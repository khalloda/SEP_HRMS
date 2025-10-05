<?php

namespace App\Services;

use App\Models\Employee;

class SalaryHistoryService
{
    public function fetch(Employee $employee, array $filters): array
    {
        // Placeholder scaffold; real implementation in next step
        return [
            'items' => [],
            'totals' => [
                'gross' => null,
                'net' => null,
            ],
            'can_view_net' => auth()->user()?->can('payroll.view_net') ?? false,
        ];
    }

    public function export(Employee $employee, array $filters, string $format)
    {
        abort(501, 'Export not implemented yet');
    }
}


