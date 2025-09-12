<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmploymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees with advanced filtering and search.
     */
    public function index(Request $request)
    {
        // Check permission (temporarily commented for development)
        // Gate::authorize('viewAny', Employee::class);

        // Start with base query including relations
        $query = Employee::query()->withRelations();

        // Apply search if provided
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            try {
                // Try fulltext search first
                $query->search($searchTerm);
            } catch (\Exception $e) {
                // Fallback to basic search if fulltext fails
                $query->basicSearch($searchTerm);
            }
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        if ($request->filled('department_id')) {
            $query->byDepartment($request->get('department_id'));
        }

        if ($request->filled('position_id')) {
            $query->byPosition($request->get('position_id'));
        }

        if ($request->filled('employment_type_id')) {
            $query->byEmploymentType($request->get('employment_type_id'));
        }

        if ($request->filled('manager_id')) {
            $query->byManager($request->get('manager_id'));
        }

        // Filter by hire date range
        if ($request->filled('hire_date_from')) {
            $query->where('hire_date', '>=', $request->get('hire_date_from'));
        }

        if ($request->filled('hire_date_to')) {
            $query->where('hire_date', '<=', $request->get('hire_date_to'));
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'first_name');
        $sortDir = $request->get('sort_dir', 'asc');
        
        if ($sortBy === 'name') {
            $query->ordered();
        } elseif (in_array($sortBy, ['first_name', 'last_name', 'code', 'hire_date', 'status'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->ordered();
        }

        // Get paginated results
        $perPage = $request->get('per_page', 15);
        $employees = $query->paginate($perPage)->appends($request->query());

        // Get filter options for the view
        $departments = Department::ordered()->get();
        $positions = Position::ordered()->get();
        $employmentTypes = EmploymentType::ordered()->get();
        $managers = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name']);

        $statusOptions = [
            'active' => __('hrms.status.active'),
            'inactive' => __('hrms.status.inactive'),
            'terminated' => __('hrms.status.terminated'),
            'on_leave' => __('hrms.status.on_leave'),
        ];

        return view('employees.index', compact(
            'employees', 'departments', 'positions', 'employmentTypes', 
            'managers', 'statusOptions'
        ));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        // Gate::authorize('create', Employee::class);

        $departments = Department::ordered()->get();
        $positions = Position::ordered()->get();
        $employmentTypes = EmploymentType::ordered()->get();
        $managers = Employee::active()->ordered()->get(['id', 'first_name', 'last_name']);

        return view('employees.create', compact(
            'departments', 'positions', 'employmentTypes', 'managers'
        ));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('create', Employee::class);

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'arabic_name' => 'nullable|string|max:160',
            'email' => 'nullable|email|max:190|unique:employees,email',
            'phone' => 'nullable|string|max:40',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'manager_id' => 'nullable|exists:employees,id',
            'national_id' => 'nullable|string|max:20|unique:employees,national_id',
            'salary_visibility_flag' => 'boolean',
        ]);

        // Set defaults
        $validated['status'] = 'active';
        
        DB::transaction(function () use ($validated) {
            $employee = Employee::create($validated);
            
            // Log the creation
            activity('employee')
                ->performedOn($employee)
                ->log('Employee created');
        });

        return redirect()->route('employees.index')
            ->with('success', __('hrms.employee.created_successfully'));
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        // Gate::authorize('view', $employee);

        $employee->load(['department', 'position', 'employmentType', 'manager', 'directReports', 'user']);
        
        // Get additional statistics
        $stats = $employee->getStats();
        
        // Get recent activity logs
        $activities = activity()
            ->forSubject($employee)
            ->latest()
            ->limit(10)
            ->get();

        return view('employees.show', compact('employee', 'stats', 'activities'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        Gate::authorize('update', $employee);

        $departments = Department::ordered()->get();
        $positions = Position::ordered()->get();
        $employmentTypes = EmploymentType::ordered()->get();
        
        // Exclude the employee themselves from the manager list
        $managers = Employee::active()
            ->where('id', '!=', $employee->id)
            ->ordered()
            ->get(['id', 'first_name', 'last_name']);

        return view('employees.edit', compact(
            'employee', 'departments', 'positions', 'employmentTypes', 'managers'
        ));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        Gate::authorize('update', $employee);

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'arabic_name' => 'nullable|string|max:160',
            'email' => [
                'nullable',
                'email',
                'max:190',
                Rule::unique('employees', 'email')->ignore($employee->id)
            ],
            'phone' => 'nullable|string|max:40',
            'hire_date' => 'nullable|date|before_or_equal:today',
            'status' => 'required|in:active,inactive,terminated,on_leave',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'manager_id' => [
                'nullable',
                'exists:employees,id',
                function ($attribute, $value, $fail) use ($employee) {
                    if ($value == $employee->id) {
                        $fail(__('hrms.employee.cannot_be_own_manager'));
                    }
                },
            ],
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('employees', 'national_id')->ignore($employee->id)
            ],
            'salary_visibility_flag' => 'boolean',
        ]);

        DB::transaction(function () use ($employee, $validated) {
            $employee->update($validated);
            
            // Log the update
            activity('employee')
                ->performedOn($employee)
                ->log('Employee updated');
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', __('hrms.employee.updated_successfully'));
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        Gate::authorize('delete', $employee);

        // Check if employee has dependencies
        $hasContracts = $employee->contracts()->exists();
        $hasPayslips = $employee->payslips()->exists();
        $hasDirectReports = $employee->directReports()->exists();
        $hasUserAccount = $employee->user !== null;

        if ($hasContracts || $hasPayslips || $hasDirectReports || $hasUserAccount) {
            return redirect()->route('employees.show', $employee)
                ->with('error', __('hrms.employee.cannot_delete_has_dependencies'));
        }

        DB::transaction(function () use ($employee) {
            // Log the deletion before deleting
            activity('employee')
                ->performedOn($employee)
                ->log('Employee deleted');
                
            $employee->delete();
        });

        return redirect()->route('employees.index')
            ->with('success', __('hrms.employee.deleted_successfully'));
    }

    /**
     * Soft delete (terminate) an employee.
     */
    public function terminate(Request $request, Employee $employee)
    {
        Gate::authorize('terminate', $employee);

        $validated = $request->validate([
            'termination_reason' => 'required|string|max:500',
            'termination_date' => 'required|date|before_or_equal:today',
        ]);

        DB::transaction(function () use ($employee, $validated) {
            $employee->update([
                'status' => 'terminated',
            ]);
            
            // Log the termination with reason
            activity('employee')
                ->performedOn($employee)
                ->withProperties($validated)
                ->log('Employee terminated');
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', __('hrms.employee.terminated_successfully'));
    }

    /**
     * Reactivate a terminated employee.
     */
    public function reactivate(Employee $employee)
    {
        Gate::authorize('reactivate', $employee);

        if ($employee->status !== 'terminated') {
            return redirect()->route('employees.show', $employee)
                ->with('error', __('hrms.employee.not_terminated'));
        }

        DB::transaction(function () use ($employee) {
            $employee->update([
                'status' => 'active',
            ]);
            
            // Log the reactivation
            activity('employee')
                ->performedOn($employee)
                ->log('Employee reactivated');
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', __('hrms.employee.reactivated_successfully'));
    }

    /**
     * Export employees to various formats.
     */
    public function export(Request $request)
    {
        Gate::authorize('export', Employee::class);

        $format = $request->get('format', 'excel');
        
        // Apply same filters as index
        $query = Employee::query()->withRelations();
        
        // Apply filters (similar to index method)
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }
        
        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }
        
        // Add other filters as needed...
        
        $employees = $query->get();

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($employees);
            case 'csv':
                return $this->exportToCsv($employees);
            case 'excel':
            default:
                return $this->exportToExcel($employees);
        }
    }

    /**
     * Get employee statistics for dashboard.
     */
    public function statistics()
    {
        // Gate::authorize('statistics', Employee::class);

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::active()->count(),
            'inactive_employees' => Employee::byStatus('inactive')->count(),
            'terminated_employees' => Employee::byStatus('terminated')->count(),
            'on_leave_employees' => Employee::byStatus('on_leave')->count(),
            'new_hires_this_month' => Employee::whereMonth('hire_date', now()->month)
                ->whereYear('hire_date', now()->year)
                ->count(),
            'by_department' => Employee::active()
                ->select('departments.name_en', DB::raw('count(*) as total'))
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->groupBy('departments.id', 'departments.name_en')
                ->get(),
            'by_position' => Employee::active()
                ->select('positions.name_en', DB::raw('count(*) as total'))
                ->join('positions', 'employees.position_id', '=', 'positions.id')
                ->groupBy('positions.id', 'positions.name_en')
                ->get(),
            'by_employment_type' => Employee::active()
                ->select('employment_types.name_en', DB::raw('count(*) as total'))
                ->join('employment_types', 'employees.employment_type_id', '=', 'employment_types.id')
                ->groupBy('employment_types.id', 'employment_types.name_en')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Search employees (AJAX endpoint).
     */
    public function search(Request $request)
    {
        Gate::authorize('viewAny', Employee::class);

        $term = $request->get('q');
        $limit = $request->get('limit', 10);

        $employees = Employee::search($term)
            ->active()
            ->limit($limit)
            ->get(['id', 'code', 'first_name', 'last_name', 'arabic_name', 'email']);

        return response()->json([
            'results' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'text' => "{$employee->code} - {$employee->display_name}",
                    'code' => $employee->code,
                    'name' => $employee->display_name,
                    'email' => $employee->email,
                ];
            })
        ]);
    }

    // Private helper methods for exports would go here...
    // private function exportToExcel($employees) { ... }
    // private function exportToPdf($employees) { ... }
    // private function exportToCsv($employees) { ... }
}