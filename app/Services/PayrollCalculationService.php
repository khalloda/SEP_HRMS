<?php

namespace App\Services;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use App\Models\AttendanceSummary;
use App\Services\Payroll\ExpressionEvaluator;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollCalculationService
{
    protected ExpressionEvaluator $expressionEvaluator;

    public function __construct(ExpressionEvaluator $expressionEvaluator)
    {
        $this->expressionEvaluator = $expressionEvaluator;
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