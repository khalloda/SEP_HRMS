<?php

namespace App\Reports\Adapters;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;

class EmployeeListReport
{
    public static function validationRules(): array
    {
        return [
            'department_id' => ['nullable', 'exists:departments,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'employment_status' => ['nullable', 'in:active,inactive,terminated'],
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
            ->orderBy('department_id')
            ->orderBy('last_name')
            ->lazy()
            ->map(function (Employee $employee) {
                return [
                    'Employee Code' => $employee->code,
                    'Full Name' => $employee->display_name,
                    'Department' => $employee->department->name_en ?? '-',
                    'Position' => $employee->position->name_en ?? '-',
                    'Employment Status' => ucfirst($employee->employment_status ?? $employee->status ?? ''),
                    'Hire Date' => optional($employee->hire_date)->format('Y-m-d') ?? '-',
                    'Manager' => $employee->manager->display_name ?? 'N/A',
                    'Work Email' => $employee->email ?? 'N/A',
                    'Phone' => $employee->phone ?? 'N/A',
                ];
            });
    }

    public static function headings(): array
    {
        return [
            __('Employee Code'),
            __('Full Name'),
            __('Department'),
            __('Position'),
            __('Employment Status'),
            __('Hire Date'),
            __('Manager'),
            __('Work Email'),
            __('Phone'),
        ];
    }

    public static function title(): string
    {
        return __('Employee Directory Export');
    }

    public static function filename(array $filters, string $format): string
    {
        $suffix = now()->format('Ymd_His');
        $extension = match (strtolower($format)) {
            'excel' => 'xlsx',
            'pdf' => 'pdf',
            default => trim(strtolower($format), '.'),
        };

        if (! empty($filters['department_id'])) {
            $department = Department::find($filters['department_id']);
            if ($department) {
                $name = str_replace(' ', '_', strtolower($department->name_en));

                return "employee-list-{$name}_{$suffix}.{$extension}";
            }
        }

        return "employee-list-{$suffix}.{$extension}";
    }

    public static function pdfView(): string
    {
        return 'reports.exports.employee-list-pdf';
    }

    public static function pdfContext(array $filters): array
    {
        $employees = self::baseQuery($filters)
            ->with(['department', 'position', 'employmentType', 'manager'])
            ->orderBy('department_id')
            ->orderBy('last_name')
            ->get()
            ->map(function (Employee $employee) {
                return [
                    'Employee Code' => $employee->code,
                    'Full Name' => $employee->display_name,
                    'Department' => $employee->department->name_en ?? '-',
                    'Position' => $employee->position->name_en ?? '-',
                    'Employment Status' => ucfirst($employee->employment_status ?? $employee->status ?? ''),
                    'Hire Date' => optional($employee->hire_date)->format('Y-m-d') ?? '-',
                    'Manager' => $employee->manager->display_name ?? 'N/A',
                    'Work Email' => $employee->email ?? 'N/A',
                    'Phone' => $employee->phone ?? 'N/A',
                ];
            });

        return [
            'data' => $employees,
            'filters' => $filters,
            'generatedAt' => now(),
        ];
    }

    protected static function baseQuery(array $filters): Builder
    {
        return Employee::query()
            ->with(['department', 'position', 'employmentType', 'manager'])
            ->when($filters['department_id'] ?? null, function (Builder $query, $departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($filters['position_id'] ?? null, function (Builder $query, $positionId) {
                $query->where('position_id', $positionId);
            })
            ->when($filters['employment_status'] ?? null, function (Builder $query, $status) {
                $query->where('employment_status', $status);
            });
    }
}
