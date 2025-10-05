
<?php

namespace App\Reports\Adapters;

use App\Models\AttendanceSummary;
use App\Models\Department;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;

class AttendanceSummaryReport
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
        return self::groupedQuery($filters)->count();
    }

    public static function rows(array $filters): LazyCollection
    {
        return self::groupedQuery($filters)
            ->lazy()
            ->map(fn (array $row) => self::mapRow($row));
    }

    public static function headings(): array
    {
        return [
            __('Employee'),
            __('Department'),
            __('Position'),
            __('Total Hours'),
            __('Overtime Hours'),
            __('Present Days'),
            __('Late Days'),
            __('Absent Days'),
        ];
    }

    public static function title(): string
    {
        return __('Attendance Summary Report');
    }

    public static function filename(array $filters, string $format): string
    {
        $month = self::resolveMonth($filters);
        $extension = match (strtolower($format)) {
            'excel' => 'xlsx',
            'pdf' => 'pdf',
            default => trim(strtolower($format), '.'),
        };

        return "attendance-summary-{$month}.{$extension}";
    }

    public static function pdfView(): string
    {
        return 'reports.exports.attendance-summary-pdf';
    }

    public static function pdfContext(array $filters): array
    {
        $rows = self::groupedQuery($filters)->get();

        return [
            'data' => $rows->map(fn (array $row) => self::mapRow($row))->all(),
            'summary' => self::summary($rows),
            'month' => self::resolveMonth($filters),
            'filters' => $filters,
            'generatedAt' => now(),
        ];
    }

    protected static function groupedQuery(array $filters)
    {
        $month = self::resolveMonth($filters);
        $periodStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        return AttendanceSummary::query()
            ->with(['employee.department', 'employee.position'])
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->when($filters['department_id'] ?? null, fn (Builder $query, int $departmentId) =>
                $query->whereHas('employee', fn (Builder $sub) => $sub->where('department_id', $departmentId)))
            ->get()
            ->groupBy('employee_id')
            ->map(function ($group) {
                $employee = $group->first()->employee;

                return [
                    'employee' => $employee,
                    'department' => $employee?->department,
                    'position' => $employee?->position,
                    'total_hours' => $group->sum('work_hours'),
                    'overtime_hours' => $group->sum('overtime_hours'),
                    'present_days' => $group->where('status', 'present')->count(),
                    'late_days' => $group->where('late_minutes', '>', 0)->count(),
                    'absent_days' => $group->where('status', 'absent')->count(),
                ];
            });
    }

    protected static function mapRow(array $row): array
    {
        return [
            'Employee' => $row['employee']->display_name ?? __('Unassigned'),
            'Department' => $row['department']->name_en ?? __('Unassigned'),
            'Position' => $row['position']->name_en ?? __('Unassigned'),
            'Total Hours' => number_format($row['total_hours'], 2),
            'Overtime Hours' => number_format($row['overtime_hours'], 2),
            'Present Days' => $row['present_days'],
            'Late Days' => $row['late_days'],
            'Absent Days' => $row['absent_days'],
        ];
    }

    protected static function summary($grouped): array
    {
        $collection = collect($grouped);

        return [
            'total_employees' => $collection->count(),
            'total_hours' => $collection->sum('total_hours'),
            'total_overtime' => $collection->sum('overtime_hours'),
            'average_hours' => $collection->count() ? $collection->avg('total_hours') : 0,
        ];
    }

    protected static function resolveMonth(array $filters): string
    {
        return $filters['month'] ?? now()->format('Y-m');
    }
}
