<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractExpiryNotification;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and alerts.
     */
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
        ];

        // Get contract expiry alerts
        $expiryAlerts = [];
        if (Gate::allows('viewAny', Contract::class)) {
            $contractsRequiringAttention = Contract::getContractsRequiringAttention();
            
            // Organize alerts by urgency
            $expiryAlerts = [
                'urgent' => $contractsRequiringAttention['expiring_urgently']->take(5),
                'critical' => $contractsRequiringAttention['expiring_critically']->take(5),
                'soon' => $contractsRequiringAttention['expiring_soon']->take(5),
                'expired' => $contractsRequiringAttention['expired']->take(5),
            ];
        }

        // Get recent notifications
        $recentNotifications = [];
        if (auth()->user()->hasRole(['HR Admin Manager', 'Accounting Manager'])) {
            $recentNotifications = ContractExpiryNotification::getRecentNotifications(10);
        }

        // Get failed notifications for admins
        $failedNotifications = [];
        if (auth()->user()->hasRole(['HR Admin Manager', 'IT Admin'])) {
            $failedNotifications = ContractExpiryNotification::getFailedNotifications();
        }

        return view('dashboard', compact(
            'stats',
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
}
