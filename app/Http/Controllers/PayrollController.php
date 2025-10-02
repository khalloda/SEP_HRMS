<?php

namespace App\Http\Controllers;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Employee;
use App\Services\PayrollCalculationService;
use App\Exports\ArrayExport;
use App\Jobs\ProcessPayrollRun;
use App\Reports\Adapters\PayrollSummaryReport;
use App\Support\CorrelationIdManager;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayrollController extends Controller
{
    protected PayrollCalculationService $calculationService;
    protected CorrelationIdManager $correlationIds;

    public function __construct(PayrollCalculationService $calculationService, CorrelationIdManager $correlationIds)
    {
        $this->calculationService = $calculationService;
        $this->correlationIds = $correlationIds;
    }

    /**
     * Display a listing of payroll runs.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', PayrollRun::class);

        $query = PayrollRun::query()->with(['creator', 'payslips']);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        if ($request->filled('year')) {
            $year = $request->get('year');
            $query->whereYear('pay_period_end', $year);
        }

        if ($request->filled('month')) {
            $month = $request->get('month');
            $query->whereMonth('pay_period_end', $month);
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $payrollRuns = $query->recent()->paginate(15)->appends($request->query());

        // Filter options
        $statusOptions = PayrollRun::STATUSES;
        $yearOptions = range(date('Y') - 2, date('Y') + 1);
        $monthOptions = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('payroll.index', compact(
            'payrollRuns', 'statusOptions', 'yearOptions', 'monthOptions'
        ));
    }

    /**
     * Show the form for creating a new payroll run.
     */
    public function create()
    {
        Gate::authorize('create', PayrollRun::class);

        // Suggest next pay period based on last payroll run
        $lastRun = PayrollRun::latest('pay_period_end')->first();

        $suggestedStart = $lastRun
            ? $lastRun->pay_period_end->addDay()
            : now()->startOfMonth();

        $suggestedEnd = $suggestedStart->copy()->endOfMonth();
        $suggestedPayDate = $suggestedEnd->copy()->addDays(5);

        return view('payroll.create', compact(
            'suggestedStart', 'suggestedEnd', 'suggestedPayDate'
        ));
    }

    /**
     * Store a newly created payroll run.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', PayrollRun::class);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'pay_date' => 'required|date|after_or_equal:pay_period_end',
            'currency' => 'required|string|in:USD,EUR,EGP',
            'approval_required' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $periodStart = Carbon::parse($validated['pay_period_start']);
        $periodEnd = Carbon::parse($validated['pay_period_end']);

        // Generate title if not provided
        if (empty($validated['title'])) {
            $validated['title'] = PayrollRun::generateTitle($periodStart, $periodEnd);
        }

        // Check for overlapping payroll runs
        $overlapping = PayrollRun::where(function ($query) use ($periodStart, $periodEnd) {
            $query->where(function ($q) use ($periodStart, $periodEnd) {
                $q->where('pay_period_start', '<=', $periodEnd)
                  ->where('pay_period_end', '>=', $periodStart);
            });
        })->whereNotIn('status', [PayrollRun::STATUS_CANCELLED])->exists();

        if ($overlapping) {
            return back()
                ->withInput()
                ->withErrors(['pay_period_start' => __('hrms.payroll.overlapping_period_error')]);
        }

        $payrollRun = DB::transaction(function () use ($validated) {
            $payrollRun = PayrollRun::create([
                ...$validated,
                'status' => PayrollRun::STATUS_DRAFT,
                'created_by' => auth()->id(),
                'currency' => $validated['currency'],
                'approval_required' => $validated['approval_required'] ?? false,
            ]);

            activity('payroll_run')
                ->performedOn($payrollRun)
                ->log('Payroll run created');

            return $payrollRun;
        });

        return redirect()->route('payroll.show', $payrollRun)
            ->with('success', __('hrms.payroll.created_successfully'));
    }

    /**
     * Display the specified payroll run.
     */
    public function show(PayrollRun $payrollRun)
    {
        Gate::authorize('view', $payrollRun);

        $payrollRun->load([
            'creator', 'locker', 'poster', 'approver',
            'payslips.employee.department',
            'payslips.payslipLines'
        ]);

        // Get calculation summary if calculated
        $summary = null;
        if ($payrollRun->isCalculated() || $payrollRun->isLocked() || $payrollRun->isPosted()) {
            $summary = $this->calculationService->getCalculationSummary($payrollRun);
        }

        // Get validation issues if in draft
        $validationIssues = [];
        if ($payrollRun->isDraft()) {
            $validationIssues = $this->calculationService->validatePayrollRun($payrollRun);
        }

        return view('payroll.show', compact('payrollRun', 'summary', 'validationIssues'));
    }

    /**
     * Show the form for editing the specified payroll run.
     */
    public function edit(PayrollRun $payrollRun)
    {
        Gate::authorize('update', $payrollRun);

        if (!$payrollRun->canBeEdited()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_edit_locked'));
        }

        return view('payroll.edit', compact('payrollRun'));
    }

    /**
     * Update the specified payroll run.
     */
    public function update(Request $request, PayrollRun $payrollRun)
    {
        Gate::authorize('update', $payrollRun);

        if (!$payrollRun->canBeEdited()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_edit_locked'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'pay_date' => 'required|date|after_or_equal:pay_period_end',
            'currency' => 'required|string|in:USD,EUR,EGP',
            'approval_required' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($payrollRun, $validated) {
            $payrollRun->update($validated);

            activity('payroll_run')
                ->performedOn($payrollRun)
                ->log('Payroll run updated');
        });

        return redirect()->route('payroll.show', $payrollRun)
            ->with('success', __('hrms.payroll.updated_successfully'));
    }

    /**
     * Calculate payroll for all eligible employees.
     */
    public function calculate(PayrollRun $payrollRun)
    {
        Gate::authorize('calculate', $payrollRun);

        $correlationId = $this->resolveCalculationCorrelationId();

        Log::withContext([
            'correlation_id' => $correlationId,
            'payroll_run_id' => $payrollRun->id,
        ]);

        if (!$payrollRun->canBeCalculated()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_calculate'))
                ->with('calculation_reference', $correlationId);
        }

        // Validate before calculation
        $validationIssues = $this->calculationService->validatePayrollRun($payrollRun);
        if (!empty($validationIssues)) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.validation_failed'))
                ->with('calculation_reference', $correlationId)
                ->with('validation_issues', $validationIssues);
        }


        if (config('payroll.queue_enabled')) {
            ProcessPayrollRun::dispatch($payrollRun->id, $correlationId);

            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', __('Payroll run queued for processing.'))
                ->with('calculation_reference', $correlationId)
                ->with('calculation_job', [
                    'correlation_id' => $correlationId,
                    'payroll_run_id' => $payrollRun->id,
                ]);
        }

        $results = $this->calculationService->calculatePayrollRun($payrollRun, [
            'correlation_id' => $correlationId,
        ]);

        if ($results['success']) {
            $message = __('hrms.payroll.calculated_successfully', [
                'employees' => $results['employees_processed'],
                'payslips' => $results['payslips_created']
            ]);

            if (!empty($results['errors'])) {
                $message .= ' ' . __('hrms.payroll.with_warnings');
            }

            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', $message)
                ->with('calculation_reference', $correlationId)
                ->with('calculation_results', $results);
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.calculation_failed'))
                ->with('calculation_reference', $correlationId)
                ->with('calculation_errors', $results['errors']);
        }
    }

    protected function resolveCalculationCorrelationId(): string
    {
        return $this->correlationIds->ensure();
    }

    /**
     * Lock the payroll run.
     */
    public function lock(PayrollRun $payrollRun)
    {
        Gate::authorize('lock', $payrollRun);

        if (!$payrollRun->canBeLocked()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_lock'));
        }

        $locked = $payrollRun->lock(auth()->user());

        if ($locked) {
            $message = $payrollRun->approval_required
                ? __('hrms.payroll.submitted_for_approval')
                : __('hrms.payroll.locked_successfully');

            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', $message);
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.lock_failed'));
        }
    }

    /**
     * Unlock the payroll run.
     */
    public function unlock(PayrollRun $payrollRun)
    {
        Gate::authorize('unlock', $payrollRun);

        if (!$payrollRun->canBeUnlocked()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_unlock'));
        }

        $unlocked = $payrollRun->unlock(auth()->user());

        if ($unlocked) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', __('hrms.payroll.unlocked_successfully'));
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.unlock_failed'));
        }
    }

    /**
     * Approve the payroll run.
     */
    public function approve(PayrollRun $payrollRun)
    {
        Gate::authorize('approve', $payrollRun);

        if (!$payrollRun->isPendingApproval()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.not_pending_approval'));
        }

        $approved = $payrollRun->approve(auth()->user());

        if ($approved) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', __('hrms.payroll.approved_successfully'));
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.approval_failed'));
        }
    }

    /**
     * Reject the payroll run.
     */
    public function reject(Request $request, PayrollRun $payrollRun)
    {
        Gate::authorize('approve', $payrollRun);

        if (!$payrollRun->isPendingApproval()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.not_pending_approval'));
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $rejected = $payrollRun->reject(auth()->user(), $validated['rejection_reason']);

        if ($rejected) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', __('hrms.payroll.rejected_successfully'));
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.rejection_failed'));
        }
    }

    /**
     * Post the payroll run.
     */
    public function post(PayrollRun $payrollRun)
    {
        Gate::authorize('post', $payrollRun);

        if (!$payrollRun->canBePosted()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_post'));
        }

        $posted = $payrollRun->post(auth()->user());

        if ($posted) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('success', __('hrms.payroll.posted_successfully'));
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.post_failed'));
        }
    }

    /**
     * Cancel the payroll run.
     */
    public function cancel(Request $request, PayrollRun $payrollRun)
    {
        Gate::authorize('delete', $payrollRun);

        if (!$payrollRun->canBeCancelled()) {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cannot_cancel'));
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $cancelled = $payrollRun->cancel(auth()->user(), $validated['cancellation_reason']);

        if ($cancelled) {
            return redirect()->route('payroll.index')
                ->with('success', __('hrms.payroll.cancelled_successfully'));
        } else {
            return redirect()->route('payroll.show', $payrollRun)
                ->with('error', __('hrms.payroll.cancellation_failed'));
        }
    }

    /**
     * Display payslips for a payroll run.
     */
    public function payslips(Request $request, PayrollRun $payrollRun)
    {
        Gate::authorize('view', $payrollRun);

        $query = $payrollRun->payslips()
            ->with(['employee.department', 'employee.position']);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->get('department_id'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('employee_arabic_name', 'like', "%{$search}%");
            });
        }

        $payslips = $query->orderBy('employee_name')
            ->paginate(20)
            ->appends($request->query());

        // Get departments for filter
        $departments = \App\Models\Department::ordered()->get();

        return view('payroll.payslips', compact('payrollRun', 'payslips', 'departments'));
    }

    /**
     * Export payroll data.
     */
    public function export(Request $request, PayrollRun $payrollRun)
    {
        Gate::authorize('export', $payrollRun);

        $format = $request->get('format', 'excel');

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($request, $payrollRun);
            case 'csv':
                return $this->exportToCsv($request, $payrollRun);
            case 'excel':
            default:
                return $this->exportToExcel($request, $payrollRun);
        }
    }

    /**
     * Get payroll statistics.
     */
    public function statistics()
    {
        Gate::authorize('statistics', PayrollRun::class);

        $currentYear = date('Y');
        $currentMonth = date('m');

        $stats = [
            'total_runs' => PayrollRun::count(),
            'draft_runs' => PayrollRun::draft()->count(),
            'pending_approval' => PayrollRun::pendingApproval()->count(),
            'posted_this_year' => PayrollRun::posted()
                ->whereYear('pay_date', $currentYear)
                ->count(),
            'total_payslips_this_year' => Payslip::whereYear('pay_date', $currentYear)->count(),
            'monthly_totals' => PayrollRun::posted()
                ->whereYear('pay_date', $currentYear)
                ->selectRaw('MONTH(pay_date) as month, SUM(total_gross) as gross, SUM(total_net) as net')
                ->groupBy('month')
                ->get()
                ->pluck(['month', 'gross', 'net']),
        ];

        return response()->json($stats);
    }

    private function exportToExcel(Request $request, PayrollRun $payrollRun)
    {
        $periodStart = $payrollRun->pay_period_start ?? $payrollRun->pay_date ?? now();
        $month = $periodStart instanceof \Carbon\CarbonInterface
            ? $periodStart->format('Y-m')
            : (string) $periodStart;

        $filters = ['month' => $month];

        if ($request->filled('department_id')) {
            $filters['department_id'] = (int) $request->get('department_id');
        }

        $headings = PayrollSummaryReport::headings();
        $rows = PayrollSummaryReport::rows($filters)
            ->map(static fn (array $row): array => array_values($row))
            ->all();

        $filename = PayrollSummaryReport::filename($filters, 'excel');

        return Excel::download(new ArrayExport($headings, $rows), $filename);
    }

    private function exportToPdf(Request $request, PayrollRun $payrollRun)
    {
        abort(501, __('Export format not yet supported.'));
    }

    private function exportToCsv(Request $request, PayrollRun $payrollRun)
    {
        abort(501, __('Export format not yet supported.'));
    }
}