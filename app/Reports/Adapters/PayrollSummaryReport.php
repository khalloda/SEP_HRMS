<?php

namespace App\Reports\Adapters;

use App\Models\Department;
use App\Models\PayrollRun;
use App\Models\Payslip;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;

class PayrollSummaryReport
{
    public static function validationRules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'export_format' => ['required', 'in:excel,pdf'],
        ];
    }

    public static function authorize(?Authenticatable $user): void
    {
        abort_if(! $user || ! $user->can('reports.view'), 403);
    }

    public static function totalRows(array $filters): int
    {
        return self::baseQuery($filters)->count();
    }

    public static function rows(array $filters): LazyCollection
    {
        return self::baseQuery($filters)
            ->with(['employee.department', 'employee.position'])
            ->lazy()
            ->map(fn (Payslip $payslip) => self::mapRow($payslip));
    }

    public static function headings(): array
    {
        return [
            __('Employee'),
            __('Department'),
            __('Position'),
            __('Gross Salary'),
            __('Total Deductions'),
            __('Net Salary'),
            __('Pay Period'),
        ];
    }

    public static function title(): string
    {
        return __('Payroll Summary Report');
    }

    public static function filename(array $filters, string $format): string
    {
        $month = self::resolveMonth($filters);
        $extension = match (strtolower($format)) {
            'excel' => 'xlsx',
            'pdf' => 'pdf',
            default => trim(strtolower($format), '.'),
        };

        return "payroll-summary-{$month}.{$extension}";
    }

    public static function pdfView(): string
    {
        return 'reports.exports.payroll-summary-pdf';
    }

    public static function pdfContext(array $filters): array
    {
        $payslips = self::baseQuery($filters)
            ->with(['employee.department', 'employee.position'])
            ->get();

        return [
            'data' => $payslips->map(fn (Payslip $payslip) => self::mapRow($payslip))->all(),
            'summary' => self::summary($filters, $payslips),
            'month' => self::resolveMonth($filters),
            'filters' => $filters,
            'generatedAt' => now(),
        ];
    }

    public static function summary(array $filters = [], $payslips = null): array
    {
        $collection = $payslips ?? self::baseQuery($filters)
            ->with(['employee.department'])
            ->get();

        $gross = fn (Payslip $payslip) => $payslip->gross_salary ?? $payslip->gross_pay ?? 0;
        $net = fn (Payslip $payslip) => $payslip->net_salary ?? $payslip->net_pay ?? 0;

        return [
            'total_employees' => $collection->count(),
            'total_gross' => $collection->sum($gross),
            'total_net' => $collection->sum($net),
            'total_deductions' => $collection->sum(fn ($p) => $p->total_deductions),
            'by_department' => $collection
                ->groupBy(fn (Payslip $payslip) => $payslip->employee->department->name_en ?? __('Unassigned'))
                ->map(fn ($group) => [
                    'count' => $group->count(),
                    'gross' => $group->sum($gross),
                    'net' => $group->sum($net),
                ])
                ->all(),
        ];
    }

    protected static function baseQuery(array $filters): Builder
    {
        $month = self::resolveMonth($filters);
        $periodStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        return Payslip::query()
            ->whereBetween('pay_period_start', [$periodStart, $periodEnd])
            ->when($filters['department_id'] ?? null, function (Builder $query, int $departmentId) {
                $query->whereHas('employee', fn (Builder $q) => $q->where('department_id', $departmentId));
            });
    }

    protected static function mapRow(Payslip $payslip): array
    {
        $gross = $payslip->gross_salary ?? $payslip->gross_pay ?? 0;
        $net = $payslip->net_salary ?? $payslip->net_pay ?? 0;

        return [
            'Employee' => $payslip->employee->display_name ?? $payslip->employee_name,
            'Department' => $payslip->employee->department->name_en ?? $payslip->department_name ?? '-',
            'Position' => $payslip->employee->position->name_en ?? $payslip->position_name ?? '-',
            'Gross Salary' => number_format($gross, 2),
            'Total Deductions' => number_format($payslip->total_deductions ?? 0, 2),
            'Net Salary' => number_format($net, 2),
            'Pay Period' => sprintf('%s - %s',
                optional($payslip->pay_period_start)->format('Y-m-d') ?? '-',
                optional($payslip->pay_period_end)->format('Y-m-d') ?? '-')
        ];
    }

    protected static function resolveMonth(array $filters): string
    {
        return $filters['month'] ?? now()->format('Y-m');
    }
}
