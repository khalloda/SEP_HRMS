<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryStructureController extends Controller
{
    /**
     * Display salary structures for an employee.
     */
    public function index(Employee $employee)
    {
        Gate::authorize('view', $employee);

        $employee->load(['currentSalaryStructure.components', 'salaryStructures.components']);
        $structures = $employee->salaryStructures()
            ->with('components')
            ->orderBy('effective_from', 'desc')
            ->paginate(10);

        return view('salary-structures.index', compact('employee', 'structures'));
    }

    /**
     * Show the form for creating a new salary structure for an employee.
     */
    public function create(Employee $employee)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('create', SalaryStructure::class);

        $components = SalaryComponent::ordered()->get()->groupBy('comp_type');

        // Get current structure to pre-populate
        $currentStructure = $employee->currentSalaryStructure;

        return view('salary-structures.create', compact('employee', 'components', 'currentStructure'));
    }

    /**
     * Store a newly created salary structure for an employee.
     */
    public function store(Request $request, Employee $employee)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('create', SalaryStructure::class);

        $currencyKeys = array_keys(payrollCurrencies());
        if (empty($currencyKeys)) {
            $currencyKeys = ['EGP'];
        }

        $validated = $request->validate([
            'currency' => 'required|string|max:3|in:' . implode(',', $currencyKeys),
            'effective_from' => 'required|date|after_or_equal:today',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:500',
            'components' => 'required|array|min:1',
            'components.*.component_id' => 'required|exists:salary_components,id',
            'components.*.value_numeric' => 'nullable|numeric|min:0',
            'components.*.formula_expr' => 'nullable|string|max:255',
            'components.*.priority_order' => 'required|integer|min:1|max:999',
        ]);

        DB::transaction(function () use ($validated, $employee, $request) {
            // End current structure if exists and new structure starts today or in future
            $effectiveFrom = Carbon::parse($validated['effective_from']);
            $currentStructure = $employee->currentSalaryStructure;

            if ($currentStructure && $effectiveFrom <= now()) {
                $currentStructure->update([
                    'effective_to' => $effectiveFrom->copy()->subDay()
                ]);
            }

            // Create new structure
            $structure = $employee->salaryStructures()->create([
                'currency' => $validated['currency'],
                'effective_from' => $effectiveFrom,
                'effective_to' => $validated['effective_to'] ? Carbon::parse($validated['effective_to']) : null,
                'notes' => $validated['notes'],
            ]);

            // Add components to structure
            foreach ($validated['components'] as $componentData) {
                $component = SalaryComponent::find($componentData['component_id']);

                $structure->structureComponents()->create([
                    'component_id' => $component->id,
                    'value_numeric' => $componentData['value_numeric'] ?? null,
                    'formula_expr' => $componentData['formula_expr'] ?? null,
                    'priority_order' => $componentData['priority_order'],
                ]);
            }

            // Log the activity
            activity('salary_structure')
                ->performedOn($structure)
                ->withProperties([
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->display_name,
                    'effective_from' => $effectiveFrom->format('Y-m-d'),
                    'components_count' => count($validated['components'])
                ])
                ->log('Salary structure created');
        });

        return redirect()->route('employees.salary-structures.index', $employee)
            ->with('success', __('hrms.salary_structure.created_successfully'));
    }

    /**
     * Display the specified salary structure.
     */
    public function show(Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('view', $employee);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        $salaryStructure->load(['components', 'structureComponents.component']);

        return view('salary-structures.show', compact('employee', 'salaryStructure'));
    }

    /**
     * Show the form for editing the specified salary structure.
     */
    public function edit(Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('update', $salaryStructure);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        // Don't allow editing expired structures
        if ($salaryStructure->is_expired) {
            return redirect()->route('employees.salary-structures.show', [$employee, $salaryStructure])
                ->with('error', __('hrms.salary_structure.cannot_edit_expired'));
        }

        $salaryStructure->load(['structureComponents.component']);
        $components = SalaryComponent::ordered()->get()->groupBy('comp_type');

        return view('salary-structures.edit', compact('employee', 'salaryStructure', 'components'));
    }

    /**
     * Update the specified salary structure.
     */
    public function update(Request $request, Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('update', $salaryStructure);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        // Don't allow updating expired structures
        if ($salaryStructure->is_expired) {
            return redirect()->route('employees.salary-structures.show', [$employee, $salaryStructure])
                ->with('error', __('hrms.salary_structure.cannot_edit_expired'));
        }

        $currencyKeys = array_keys(payrollCurrencies());
        if (empty($currencyKeys)) {
            $currencyKeys = ['EGP'];
        }

        $validated = $request->validate([
            'currency' => 'required|string|max:3|in:' . implode(',', $currencyKeys),
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:500',
            'components' => 'required|array|min:1',
            'components.*.component_id' => 'required|exists:salary_components,id',
            'components.*.value_numeric' => 'nullable|numeric|min:0',
            'components.*.formula_expr' => 'nullable|string|max:255',
            'components.*.priority_order' => 'required|integer|min:1|max:999',
        ]);

        DB::transaction(function () use ($validated, $salaryStructure) {
            // Update structure
            $salaryStructure->update([
                'currency' => $validated['currency'],
                'effective_from' => Carbon::parse($validated['effective_from']),
                'effective_to' => $validated['effective_to'] ? Carbon::parse($validated['effective_to']) : null,
                'notes' => $validated['notes'],
            ]);

            // Remove existing components
            $salaryStructure->structureComponents()->delete();

            // Add updated components
            foreach ($validated['components'] as $componentData) {
                $salaryStructure->structureComponents()->create([
                    'component_id' => $componentData['component_id'],
                    'value_numeric' => $componentData['value_numeric'] ?? null,
                    'formula_expr' => $componentData['formula_expr'] ?? null,
                    'priority_order' => $componentData['priority_order'],
                ]);
            }

            // Log the activity
            activity('salary_structure')
                ->performedOn($salaryStructure)
                ->withProperties([
                    'employee_id' => $salaryStructure->employee_id,
                    'components_count' => count($validated['components'])
                ])
                ->log('Salary structure updated');
        });

        return redirect()->route('employees.salary-structures.show', [$employee, $salaryStructure])
            ->with('success', __('hrms.salary_structure.updated_successfully'));
    }

    /**
     * Clone an existing salary structure for a new period.
     */
    public function clone(Request $request, Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('create', SalaryStructure::class);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        $validated = $request->validate([
            'effective_from' => 'required|date|after_or_equal:today',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:500',
        ]);

        $newStructure = DB::transaction(function () use ($validated, $salaryStructure) {
            $effectiveFrom = Carbon::parse($validated['effective_from']);

            // End current structure if it's active
            $currentStructure = $salaryStructure->employee->currentSalaryStructure;
            if ($currentStructure && $effectiveFrom <= now()) {
                $currentStructure->update([
                    'effective_to' => $effectiveFrom->copy()->subDay()
                ]);
            }

            // Clone the structure
            $newStructure = $salaryStructure->replicate();
            $newStructure->effective_from = $effectiveFrom;
            $newStructure->effective_to = $validated['effective_to'] ? Carbon::parse($validated['effective_to']) : null;
            $newStructure->notes = $validated['notes'];
            $newStructure->save();

            // Clone components
            foreach ($salaryStructure->structureComponents as $component) {
                $newComponent = $component->replicate();
                $newComponent->structure_id = $newStructure->id;
                $newComponent->save();
            }

            // Log the activity
            activity('salary_structure')
                ->performedOn($newStructure)
                ->withProperties([
                    'cloned_from' => $salaryStructure->id,
                    'employee_id' => $salaryStructure->employee_id,
                ])
                ->log('Salary structure cloned');

            return $newStructure;
        });

        return redirect()->route('employees.salary-structures.show', [$employee, $newStructure])
            ->with('success', __('hrms.salary_structure.cloned_successfully'));
    }

    /**
     * Terminate a salary structure (set end date to today).
     */
    public function terminate(Request $request, Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('update', $employee);
        Gate::authorize('delete', $salaryStructure);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        if (!$salaryStructure->is_active) {
            return redirect()->route('employees.salary-structures.show', [$employee, $salaryStructure])
                ->with('error', __('hrms.salary_structure.not_active'));
        }

        $validated = $request->validate([
            'termination_date' => 'required|date|before_or_equal:today',
            'termination_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $salaryStructure) {
            $salaryStructure->update([
                'effective_to' => Carbon::parse($validated['termination_date'])
            ]);

            // Log the activity
            activity('salary_structure')
                ->performedOn($salaryStructure)
                ->withProperties([
                    'termination_date' => $validated['termination_date'],
                    'termination_reason' => $validated['termination_reason'],
                ])
                ->log('Salary structure terminated');
        });

        return redirect()->route('employees.salary-structures.index', $employee)
            ->with('success', __('hrms.salary_structure.terminated_successfully'));
    }

    /**
     * Calculate and preview salary for a structure.
     */
    public function calculate(Employee $employee, SalaryStructure $salaryStructure)
    {
        Gate::authorize('view', $employee);

        // Ensure structure belongs to employee
        if ($salaryStructure->employee_id !== $employee->id) {
            abort(404);
        }

        $salaryStructure->load(['structureComponents.component']);

        $calculations = [
            'gross_salary' => $salaryStructure->calculateGrossSalary(),
            'total_deductions' => $salaryStructure->calculateTotalDeductions(),
            'net_salary' => $salaryStructure->calculateNetSalary(),
            'components' => []
        ];

        foreach ($salaryStructure->structureComponents as $structureComponent) {
            $component = $structureComponent->component;

            $calculations['components'][] = [
                'component' => $component,
                'structure_component' => $structureComponent,
                'calculated_value' => $structureComponent->calculated_value,
                'display_value' => $structureComponent->display_value,
                'can_view' => $component->canViewForUser(auth()->user()),
            ];
        }

        return response()->json($calculations);
    }
}
