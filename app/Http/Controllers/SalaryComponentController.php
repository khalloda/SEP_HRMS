<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalaryComponentController extends Controller
{
    /**
     * Display a listing of salary components.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', SalaryComponent::class);

        $query = SalaryComponent::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        if ($request->filled('comp_type')) {
            $query->byType($request->get('comp_type'));
        }

        if ($request->filled('calc_mode')) {
            $query->where('calc_mode', $request->get('calc_mode'));
        }

        if ($request->filled('taxable')) {
            if ($request->get('taxable') === '1') {
                $query->taxable();
            } else {
                $query->nonTaxable();
            }
        }

        $components = $query->ordered()->paginate(15)->appends($request->query());

        // Get statistics
        $stats = [
            'total' => SalaryComponent::count(),
            'earnings' => SalaryComponent::earnings()->count(),
            'deductions' => SalaryComponent::deductions()->count(),
            'info_only' => SalaryComponent::infoOnly()->count(),
            'taxable' => SalaryComponent::taxable()->count(),
        ];

        return view('salary-components.index', compact('components', 'stats'));
    }

    /**
     * Show the form for creating a new salary component.
     */
    public function create()
    {
        Gate::authorize('create', SalaryComponent::class);

        $componentTypes = SalaryComponent::TYPES;
        $calcModes = SalaryComponent::CALC_MODES;
        $availableRoles = $this->getAvailableRoles();

        return view('salary-components.create', compact(
            'componentTypes', 'calcModes', 'availableRoles'
        ));
    }

    /**
     * Store a newly created salary component.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', SalaryComponent::class);

        $validated = $request->validate([
            'code' => 'required|string|max:40|unique:salary_components,code',
            'name_en' => 'required|string|max:120',
            'name_ar' => 'required|string|max:120',
            'comp_type' => 'required|in:earning,deduction,info',
            'calc_mode' => 'required|in:fixed,formula,variable_net_based',
            'taxable' => 'boolean',
            'visible_to_roles' => 'nullable|array',
            'visible_to_roles.*' => 'string',
            'priority_order' => 'required|integer|min:0|max:999',
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        DB::transaction(function () use ($validated) {
            $component = SalaryComponent::create($validated);

            // Log the creation
            activity('salary_component')
                ->performedOn($component)
                ->log('Salary component created');
        });

        return redirect()->route('salary-components.index')
            ->with('success', __('Salary component created successfully.'));
    }

    /**
     * Display the specified salary component.
     */
    public function show(SalaryComponent $salaryComponent)
    {
        Gate::authorize('view', $salaryComponent);

        // Get salary structures using this component
        $salaryStructures = $salaryComponent->salaryStructures()
            ->with('employee')
            ->latest()
            ->limit(10)
            ->get();

        // Get recent activity
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', \App\Models\SalaryComponent::class)
            ->where('subject_id', $salaryComponent->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('salary-components.show', compact(
            'salaryComponent', 'salaryStructures', 'activities'
        ));
    }

    /**
     * Show the form for editing the specified salary component.
     */
    public function edit(SalaryComponent $salaryComponent)
    {
        Gate::authorize('update', $salaryComponent);

        $componentTypes = SalaryComponent::TYPES;
        $calcModes = SalaryComponent::CALC_MODES;
        $availableRoles = $this->getAvailableRoles();

        return view('salary-components.edit', compact(
            'salaryComponent', 'componentTypes', 'calcModes', 'availableRoles'
        ));
    }

    /**
     * Update the specified salary component.
     */
    public function update(Request $request, SalaryComponent $salaryComponent)
    {
        Gate::authorize('update', $salaryComponent);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:40',
                Rule::unique('salary_components', 'code')->ignore($salaryComponent->id)
            ],
            'name_en' => 'required|string|max:120',
            'name_ar' => 'required|string|max:120',
            'comp_type' => 'required|in:earning,deduction,info',
            'calc_mode' => 'required|in:fixed,formula,variable_net_based',
            'taxable' => 'boolean',
            'visible_to_roles' => 'nullable|array',
            'visible_to_roles.*' => 'string',
            'priority_order' => 'required|integer|min:0|max:999',
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        DB::transaction(function () use ($salaryComponent, $validated) {
            $salaryComponent->update($validated);

            // Log the update
            activity('salary_component')
                ->performedOn($salaryComponent)
                ->log('Salary component updated');
        });

        return redirect()->route('salary-components.show', $salaryComponent)
            ->with('success', __('Salary component updated successfully.'));
    }

    /**
     * Remove the specified salary component.
     */
    public function destroy(SalaryComponent $salaryComponent)
    {
        Gate::authorize('delete', $salaryComponent);

        // Check if component is used in any salary structures
        if ($salaryComponent->salaryStructures()->exists()) {
            return redirect()->route('salary-components.show', $salaryComponent)
                ->with('error', __('Cannot delete salary component: it is used in salary structures.'));
        }

        DB::transaction(function () use ($salaryComponent) {
            // Log the deletion
            activity('salary_component')
                ->performedOn($salaryComponent)
                ->log('Salary component deleted');

            $salaryComponent->delete();
        });

        return redirect()->route('salary-components.index')
            ->with('success', __('Salary component deleted successfully.'));
    }

    /**
     * Seed predefined salary components.
     */
    public function seedPredefined()
    {
        Gate::authorize('create', SalaryComponent::class);

        $predefinedComponents = SalaryComponent::getPredefinedComponents();
        $createdCount = 0;

        DB::transaction(function () use ($predefinedComponents, &$createdCount) {
            foreach ($predefinedComponents as $componentData) {
                // Check if component already exists
                if (SalaryComponent::where('code', $componentData['code'])->exists()) {
                    continue;
                }

                SalaryComponent::create($componentData);
                $createdCount++;
            }

            // Log the seeding
            activity('salary_component')
                ->log("Seeded {$createdCount} predefined salary components");
        });

        return redirect()->route('salary-components.index')
            ->with('success', __("Successfully created {$createdCount} predefined salary components."));
    }

    /**
     * Get available roles for visibility restrictions.
     */
    private function getAvailableRoles(): array
    {
        return [
            'HR_Admin_Manager' => 'HR Admin Manager',
            'Accounting_Manager' => 'Accounting Manager',
            'HR_Coordinator' => 'HR Coordinator',
            'Accountant' => 'Accountant',
        ];
    }
}