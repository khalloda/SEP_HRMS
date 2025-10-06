<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\SalaryHistoryService;
use Illuminate\Http\Request;

class EmployeeSalaryHistoryController extends Controller
{
    public function __construct(private SalaryHistoryService $historyService) {}

    public function index(Request $request, Employee $employee)
    {
        $this->authorize('view', $employee);

        abort_unless(config('payroll.use_salary_structure_history'), 404);

        $filters = [
            'from' => $request->date('from'),
            'to' => $request->date('to'),
            'contract_id' => $request->input('contract_id'),
            'preset' => $request->input('preset'),
            'page' => (int) $request->input('page', 1),
            'detail' => $request->boolean('detail', true),
            'multisheet' => $request->boolean('multisheet', false),
        ];

        $history = $this->historyService->fetch($employee, $filters);

        return view('employees.salary-history.index', compact('employee', 'history', 'filters'));
    }

    public function export(Request $request, Employee $employee)
    {
        $this->authorize('view', $employee);
        abort_unless(config('payroll.use_salary_structure_history'), 404);

        $format = $request->get('format', 'xlsx');
        $filters = [
            'from' => $request->date('from'),
            'to' => $request->date('to'),
            'contract_id' => $request->input('contract_id'),
            'preset' => $request->input('preset'),
            'detail' => $request->boolean('detail', true),
            'multisheet' => $request->boolean('multisheet', false),
        ];

        return $this->historyService->export($employee, $filters, $format);
    }
}
