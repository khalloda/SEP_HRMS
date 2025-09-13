<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractExpiryNotification;
use App\Models\Employee;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with comprehensive analytics and alerts.
     */
    public function index()
    {
        $user = auth()->user();

        // Get comprehensive dashboard analytics
        $analytics = $this->dashboardService->getDashboardAnalytics($user);

        // Legacy compatibility - keep existing alerts structure
        $expiryAlerts = [];
        if (Gate::allows('viewAny', Contract::class)) {
            $contractAnalytics = $analytics['contract_analytics'];
            $expiryAlerts = [
                'urgent' => $contractAnalytics['expiry_alerts']['urgent']->take(5),
                'critical' => $contractAnalytics['expiry_alerts']['critical']->take(5),
                'soon' => $contractAnalytics['expiry_alerts']['soon']->take(5),
                'expired' => collect(), // We'll show this separately
            ];
        }

        // Get recent notifications (legacy)
        $recentNotifications = [];
        if ($user->hasRole(['HR Admin Manager', 'Accounting Manager'])) {
            $recentNotifications = ContractExpiryNotification::getRecentNotifications(10);
        }

        // Get failed notifications for admins (legacy)
        $failedNotifications = [];
        if ($user->hasRole(['HR Admin Manager', 'IT Admin'])) {
            $failedNotifications = ContractExpiryNotification::getFailedNotifications();
        }

        return view('dashboard', compact(
            'analytics',
            'expiryAlerts',
            'recentNotifications',
            'failedNotifications'
        ));
    }

    /**
     * Get contract expiry alerts for AJAX requests.
     */
    public function getExpiryAlerts()
    {
        Gate::authorize('viewAny', Contract::class);

        $contractsRequiringAttention = Contract::getContractsRequiringAttention();
        
        $alerts = [
            'urgent' => $contractsRequiringAttention['expiring_urgently']->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'employee_name' => $contract->employee->display_name,
                    'employee_code' => $contract->employee->code,
                    'days_until_expiry' => $contract->days_until_expiry,
                    'end_date' => $contract->end_date->format('Y-m-d'),
                    'type' => $contract->type_name,
                    'urgency' => 'urgent',
                    'url' => route('contracts.show', $contract),
                ];
            }),
            'critical' => $contractsRequiringAttention['expiring_critically']->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'employee_name' => $contract->employee->display_name,
                    'employee_code' => $contract->employee->code,
                    'days_until_expiry' => $contract->days_until_expiry,
                    'end_date' => $contract->end_date->format('Y-m-d'),
                    'type' => $contract->type_name,
                    'urgency' => 'critical',
                    'url' => route('contracts.show', $contract),
                ];
            }),
            'soon' => $contractsRequiringAttention['expiring_soon']->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'employee_name' => $contract->employee->display_name,
                    'employee_code' => $contract->employee->code,
                    'days_until_expiry' => $contract->days_until_expiry,
                    'end_date' => $contract->end_date->format('Y-m-d'),
                    'type' => $contract->type_name,
                    'urgency' => 'soon',
                    'url' => route('contracts.show', $contract),
                ];
            }),
        ];

        return response()->json([
            'alerts' => $alerts,
            'counts' => [
                'urgent' => $contractsRequiringAttention['expiring_urgently']->count(),
                'critical' => $contractsRequiringAttention['expiring_critically']->count(),
                'soon' => $contractsRequiringAttention['expiring_soon']->count(),
            ]
        ]);
    }

    /**
     * Dismiss a notification (mark as read).
     */
    public function dismissNotification(ContractExpiryNotification $notification)
    {
        Gate::authorize('view', $notification);

        // For now, we'll just add a dismissed_at timestamp if the column exists
        // This would require a migration to add the column
        
        return response()->json(['success' => true]);
    }

    /**
     * Get comprehensive analytics data for AJAX requests.
     */
    public function getAnalytics(Request $request)
    {
        $user = auth()->user();
        $analytics = $this->dashboardService->getDashboardAnalytics($user);

        // Return specific section if requested
        $section = $request->get('section');
        if ($section && isset($analytics[$section])) {
            return response()->json($analytics[$section]);
        }

        return response()->json($analytics);
    }

    /**
     * Get employee statistics for charts.
     */
    public function getEmployeeStats()
    {
        $user = auth()->user();
        $stats = $this->dashboardService->getEmployeeStatistics($user);

        return response()->json($stats);
    }

    /**
     * Get payroll insights for authorized users.
     */
    public function getPayrollInsights()
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager', 'HR_Coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $insights = $this->dashboardService->getPayrollInsights($user);

        return response()->json($insights);
    }

    /**
     * Get contract analytics.
     */
    public function getContractAnalytics()
    {
        Gate::authorize('viewAny', Contract::class);

        $user = auth()->user();
        $analytics = $this->dashboardService->getContractAnalytics($user);

        return response()->json($analytics);
    }

    /**
     * Get critical alerts.
     */
    public function getCriticalAlerts()
    {
        $user = auth()->user();
        $alerts = $this->dashboardService->getCriticalAlerts($user);

        return response()->json($alerts);
    }

    /**
     * Get charts data for dashboard widgets.
     */
    public function getChartsData(Request $request)
    {
        $user = auth()->user();
        $charts = $this->dashboardService->getChartsData($user);

        // Return specific chart if requested
        $chart = $request->get('chart');
        if ($chart && isset($charts[$chart])) {
            return response()->json($charts[$chart]);
        }

        return response()->json($charts);
    }

    /**
     * Get recent activities.
     */
    public function getRecentActivities(Request $request)
    {
        $user = auth()->user();
        $activities = $this->dashboardService->getRecentActivities($user);

        $limit = $request->get('limit', 20);
        $activities = array_slice($activities, 0, $limit);

        return response()->json($activities);
    }

    /**
     * Clear dashboard cache.
     */
    public function clearCache()
    {
        $user = auth()->user();
        $this->dashboardService->clearUserCache($user->id);

        return response()->json(['success' => true, 'message' => 'Cache cleared successfully']);
    }
}
