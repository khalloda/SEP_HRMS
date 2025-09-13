<?php

namespace App\Services;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use App\Models\AttendanceSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollCalculationService
{
    /**
     * Calculate payroll for all eligible employees in a payroll run.
     */
    public function calculatePayrollRun(PayrollRun $payrollRun): array
    {
        $results = [
            'success' => true,
            'employees_processed' => 0,
            'employees_skipped' => 0,
            'payslips_created' => 0,
            'errors' => [],
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
        ];

        try {
            // Update status to calculating
            $payrollRun->update(['status' => PayrollRun::STATUS_CALCULATING]);

            // Get eligible employees (active with salary structures)
            $employees = $this->getEligibleEmployees($payrollRun);

            DB::transaction(function () use ($payrollRun, $employees, &$results) {
                foreach ($employees as $employee) {
                    try {
                        $payslip = $this->calculateEmployeePayslip($payrollRun, $employee);

                        if ($payslip) {
                            $results['employees_processed']++;
                            $results['payslips_created']++;
                            $results['total_gross'] += $payslip->gross_pay;
                            $results['total_net'] += $payslip->net_pay;
                            $results['total_deductions'] += $payslip->total_deductions;
                        } else {
                            $results['employees_skipped']++;
                            $results['errors'][] = "No active salary structure for employee {$employee->code}";
                        }
                    } catch (\Exception $e) {
                        $results['employees_skipped']++;
                        $results['errors'][] = "Error calculating payslip for employee {$employee->code}: {$e->getMessage()}";
                        Log::error("Payroll calculation error for employee {$employee->code}", [
                            'error' => $e->getMessage(),
                            'payroll_run_id' => $payrollRun->id
                        ]);
                    }
                }

                // Update payroll run totals
                $payrollRun->update([
                    'status' => PayrollRun::STATUS_CALCULATED,
                    'total_employees' => $results['employees_processed'],
                    'total_gross' => $results['total_gross'],
                    'total_net' => $results['total_net'],
                    'total_deductions' => $results['total_deductions'],
                ]);
            });

        } catch (\Exception $e) {
            $results['success'] = false;
            $results['errors'][] = "Fatal error during payroll calculation: {$e->getMessage()}";

            $payrollRun->update(['status' => PayrollRun::STATUS_DRAFT]);

            Log::error("Fatal payroll calculation error", [
                'error' => $e->getMessage(),
                'payroll_run_id' => $payrollRun->id
            ]);
        }

        return $results;
    }

    /**
     * Calculate payslip for a specific employee.
     */
    public function calculateEmployeePayslip(PayrollRun $payrollRun, Employee $employee): ?Payslip
    {
        // Get active salary structure
        $salaryStructure = $employee->currentSalaryStructure;
        if (!$salaryStructure) {
            return null;
        }

        // Check if payslip already exists for this employee in this payroll run
        $existingPayslip = $payrollRun->payslips()
            ->where('employee_id', $employee->id)
            ->first();

        if ($existingPayslip) {
            // Delete existing payslip and recalculate
            $existingPayslip->payslipLines()->delete();
            $existingPayslip->delete();
        }

        // Create new payslip
        $payslip = $payrollRun->payslips()->create([
            'employee_id' => $employee->id,
            'salary_structure_id' => $salaryStructure->id,
            'employee_code' => $employee->code,
            'employee_name' => $employee->full_name,
            'employee_arabic_name' => $employee->arabic_name,
            'department_name' => $employee->department?->name,
            'position_name' => $employee->position?->name,
            'pay_period_start' => $payrollRun->pay_period_start,
            'pay_period_end' => $payrollRun->pay_period_end,
            'pay_date' => $payrollRun->pay_date,
            'currency' => $payrollRun->currency,
            'status' => Payslip::STATUS_DRAFT,
        ]);

        // Calculate components
        $this->calculatePayslipComponents($payslip, $salaryStructure);

        // Update totals
        $payslip->updateTotals();

        // Mark as calculated
        $payslip->update(['status' => Payslip::STATUS_CALCULATED]);

        return $payslip;
    }

