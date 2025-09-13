<?php

namespace App\Http\Controllers;

use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditTrailController extends Controller
{
    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Display audit trail dashboard.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Activity::class);

        $days = $request->get('days', 30);
        $logType = $request->get('log_type', 'all');
        $userId = $request->get('user_id');

        $query = Activity::with(['causer', 'subject']);

        // Filter by date range
        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        // Filter by log type
        if ($logType !== 'all') {
            $query->where('log_name', $logType);
        } else {
            $query->whereIn('log_name', ['contract', 'salary_structure', 'employee_critical']);
        }

        // Filter by user
        if ($userId) {
            $query->where('causer_id', $userId);
        }

        $activities = $query->latest()
            ->paginate(25)
            ->appends($request->query());

        // Get statistics
        $contractStats = $this->auditTrailService->getContractAuditStats();
        $salaryStats = $this->auditTrailService->getSalaryAuditStats();

        return view('audit-trail.index', compact(
            'activities',
            'contractStats',
            'salaryStats',
            'days',
            'logType',
            'userId'
        ));
    }

    /**
     * Show audit trail for specific model.
     */
    public function show(Request $request, $subjectType, $subjectId)
    {
        $this->authorize('view', Activity::class);

        $modelClass = 'App\\Models\\' . ucfirst($subjectType);

        if (!class_exists($modelClass)) {
            abort(404, 'Invalid subject type');
        }

        $subject = $modelClass::findOrFail($subjectId);
        $activities = $this->auditTrailService->getAuditTrailForModel($subject);

        return view('audit-trail.show', compact('subject', 'activities', 'subjectType'));
    }

    /**
     * Export audit trail data.
     */
    public function export(Request $request)
    {
        $this->authorize('export', Activity::class);

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $logNames = $request->get('log_names', ['contract', 'salary_structure', 'employee_critical']);

        $data = $this->auditTrailService->exportAuditTrail($startDate, $endDate, $logNames);

        $filename = 'audit_trail_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Headers
            fputcsv($handle, [
                'Timestamp',
                'User',
                'Action',
                'Subject Type',
                'Subject ID',
                'Changes',
                'Log Type'
            ]);

            // Data rows
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Get audit trail statistics API.
     */
    public function statistics()
    {
        $this->authorize('viewAny', Activity::class);

        $contractStats = $this->auditTrailService->getContractAuditStats();
        $salaryStats = $this->auditTrailService->getSalaryAuditStats();

        return response()->json([
            'contracts' => $contractStats,
            'salary_structures' => $salaryStats,
            'recent_activity' => $this->auditTrailService->getRecentAuditTrail(7, 10)
        ]);
    }
}