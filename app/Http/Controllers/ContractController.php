<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Contract::class);

        $query = Contract::withEmployee()->latest('start_date');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->byType($request->get('type'));
        }

        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }

        if ($request->filled('expiry_filter')) {
            $filter = $request->get('expiry_filter');
            switch ($filter) {
                case 'urgent':
                    $query->expiringUrgently();
                    break;
                case 'critical':
                    $query->expiringCritically();
                    break;
                case 'soon':
                    $query->expiringSoon();
                    break;
                case 'expired':
                    $query->expired();
                    break;
            }
        }

        // Date range filters
        if ($request->filled('start_date_from')) {
            $query->where('start_date', '>=', $request->get('start_date_from'));
        }

        if ($request->filled('start_date_to')) {
            $query->where('start_date', '<=', $request->get('start_date_to'));
        }

        if ($request->filled('end_date_from')) {
            $query->where('end_date', '>=', $request->get('end_date_from'));
        }

        if ($request->filled('end_date_to')) {
            $query->where('end_date', '<=', $request->get('end_date_to'));
        }

        $perPage = $request->get('per_page', 15);
        $contracts = $query->paginate($perPage)->appends($request->query());

        // Get filter options
        $contractTypes = collect(Contract::TYPES)->map(function ($type, $key) {
            return [
                'value' => $key,
                'label' => __('hrms.contract_types.' . $key, $type)
            ];
        });

        $contractStatuses = collect(Contract::STATUSES)->map(function ($status, $key) {
            return [
                'value' => $key,
                'label' => __('hrms.contract_status.' . $key, $status)
            ];
        });

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        // Get statistics
        $stats = [
            'total' => Contract::count(),
            'active' => Contract::active()->count(),
            'expiring_soon' => Contract::expiringSoon()->count(),
            'expired' => Contract::expired()->count(),
            'by_type' => Contract::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->type => $item->total];
                }),
        ];

        return view('contracts.index', compact(
            'contracts', 'contractTypes', 'contractStatuses', 'employees', 'stats'
        ));
    }

    /**
     * Show the form for creating a new contract.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Contract::class);

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        $contractTypes = Contract::TYPES;

        // Pre-select employee if provided
        $selectedEmployee = null;
        if ($request->filled('employee_id')) {
            $selectedEmployee = Employee::find($request->get('employee_id'));
        }

        return view('contracts.create', compact(
            'employees', 'contractTypes', 'selectedEmployee'
        ));
    }

    /**
     * Store a newly created contract.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Contract::class);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|in:' . implode(',', array_keys(Contract::TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'terms_json' => 'nullable|array',
            'status' => 'required|in:' . implode(',', array_keys(Contract::STATUSES)),
        ]);

        // Validate end_date requirement based on contract type
        $contract = new Contract(['type' => $validated['type']]);
        if ($contract->requiresEndDate() && empty($validated['end_date'])) {
            return back()->withErrors([
                'end_date' => __('End date is required for this contract type.')
            ])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $contract = Contract::create($validated);

            // Log the creation
            activity('contract')
                ->performedOn($contract)
                ->log('Contract created');
        });

        return redirect()->route('contracts.index')
            ->with('success', __('hrms.contract.created_successfully'));
    }

    /**
     * Display the specified contract.
     */
    public function show(Contract $contract)
    {
        Gate::authorize('view', $contract);

        $contract->load(['employee.department', 'employee.position', 'documents']);

        // Get contract statistics
        $stats = $contract->getStats();

        // Get recent activity
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', Contract::class)
            ->where('subject_id', $contract->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('contracts.show', compact('contract', 'stats', 'activities'));
    }

    /**
     * Show the form for editing the specified contract.
     */
    public function edit(Contract $contract)
    {
        Gate::authorize('update', $contract);

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        $contractTypes = Contract::TYPES;
        $contractStatuses = Contract::STATUSES;

        return view('contracts.edit', compact(
            'contract', 'employees', 'contractTypes', 'contractStatuses'
        ));
    }

    /**
     * Update the specified contract.
     */
    public function update(Request $request, Contract $contract)
    {
        Gate::authorize('update', $contract);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|in:' . implode(',', array_keys(Contract::TYPES)),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'terms_json' => 'nullable|array',
            'status' => 'required|in:' . implode(',', array_keys(Contract::STATUSES)),
        ]);

        // Validate end_date requirement based on contract type
        $tempContract = new Contract(['type' => $validated['type']]);
        if ($tempContract->requiresEndDate() && empty($validated['end_date'])) {
            return back()->withErrors([
                'end_date' => __('End date is required for this contract type.')
            ])->withInput();
        }

        DB::transaction(function () use ($contract, $validated) {
            $contract->update($validated);

            // Log the update
            activity('contract')
                ->performedOn($contract)
                ->log('Contract updated');
        });

        return redirect()->route('contracts.show', $contract)
            ->with('success', __('hrms.contract.updated_successfully'));
    }

    /**
     * Remove the specified contract.
     */
    public function destroy(Contract $contract)
    {
        Gate::authorize('delete', $contract);

        // Check if contract has dependencies
        $hasDocuments = $contract->documents()->exists();

        if ($hasDocuments) {
            return redirect()->route('contracts.show', $contract)
                ->with('error', __('hrms.contract.cannot_delete_has_dependencies'));
        }

        DB::transaction(function () use ($contract) {
            // Log the deletion
            activity('contract')
                ->performedOn($contract)
                ->log('Contract deleted');

            $contract->delete();
        });

        return redirect()->route('contracts.index')
            ->with('success', __('hrms.contract.deleted_successfully'));
    }

    /**
     * Renew the specified contract.
     */
    public function renew(Request $request, Contract $contract)
    {
        Gate::authorize('update', $contract);

        if (!$contract->isRenewable() || !$contract->isActive()) {
            return redirect()->route('contracts.show', $contract)
                ->with('error', __('hrms.contract.cannot_renew'));
        }

        $validated = $request->validate([
            'type' => 'nullable|string|in:' . implode(',', array_keys(Contract::TYPES)),
            'start_date' => 'nullable|date|after:' . $contract->end_date,
            'end_date' => 'nullable|date|after:start_date',
            'terms_json' => 'nullable|array',
        ]);

        DB::transaction(function () use ($contract, $validated) {
            $newContract = $contract->renew($validated);

            if ($newContract) {
                // Mark old contract as renewed
                $contract->update(['status' => 'terminated']);

                // Log the renewal
                activity('contract')
                    ->performedOn($contract)
                    ->withProperties(['new_contract_id' => $newContract->id])
                    ->log('Contract renewed');
            }
        });

        return redirect()->route('contracts.index')
            ->with('success', __('hrms.contract.renewed_successfully'));
    }

    /**
     * Terminate the specified contract.
     */
    public function terminate(Request $request, Contract $contract)
    {
        Gate::authorize('update', $contract);

        $validated = $request->validate([
            'termination_reason' => 'required|string|max:500',
            'termination_date' => 'required|date|before_or_equal:today',
        ]);

        DB::transaction(function () use ($contract, $validated) {
            $contract->terminate($validated['termination_reason']);

            // Log the termination with additional details
            activity('contract')
                ->performedOn($contract)
                ->withProperties($validated)
                ->log('Contract terminated');
        });

        return redirect()->route('contracts.show', $contract)
            ->with('success', __('hrms.contract.terminated_successfully'));
    }

    /**
     * Get contracts requiring attention (AJAX).
     */
    public function getContractsRequiringAttention()
    {
        Gate::authorize('viewAny', Contract::class);

        $contracts = Contract::getContractsRequiringAttention();

        return response()->json($contracts);
    }

    /**
     * Export contracts to various formats.
     */
    public function export(Request $request)
    {
        Gate::authorize('export', Contract::class);

        // Implementation would go here for PDF/Excel export
        return response()->json(['message' => 'Export functionality coming soon']);
    }

    /**
     * Bulk operations on contracts.
     */
    public function bulkOperation(Request $request)
    {
        Gate::authorize('bulkUpdate', Contract::class);

        $validated = $request->validate([
            'operation' => 'required|in:expire,terminate,activate',
            'contract_ids' => 'required|array',
            'contract_ids.*' => 'exists:contracts,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $contracts = Contract::whereIn('id', $validated['contract_ids'])->get();
        $operation = $validated['operation'];
        $affectedCount = 0;

        DB::transaction(function () use ($contracts, $operation, $validated, &$affectedCount) {
            foreach ($contracts as $contract) {
                switch ($operation) {
                    case 'expire':
                        if ($contract->isActive()) {
                            $contract->update(['status' => 'expired']);
                            $affectedCount++;
                        }
                        break;
                    case 'terminate':
                        if ($contract->isActive()) {
                            $contract->terminate($validated['reason']);
                            $affectedCount++;
                        }
                        break;
                    case 'activate':
                        if (!$contract->isActive()) {
                            $contract->update(['status' => 'active']);
                            $affectedCount++;
                        }
                        break;
                }
            }

            // Log bulk operation
            activity('contract')
                ->withProperties([
                    'operation' => $operation,
                    'affected_count' => $affectedCount,
                    'reason' => $validated['reason'] ?? null,
                ])
                ->log("Bulk {$operation} operation performed on {$affectedCount} contracts");
        });

        return redirect()->route('contracts.index')
            ->with('success', __("Successfully performed {$operation} on {$affectedCount} contracts."));
    }
}