    /**
     * Calculate individual components for a payslip.
     */
    protected function calculatePayslipComponents(Payslip $payslip, SalaryStructure $salaryStructure): void
    {
        $salaryStructure->load(['structureComponents.component']);
        $calculatedValues = [];

        // Get attendance data for this payroll period
        $attendanceData = $this->getAttendanceDataForPayroll($payslip);

        // Process components in priority order
        $components = $salaryStructure->structureComponents()
            ->with('component')
            ->orderBy('priority_order')
            ->get();

        foreach ($components as $structureComponent) {
            $component = $structureComponent->component;

            // Calculate component value
            $calculatedAmount = $this->calculateComponentValue(
                $structureComponent,
                $calculatedValues,
                $payslip,
                $attendanceData
            );

            // Store calculated value for reference by other components
            $calculatedValues[$component->code] = $calculatedAmount;

            // Create payslip line
            $payslip->payslipLines()->create([
                'salary_component_id' => $component->id,
                'component_code' => $component->code,
                'component_name_en' => $component->name_en,
                'component_name_ar' => $component->name_ar,
                'component_type' => $component->comp_type,
                'calculation_mode' => $component->calc_mode,
                'amount' => $calculatedAmount,
                'formula_used' => $structureComponent->formula_expr,
                'priority_order' => $structureComponent->priority_order,
                'include_in_gross' => $component->comp_type === 'earning',
                'taxable' => $component->taxable,
            ]);
        }
    }

    /**
     * Calculate the value for a specific component.
     */
    protected function calculateComponentValue(
        $structureComponent,
        array $calculatedValues,
        Payslip $payslip,
        array $attendanceData = []
    ): float {
        $component = $structureComponent->component;

        switch ($component->calc_mode) {
            case 'fixed':
                return (float) $structureComponent->value_numeric ?? 0;

            case 'formula':
                return $this->evaluateFormula(
                    $structureComponent->formula_expr,
                    $calculatedValues,
                    $payslip,
                    $attendanceData
                );

            case 'variable_net_based':
                // For now, return 0 - this would be overridden by manual entries or attendance
                return 0;

            default:
                return 0;
        }
    }

