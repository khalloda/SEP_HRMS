<?php

namespace App\Services;

use App\Exceptions\PayrollDependencyCycleException;
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
use Illuminate\Support\Str;

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

                $this->assertNoCircularDependencies($salaryStructure);

                // Delete existing lines
                $payslip->payslipLines()->delete();

                // Recalculate components
                $this->calculatePayslipComponents($payslip, $salaryStructure);

                // Update totals
                $payslip->updateTotals();
            });

            return true;
        } catch (PayrollDependencyCycleException $e) {
            Log::error('Failed to recalculate payslip', [
                'payslip_id' => $payslip->id,
                'structure_id' => $e->getStructureId() ?? ($payslip->salaryStructure ? $payslip->salaryStructure->id : null),
                'cycle' => $e->getCycle(),
                'error' => $e->getMessage(),
                'correlation_id' => $this->currentCorrelationId(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to recalculate payslip', [
                'payslip_id' => $payslip->id,
                'error' => $e->getMessage(),
                'correlation_id' => $this->currentCorrelationId(),
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

        $checkedStructures = [];

        foreach ($eligibleEmployees as $employee) {
            $salaryStructure = $employee->currentSalaryStructure;

            if (!$salaryStructure) {
                continue;
            }

            $structureKey = $salaryStructure->id ?? spl_object_hash($salaryStructure);

            if (isset($checkedStructures[$structureKey])) {
                continue;
            }

            try {
                $this->assertNoCircularDependencies($salaryStructure);
            } catch (PayrollDependencyCycleException $exception) {
                $issues[] = $exception->getMessage();

                Log::error('Circular payroll component dependency detected', [
                    'payroll_run_id' => $payrollRun->id,
                    'structure_id' => $exception->getStructureId() ?? $salaryStructure->id,
                    'cycle' => $exception->getCycle(),
                    'correlation_id' => $this->currentCorrelationId(),
                ]);
            }

            $checkedStructures[$structureKey] = true;
        }

        return $issues;
    }

    /**
     * Calculate payroll run for eligible employees.
     *
     * @param array<string, mixed> $context
     */
    public function calculatePayrollRun(PayrollRun $payrollRun, array $context = []): array
    {
        $correlationId = $context['correlation_id'] ?? $this->currentCorrelationId() ?? (string) Str::uuid();
        $context['correlation_id'] = $correlationId;

        $result = [
            'success' => true,
            'employees_processed' => 0,
            'payslips_created' => 0,
            'errors' => [],
        ];

        $this->markRunAsCalculating($payrollRun);

        try {
            $eligibleEmployees = $this->getEligibleEmployees($payrollRun);

            if ($eligibleEmployees->isEmpty()) {
                $result['errors'][] = 'No eligible employees were processed.';
            } else {
                $chunkSize = max(1, (int) config('payroll.chunk_size', 50));

                foreach ($eligibleEmployees->chunk($chunkSize) as $chunk) {
                    $chunkResult = $this->processEmployeeChunk($payrollRun, $chunk, $context);

                    $result['employees_processed'] += $chunkResult['employees_processed'];
                    $result['payslips_created'] += $chunkResult['payslips_created'];

                    if (!empty($chunkResult['errors'])) {
                        $result['errors'] = array_merge($result['errors'], $chunkResult['errors']);
                    }
                }
            }

            $totals = $this->updateRunTotals($payrollRun);

            $result['success'] = empty($result['errors']);

            $this->markRunAsCalculated($payrollRun, $result, $totals);

            return $result;
        } catch (PayrollDependencyCycleException $exception) {
            $result['success'] = false;
            $result['errors'][] = $exception->getMessage();

            Log::error('Payroll run calculation failed due to dependency cycle', [
                'payroll_run_id' => $payrollRun->id,
                'structure_id' => $exception->getStructureId(),
                'cycle' => $exception->getCycle(),
                'correlation_id' => $correlationId,
            ]);
        } catch (\Throwable $throwable) {
            $result['success'] = false;
            $result['errors'][] = $throwable->getMessage();

            Log::error('Payroll run calculation failed', [
                'payroll_run_id' => $payrollRun->id,
                'error' => $throwable->getMessage(),
                'correlation_id' => $correlationId,
            ]);
        }

        $this->markRunAsFailed($payrollRun);

        return $result;
    }

    /**
     * Process a chunk of employees during calculation.
     *
     * @param array<string, mixed> $context
     */
    protected function processEmployeeChunk(PayrollRun $payrollRun, Collection $employees, array $context): array
    {
        $chunkResult = [
            'success' => true,
            'employees_processed' => 0,
            'payslips_created' => 0,
            'errors' => [],
        ];

        DB::transaction(function () use ($payrollRun, $employees, $context, &$chunkResult) {
            foreach ($employees as $employee) {
                try {
                    $calculation = $this->calculateEmployeePayroll($payrollRun, $employee, $context);

                    $chunkResult['employees_processed']++;

                    if (!empty($calculation['errors'])) {
                        $chunkResult['errors'] = array_merge($chunkResult['errors'], $calculation['errors']);
                        $chunkResult['success'] = false;
                    }

                    if (!empty($calculation['payslip_created'])) {
                        $chunkResult['payslips_created']++;
                    }
                } catch (PayrollDependencyCycleException $exception) {
                    throw $exception;
                } catch (\Throwable $throwable) {
                    $chunkResult['success'] = false;
                    $chunkResult['errors'][] = $throwable->getMessage();

                    Log::error('Failed to calculate payroll for employee', [
                        'payroll_run_id' => $payrollRun->id,
                        'employee_id' => $employee->id ?? null,
                        'error' => $throwable->getMessage(),
                        'correlation_id' => $context['correlation_id'] ?? null,
                    ]);
                }
            }
        });

        return $chunkResult;
    }

    /**
     * Calculate payroll data for a single employee.
     *
     * @param array<string, mixed> $context
     */
    protected function calculateEmployeePayroll(PayrollRun $payrollRun, Employee $employee, array $context): array
    {
        $salaryStructure = $employee->currentSalaryStructure;

        if (!$salaryStructure) {
            throw new \RuntimeException('No active salary structure found for employee.');
        }

        $this->assertNoCircularDependencies($salaryStructure);

        $payslip = $payrollRun->payslips()->updateOrCreate([
            'employee_id' => $employee->id,
        ], [
            'salary_structure_id' => $salaryStructure->id,
            'employee_code' => $employee->code,
            'employee_name' => $employee->full_name,
            'employee_arabic_name' => $employee->arabic_name,
            'department_name' => optional($employee->department)->name,
            'position_name' => optional($employee->position)->name,
            'pay_period_start' => $payrollRun->pay_period_start,
            'pay_period_end' => $payrollRun->pay_period_end,
            'pay_date' => $payrollRun->pay_date,
            'currency' => $payrollRun->currency,
            'gross_pay' => 0,
            'total_deductions' => 0,
            'net_pay' => 0,
            'basic_salary' => 0,
            'status' => Payslip::STATUS_CALCULATED,
            'generated_at' => now(),
        ]);

        return [
            'success' => true,
            'payslip_created' => (bool) $payslip,
            'errors' => [],
        ];
    }

    protected function markRunAsCalculating(PayrollRun $payrollRun): void
    {
        if ($payrollRun->status !== PayrollRun::STATUS_CALCULATING) {
            $payrollRun->forceFill(['status' => PayrollRun::STATUS_CALCULATING])->saveQuietly();
        }
    }

    protected function markRunAsCalculated(PayrollRun $payrollRun, array $result, array $totals): void
    {
        $payrollRun->forceFill([
            'status' => PayrollRun::STATUS_CALCULATED,
            'total_employees' => $totals['total_employees'] ?? $result['employees_processed'],
            'total_gross' => $totals['total_gross'] ?? 0,
            'total_net' => $totals['total_net'] ?? 0,
            'total_deductions' => $totals['total_deductions'] ?? 0,
        ])->saveQuietly();

        if (!empty($result['errors'])) {
            Log::warning('Payroll run calculated with warnings', [
                'payroll_run_id' => $payrollRun->id,
                'warnings' => $result['errors'],
            ]);
        }
    }

    protected function markRunAsFailed(PayrollRun $payrollRun): void
    {
        $payrollRun->forceFill(['status' => PayrollRun::STATUS_DRAFT])->saveQuietly();
    }

    protected function updateRunTotals(PayrollRun $payrollRun): array
    {
        $aggregates = $payrollRun->payslips()
            ->selectRaw('COUNT(*) as employees, COALESCE(SUM(gross_pay), 0) as gross, COALESCE(SUM(total_deductions), 0) as deductions, COALESCE(SUM(net_pay), 0) as net')
            ->first();

        $totals = [
            'total_employees' => (int) ($aggregates->employees ?? 0),
            'total_gross' => (float) ($aggregates->gross ?? 0),
            'total_deductions' => (float) ($aggregates->deductions ?? 0),
            'total_net' => (float) ($aggregates->net ?? 0),
        ];

        $payrollRun->forceFill($totals)->saveQuietly();

        return $totals;
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

    public function assertNoCircularDependencies(SalaryStructure $salaryStructure): void
    {
        $graph = $this->buildDependencyGraph($salaryStructure);

        if (empty($graph)) {
            return;
        }

        $visited = [];
        $visiting = [];
        $stack = [];

        $traverse = function (string $code) use (&$traverse, &$visited, &$visiting, &$stack, $graph, $salaryStructure) {
            if (isset($visited[$code])) {
                return;
            }

            if (isset($visiting[$code])) {
                $cycleStartIndex = array_search($code, $stack, true);
                $cycle = $cycleStartIndex === false ? [$code] : array_slice($stack, $cycleStartIndex);
                $cycle[] = $code;

                throw new PayrollDependencyCycleException($cycle, $salaryStructure->id);
            }

            $visiting[$code] = true;
            $stack[] = $code;

            foreach ($graph[$code] as $dependency) {
                if (!array_key_exists($dependency, $graph)) {
                    continue;
                }

                $traverse($dependency);
            }

            array_pop($stack);
            unset($visiting[$code]);
            $visited[$code] = true;
        };

        foreach (array_keys($graph) as $componentCode) {
            $traverse($componentCode);
        }
    }

    protected function buildDependencyGraph(SalaryStructure $salaryStructure): array
    {
        $components = $salaryStructure->relationLoaded('structureComponents')
            ? $salaryStructure->structureComponents
            : $salaryStructure->structureComponents()->with('component')->get();

        if ($components->isEmpty()) {
            return [];
        }

        $availableCodes = [];

        foreach ($components as $structureComponent) {
            $component = $structureComponent->component;
            $code = $component ? $this->normaliseComponentCode($component->code) : null;

            if ($code === null) {
                continue;
            }

            $availableCodes[$code] = true;
        }

        $graph = [];

        foreach ($components as $structureComponent) {
            $component = $structureComponent->component;
            $code = $component ? $this->normaliseComponentCode($component->code) : null;

            if ($code === null) {
                continue;
            }

            $dependencies = [];

            foreach ($structureComponent->dependency_codes as $dependencyCode) {
                $normalised = $this->normaliseComponentCode($dependencyCode);

                if ($normalised && isset($availableCodes[$normalised])) {
                    $dependencies[] = $normalised;
                }
            }

            $graph[$code] = array_values(array_unique($dependencies));
        }

        return $graph;
    }

    protected function normaliseComponentCode(?string $code): ?string
    {
        if ($code === null) {
            return null;
        }

        $code = trim($code);

        if ($code === '') {
            return null;
        }

        return strtoupper($code);
    }

    protected function currentCorrelationId(): ?string
    {
        $request = request();

        if (!$request) {
            return null;
        }

        return $request->attributes->get('correlation_id');
    }

}