    /**
     * Evaluate a formula expression safely.
     */
    protected function evaluateFormula(string $formula, array $calculatedValues, Payslip $payslip, array $attendanceData = []): float
    {
        if (empty($formula)) {
            return 0;
        }

        try {
            // Replace component codes with their calculated values
            $processedFormula = $this->replaceFormulaVariables($formula, $calculatedValues, $attendanceData);

            // Basic arithmetic evaluation (secure)
            return $this->safeEvaluate($processedFormula);
        } catch (\Exception $e) {
            Log::warning("Formula evaluation failed", [
                'formula' => $formula,
                'processed_formula' => $processedFormula ?? '',
                'payslip_id' => $payslip->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Replace formula variables with actual values.
     */
    protected function replaceFormulaVariables(string $formula, array $calculatedValues, array $attendanceData = []): string
    {
        // Replace component codes with their values
        foreach ($calculatedValues as $code => $value) {
            $formula = str_replace($code, $value, $formula);
        }

        // Replace attendance variables
        $attendanceReplacements = [
            'WORK_DAYS' => $attendanceData['work_days'] ?? 0,
            'PRESENT_DAYS' => $attendanceData['present_days'] ?? 0,
            'ABSENT_DAYS' => $attendanceData['absent_days'] ?? 0,
            'LATE_DAYS' => $attendanceData['late_days'] ?? 0,
            'OVERTIME_HOURS' => $attendanceData['overtime_hours'] ?? 0,
            'TOTAL_WORK_HOURS' => $attendanceData['total_work_hours'] ?? 0,
            'LATE_MINUTES' => $attendanceData['total_late_minutes'] ?? 0,
            'ATTENDANCE_RATE' => $attendanceData['attendance_rate'] ?? 100,
        ];

        foreach ($attendanceReplacements as $variable => $value) {
            $formula = str_replace($variable, $value, $formula);
        }

        // Replace common formula variables
        $commonReplacements = [
            'GROSS' => array_sum(array_filter($calculatedValues, fn($v, $k) =>
                $this->isEarningComponent($k), ARRAY_FILTER_USE_BOTH)),
            'NET' => 0, // Will be calculated at the end
        ];

        foreach ($commonReplacements as $variable => $value) {
            $formula = str_replace($variable, $value, $formula);
        }

        return $formula;
    }

    /**
     * Check if a component code represents an earning component.
     */
    protected function isEarningComponent(string $componentCode): bool
    {
        $earningCodes = ['BASIC_SALARY', 'HOUSING_ALLOWANCE', 'TRANSPORT_ALLOWANCE', 'BONUS'];
        return in_array($componentCode, $earningCodes);
    }

    /**
     * Safely evaluate a mathematical expression.
     */
    protected function safeEvaluate(string $expression): float
    {
        // Remove any non-numeric, non-operator characters for security
        $cleanExpression = preg_replace('/[^0-9+\-*\/\(\)\.\s]/', '', $expression);

        // Basic validation
        if (empty($cleanExpression) || !$this->isValidMathExpression($cleanExpression)) {
            return 0;
        }

        // Use eval carefully with cleaned expression
        try {
            $result = eval("return $cleanExpression;");
            return is_numeric($result) ? (float) $result : 0;
        } catch (\ParseError|\Error|\Exception $e) {
            return 0;
        }
    }

    /**
     * Validate that the expression contains only safe mathematical operations.
     */
    protected function isValidMathExpression(string $expression): bool
    {
        // Check for balanced parentheses
        $openCount = substr_count($expression, '(');
        $closeCount = substr_count($expression, ')');
        if ($openCount !== $closeCount) {
            return false;
        }

        // Check that we only have numbers, operators, and parentheses
        return preg_match('/^[0-9+\-*\/\(\)\.\s]+$/', $expression);
    }

    /**
     * Get employees eligible for payroll processing.
     */
    protected function getEligibleEmployees(PayrollRun $payrollRun): Collection
    {
        return Employee::active()
            ->whereHas('currentSalaryStructure', function ($query) use ($payrollRun) {
                $query->where('effective_from', '<=', $payrollRun->pay_period_end)
                      ->where(function ($q) use ($payrollRun) {
                          $q->whereNull('effective_to')
                            ->orWhere('effective_to', '>=', $payrollRun->pay_period_start);
                      });
            })
            ->with(['department', 'position', 'currentSalaryStructure.structureComponents.component'])
            ->get();
    }

    /**
     * Recalculate a single payslip.
     */
    public function recalculatePayslip(Payslip $payslip): bool
    {
        try {
            DB::transaction(function () use ($payslip) {
                // Get the salary structure
                $salaryStructure = $payslip->salaryStructure;
                if (!$salaryStructure) {
                    throw new \Exception('Salary structure not found for payslip');
                }

                // Delete existing lines
                $payslip->payslipLines()->delete();

                // Recalculate components
                $this->calculatePayslipComponents($payslip, $salaryStructure);

                // Update totals
                $payslip->updateTotals();
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to recalculate payslip", [
                'payslip_id' => $payslip->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Validate payroll run before calculation.
     */
    public function validatePayrollRun(PayrollRun $payrollRun): array
    {
        $issues = [];

        // Check if payroll run is in correct status
        if (!$payrollRun->canBeCalculated()) {
            $issues[] = "Payroll run must be in draft status to calculate";
        }

        // Check if there are eligible employees
        $eligibleEmployees = $this->getEligibleEmployees($payrollRun);
        if ($eligibleEmployees->isEmpty()) {
            $issues[] = "No eligible employees found for this payroll run";
        }

        // Check for employees without salary structures
        $employeesWithoutStructures = Employee::active()
            ->whereDoesntHave('currentSalaryStructure')
            ->count();

        if ($employeesWithoutStructures > 0) {
            $issues[] = "{$employeesWithoutStructures} active employees don't have salary structures";
        }

        return $issues;
    }

    /**
     * Get payroll calculation summary.
     */
    public function getCalculationSummary(PayrollRun $payrollRun): array
    {
        $payslips = $payrollRun->payslips()->with(['employee.department', 'payslipLines'])->get();

        $summary = [
            'total_employees' => $payslips->count(),
            'total_gross' => $payslips->sum('gross_pay'),
            'total_net' => $payslips->sum('net_pay'),
            'total_deductions' => $payslips->sum('total_deductions'),
            'by_department' => [],
            'by_component' => [],
            'earnings_breakdown' => [],
            'deductions_breakdown' => [],
        ];

        // Group by department
        $summary['by_department'] = $payslips->groupBy('employee.department.name')
            ->map(function ($deptPayslips) {
                return [
                    'count' => $deptPayslips->count(),
                    'gross' => $deptPayslips->sum('gross_pay'),
                    'net' => $deptPayslips->sum('net_pay'),
                    'deductions' => $deptPayslips->sum('total_deductions'),
                ];
            });

        // Component analysis
        $allLines = $payslips->flatMap->payslipLines;

        $summary['earnings_breakdown'] = $allLines->where('component_type', 'earning')
            ->groupBy('component_code')
            ->map(function ($lines) {
                return [
                    'name' => $lines->first()->component_display_name,
                    'total' => $lines->sum('amount'),
                    'count' => $lines->count(),
                ];
            });

        $summary['deductions_breakdown'] = $allLines->where('component_type', 'deduction')
            ->groupBy('component_code')
            ->map(function ($lines) {
                return [
                    'name' => $lines->first()->component_display_name,
                    'total' => $lines->sum('amount'),
                    'count' => $lines->count(),
                ];
            });

        return $summary;
    }

    /**
     * Get attendance data for payroll calculations.
     */
    protected function getAttendanceDataForPayroll(Payslip $payslip): array
    {
        // Get attendance summaries for the payroll period
        $attendanceSummaries = AttendanceSummary::forEmployee($payslip->employee_id)
            ->dateRange($payslip->pay_period_start, $payslip->pay_period_end)
            ->get();

        if ($attendanceSummaries->isEmpty()) {
            // Return default values if no attendance data
            return [
                'work_days' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'partial_days' => 0,
                'late_days' => 0,
                'overtime_days' => 0,
                'total_work_hours' => 0,
                'total_overtime_hours' => 0,
                'total_late_minutes' => 0,
                'total_break_minutes' => 0,
                'attendance_rate' => 0,
                'anomaly_count' => 0,
            ];
        }

        // Calculate attendance statistics
        $totalDays = $attendanceSummaries->count();
        $presentDays = $attendanceSummaries->where('status', 'present')->count();
        $absentDays = $attendanceSummaries->where('status', 'absent')->count();
        $partialDays = $attendanceSummaries->where('status', 'partial')->count();
        $lateDays = $attendanceSummaries->where('late_minutes', '>', 0)->count();
        $overtimeDays = $attendanceSummaries->where('overtime_minutes', '>', 0)->count();

        $totalWorkMinutes = $attendanceSummaries->sum('total_work_minutes');
        $totalOvertimeMinutes = $attendanceSummaries->sum('overtime_minutes');
        $totalLateMinutes = $attendanceSummaries->sum('late_minutes');
        $totalBreakMinutes = $attendanceSummaries->sum('break_minutes');

        $attendanceRate = $totalDays > 0 ?
            round((($presentDays + $partialDays) / $totalDays) * 100, 2) : 0;

        $anomalyCount = $attendanceSummaries->filter(function ($summary) {
            return !empty($summary->anomalies);
        })->count();

        return [
            'work_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'partial_days' => $partialDays,
            'late_days' => $lateDays,
            'overtime_days' => $overtimeDays,
            'total_work_hours' => round($totalWorkMinutes / 60, 2),
            'total_overtime_hours' => round($totalOvertimeMinutes / 60, 2),
            'total_late_minutes' => $totalLateMinutes,
            'total_break_minutes' => $totalBreakMinutes,
            'attendance_rate' => $attendanceRate,
            'anomaly_count' => $anomalyCount,
        ];
    }
}