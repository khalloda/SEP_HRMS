<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Contract;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Department;
use App\Models\Position;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get comprehensive dashboard analytics for the current user.
     */
    public function getDashboardAnalytics($user): array
    {
        $cacheKey = "dashboard_analytics_" . $user->id . "_" . app()->getLocale() . "_" . now()->format('Y-m-d-H');

        return Cache::remember($cacheKey, 3600, function () use ($user) {
            return [
                'employee_stats' => $this->getEmployeeStatistics($user),
                'contract_analytics' => $this->getContractAnalytics($user),
                'payroll_insights' => $this->getPayrollInsights($user),
                'recent_activities' => $this->getRecentActivities($user),
                'alerts' => $this->getCriticalAlerts($user),
                'charts_data' => $this->getChartsData($user),
                // Role-specific customizations
                'role_config' => $this->getRoleBasedDashboardConfig($user),
                'personalized_widgets' => $this->getPersonalizedWidgets($user),
                'quick_actions' => $this->getQuickActionsForRole($user),
            ];
        });
    }

    /**
     * Get role-based dashboard configuration.
     */
    public function getRoleBasedDashboardConfig($user): array
    {
        $config = [
            'visible_sections' => [],
            'priority_widgets' => [],
            'restricted_data' => [],
            'custom_title' => '',
            'theme_color' => 'primary',
        ];

        if ($user->hasRole('HR_Admin_Manager')) {
            $config = [
                'visible_sections' => ['employees', 'contracts', 'payroll', 'documents', 'compliance', 'reports'],
                'priority_widgets' => ['employee_stats', 'contract_expiry', 'payroll_summary', 'compliance_alerts'],
                'restricted_data' => [],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'success',
            ];
        } elseif ($user->hasRole('Accounting_Manager')) {
            $config = [
                'visible_sections' => ['payroll', 'contracts', 'employees', 'reports'],
                'priority_widgets' => ['payroll_summary', 'salary_analysis', 'contract_costs', 'financial_reports'],
                'restricted_data' => [],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'warning',
            ];
        } elseif ($user->hasRole('HR_Coordinator')) {
            $config = [
                'visible_sections' => ['employees', 'contracts', 'documents', 'reports'],
                'priority_widgets' => ['employee_stats', 'document_expiry', 'new_hires', 'contract_renewals'],
                'restricted_data' => ['net_salary', 'gross_salary'],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'info',
            ];
        } elseif ($user->hasRole('Accountant')) {
            $config = [
                'visible_sections' => ['payroll', 'reports'],
                'priority_widgets' => ['payroll_processing', 'expense_tracking'],
                'restricted_data' => ['net_salary', 'gross_salary'],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'secondary',
            ];
        } elseif ($user->hasRole('Employee')) {
            $config = [
                'visible_sections' => ['profile', 'documents', 'payslips', 'attendance'],
                'priority_widgets' => ['profile_completion', 'recent_payslips', 'document_expiry'],
                'restricted_data' => ['all_employee_data', 'other_salaries'],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'primary',
            ];
        } elseif ($user->hasRole('IT_Admin')) {
            $config = [
                'visible_sections' => ['system', 'audit', 'reports'],
                'priority_widgets' => ['system_health', 'audit_logs', 'user_activities'],
                'restricted_data' => ['salary_details'],
                'custom_title' => __('hrms.dashboard.title'),
                'theme_color' => 'dark',
            ];
        }

        return $config;
    }

    /**
     * Get personalized widgets based on user role and preferences.
     */
    public function getPersonalizedWidgets($user): array
    {
        $widgets = [];
        $roleConfig = $this->getRoleBasedDashboardConfig($user);

        foreach ($roleConfig['priority_widgets'] as $widgetType) {
            $widgets[] = $this->generateWidget($widgetType, $user);
        }

        return $widgets;
    }

    /**
     * Generate a specific widget based on type.
     */
    private function generateWidget($type, $user): array
    {
        switch ($type) {
            case 'employee_stats':
                return [
                    'type' => 'employee_stats',
                    'title' => __('hrms.dashboard.employee_analytics'),
                    'icon' => 'fas fa-users',
                    'color' => 'primary',
                    'data' => $this->getEmployeeStatsWidget($user),
                    'size' => 'col-lg-6',
                ];

            case 'contract_expiry':
                return [
                    'type' => 'contract_expiry',
                    'title' => __('hrms.notifications.expiry_alerts'),
                    'icon' => 'fas fa-exclamation-triangle',
                    'color' => 'warning',
                    'data' => $this->getContractExpiryWidget($user),
                    'size' => 'col-lg-6',
                ];

            case 'payroll_summary':
                return [
                    'type' => 'payroll_summary',
                    'title' => __('common.payroll_summary'),
                    'icon' => 'fas fa-file-invoice-dollar',
                    'color' => 'success',
                    'data' => $this->getPayrollSummaryWidget($user),
                    'size' => 'col-lg-8',
                ];

            case 'document_expiry':
                return [
                    'type' => 'document_expiry',
                    'title' => __('hrms.dashboard.documents_expiring'),
                    'icon' => 'fas fa-folder-open',
                    'color' => 'info',
                    'data' => $this->getDocumentExpiryWidget($user),
                    'size' => 'col-lg-4',
                ];

            case 'profile_completion':
                return [
                    'type' => 'profile_completion',
                    'title' => __('common.my_profile'),
                    'icon' => 'fas fa-user-check',
                    'color' => 'info',
                    'data' => $this->getProfileCompletionWidget($user),
                    'size' => 'col-lg-4',
                ];

            case 'system_health':
                return [
                    'type' => 'system_health',
                    'title' => __('common.system_information'),
                    'icon' => 'fas fa-heartbeat',
                    'color' => 'success',
                    'data' => $this->getSystemHealthWidget($user),
                    'size' => 'col-lg-6',
                ];

            case 'audit_logs':
                return [
                    'type' => 'audit_logs',
                    'title' => __('common.audit_trail'),
                    'icon' => 'fas fa-history',
                    'color' => 'secondary',
                    'data' => $this->getAuditLogsWidget($user),
                    'size' => 'col-lg-6',
                ];

            default:
                return [
                    'type' => 'default',
                    'title' => __('common.information'),
                    'icon' => 'fas fa-info-circle',
                    'color' => 'light',
                    'data' => ['message' => __('hrms.dashboard.widget_not_configured')],
                    'size' => 'col-lg-4',
                ];
        }
    }

    /**
     * Get quick actions based on user role.
     */
    public function getQuickActionsForRole($user): array
    {
        if ($user->hasRole('HR_Admin_Manager')) {
            return [
                ['title' => __('hrms.add_employee'), 'url' => route('employees.create'), 'icon' => 'fas fa-user-plus', 'color' => 'primary'],
                ['title' => __('common.new_contract'), 'url' => route('contracts.create'), 'icon' => 'fas fa-file-circle-plus', 'color' => 'success'],
                ['title' => __('common.upload_documents'), 'url' => route('documents.create'), 'icon' => 'fas fa-upload', 'color' => 'info'],
                ['title' => (\Illuminate\Support\Facades\Lang::has('reports.title') ? __('reports.title') : (\Illuminate\Support\Facades\Lang::has('common.reports_quick') ? __('common.reports_quick') : (\Illuminate\Support\Facades\Lang::has('common.reports_title') ? __('common.reports_title') : __('common.reports')))), 'url' => route('reports.index'), 'icon' => 'fas fa-chart-bar', 'color' => 'warning'],
            ];
        } elseif ($user->hasRole('Accounting_Manager')) {
            return [
                ['title' => __('common.payroll_summary'), 'url' => route('reports.payroll.summary'), 'icon' => 'fas fa-calculator', 'color' => 'success'],
                ['title' => __('hrms.salary', [], app()->getLocale()), 'url' => route('salary-components.index'), 'icon' => 'fas fa-cogs', 'color' => 'primary'],
                ['title' => (\Illuminate\Support\Facades\Lang::has('reports.title') ? __('reports.title') : (\Illuminate\Support\Facades\Lang::has('common.reports_quick') ? __('common.reports_quick') : (\Illuminate\Support\Facades\Lang::has('common.reports_title') ? __('common.reports_title') : __('common.reports')))), 'url' => route('reports.index'), 'icon' => 'fas fa-chart-line', 'color' => 'warning'],
            ];
        } elseif ($user->hasRole('HR_Coordinator')) {
            return [
                ['title' => __('hrms.employees'), 'url' => route('employees.index'), 'icon' => 'fas fa-users', 'color' => 'primary'],
                ['title' => __('hrms.documents'), 'url' => route('documents.index'), 'icon' => 'fas fa-folder', 'color' => 'info'],
                ['title' => __('hrms.renew'), 'url' => route('contracts.index') . '?filter=expiring', 'icon' => 'fas fa-clock', 'color' => 'warning'],
            ];
        } elseif ($user->hasRole('Employee')) {
            return [
                ['title' => __('common.my_profile'), 'url' => route('employee-portal.profile'), 'icon' => 'fas fa-user', 'color' => 'primary'],
                ['title' => __('common.my_documents'), 'url' => route('employee-portal.documents'), 'icon' => 'fas fa-folder', 'color' => 'info'],
                ['title' => __('common.my_payslips'), 'url' => route('employee-portal.payslips'), 'icon' => 'fas fa-file-invoice', 'color' => 'success'],
                ['title' => __('hrms.hr_letter') ?? __('common.generate_letter'), 'url' => route('employee-portal.request-letter'), 'icon' => 'fas fa-envelope', 'color' => 'warning'],
            ];
        } elseif ($user->hasRole('IT_Admin')) {
            return [
                ['title' => __('common.audit_trail'), 'url' => route('audit-trail.index'), 'icon' => 'fas fa-history', 'color' => 'secondary'],
                ['title' => (\Illuminate\Support\Facades\Lang::has('reports.title') ? __('reports.title') : (\Illuminate\Support\Facades\Lang::has('common.reports_quick') ? __('common.reports_quick') : (\Illuminate\Support\Facades\Lang::has('common.reports_title') ? __('common.reports_title') : __('common.reports')))), 'url' => route('reports.index'), 'icon' => 'fas fa-server', 'color' => 'dark'],
                ['title' => __('common.weekly_digest'), 'url' => route('weekly-digest.index'), 'icon' => 'fas fa-envelope-open', 'color' => 'info'],
            ];
        }

        return [];
    }

    /**
     * Get employee statistics and trends.
     */
    public function getEmployeeStatistics($user): array
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::active()->count(),
            'inactive_employees' => Employee::byStatus('inactive')->count(),
            'terminated_employees' => Employee::byStatus('terminated')->count(),
            'on_leave_employees' => Employee::byStatus('on_leave')->count(),
            'new_hires_this_month' => Employee::whereMonth('hire_date', $currentMonth)
                ->whereYear('hire_date', $currentYear)
                ->count(),
            'new_hires_this_year' => Employee::whereYear('hire_date', $currentYear)->count(),
            'employees_without_contracts' => Employee::active()
                ->whereDoesntHave('activeContract')
                ->count(),
            'employees_without_salary_structures' => Employee::active()
                ->whereDoesntHave('currentSalaryStructure')
                ->count(),
        ];

        // Department breakdown
        $stats['by_department'] = Employee::active()
            ->select('departments.name_en', 'departments.name_ar', DB::raw('count(*) as total'))
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name_en', 'departments.name_ar')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => app()->getLocale() === 'ar' ? $item->name_ar : $item->name_en,
                    'total' => $item->total,
                    'percentage' => round(($item->total / Employee::active()->count()) * 100, 1)
                ];
            });

        // Position breakdown
        $stats['by_position'] = Employee::active()
            ->select('positions.name_en', 'positions.name_ar', DB::raw('count(*) as total'))
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->groupBy('positions.id', 'positions.name_en', 'positions.name_ar')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => app()->getLocale() === 'ar' ? $item->name_ar : $item->name_en,
                    'total' => $item->total,
                    'percentage' => round(($item->total / Employee::active()->count()) * 100, 1)
                ];
            });

        // Monthly hiring trends (last 12 months)
        $stats['hiring_trends'] = collect(range(0, 11))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'month' => $date->format('M Y'),
                'count' => Employee::whereMonth('hire_date', $date->month)
                    ->whereYear('hire_date', $date->year)
                    ->count()
            ];
        })->reverse()->values();

        return $stats;
    }

    /**
     * Get contract analytics and expiry alerts.
     */
    public function getContractAnalytics($user): array
    {
        $currentDate = now();

        $analytics = [
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'expired_contracts' => Contract::where('status', 'expired')->count(),
            'terminated_contracts' => Contract::where('status', 'terminated')->count(),
            'contracts_expiring_soon' => Contract::active()
                ->where('end_date', '<=', $currentDate->copy()->addDays(30))
                ->count(),
            'contracts_expiring_this_month' => Contract::active()
                ->whereMonth('end_date', $currentDate->month)
                ->whereYear('end_date', $currentDate->year)
                ->count(),
        ];

        // Contract type breakdown
        $analytics['by_type'] = Contract::active()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => __('hrms.contract_types.' . $item->type),
                    'total' => $item->total,
                    'percentage' => round(($item->total / Contract::active()->count()) * 100, 1)
                ];
            });

        // Expiry alerts (categorized)
        $analytics['expiry_alerts'] = [
            'urgent' => Contract::active()
                ->where('end_date', '<=', $currentDate->copy()->addDays(7))
                ->with(['employee'])
                ->orderBy('end_date')
                ->get(),
            'critical' => Contract::active()
                ->where('end_date', '>', $currentDate->copy()->addDays(7))
                ->where('end_date', '<=', $currentDate->copy()->addDays(15))
                ->with(['employee'])
                ->orderBy('end_date')
                ->get(),
            'soon' => Contract::active()
                ->where('end_date', '>', $currentDate->copy()->addDays(15))
                ->where('end_date', '<=', $currentDate->copy()->addDays(30))
                ->with(['employee'])
                ->orderBy('end_date')
                ->get(),
        ];

        // Contract renewal trends (last 6 months)
        $analytics['renewal_trends'] = collect(range(0, 5))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'month' => $date->format('M Y'),
                'new_contracts' => Contract::whereMonth('start_date', $date->month)
                    ->whereYear('start_date', $date->year)
                    ->count(),
                'expired_contracts' => Contract::whereMonth('end_date', $date->month)
                    ->whereYear('end_date', $date->year)
                    ->count()
            ];
        })->reverse()->values();

        return $analytics;
    }

    /**
     * Get payroll insights and trends.
     */
    public function getPayrollInsights($user): array
    {
        // Check user permissions for payroll data
        if (!$user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager', 'HR_Coordinator'])) {
            return ['access_restricted' => true];
        }

        $currentYear = now()->year;
        $canViewNetGross = $user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager']);

        $insights = [
            'total_payroll_runs' => PayrollRun::count(),
            'draft_runs' => PayrollRun::draft()->count(),
            'pending_approval' => PayrollRun::pendingApproval()->count(),
            'posted_runs_this_year' => PayrollRun::posted()
                ->whereYear('pay_date', $currentYear)
                ->count(),
            'total_payslips_this_year' => Payslip::whereYear('pay_date', $currentYear)->count(),
            'employees_with_salary_structures' => Employee::active()
                ->whereHas('currentSalaryStructure')
                ->count(),
        ];

        if ($canViewNetGross) {
            // Financial insights (only for authorized users)
            $lastPayrollRun = PayrollRun::posted()->latest('pay_date')->first();

            $insights['financial_summary'] = [
                'last_payroll_total_gross' => $lastPayrollRun?->total_gross ?? 0,
                'last_payroll_total_net' => $lastPayrollRun?->total_net ?? 0,
                'last_payroll_total_deductions' => $lastPayrollRun?->total_deductions ?? 0,
                'year_to_date_gross' => PayrollRun::posted()
                    ->whereYear('pay_date', $currentYear)
                    ->sum('total_gross'),
                'year_to_date_net' => PayrollRun::posted()
                    ->whereYear('pay_date', $currentYear)
                    ->sum('total_net'),
                'average_monthly_payroll' => PayrollRun::posted()
                    ->whereYear('pay_date', $currentYear)
                    ->avg('total_net'),
            ];

            // Monthly payroll trends
            $insights['payroll_trends'] = collect(range(0, 11))->map(function ($monthsAgo) {
                $date = now()->subMonths($monthsAgo);
                $monthlyRuns = PayrollRun::posted()
                    ->whereMonth('pay_date', $date->month)
                    ->whereYear('pay_date', $date->year);

                return [
                    'month' => $date->format('M Y'),
                    'runs_count' => $monthlyRuns->count(),
                    'total_gross' => $monthlyRuns->sum('total_gross'),
                    'total_net' => $monthlyRuns->sum('total_net'),
                    'employees_paid' => $monthlyRuns->sum('total_employees'),
                ];
            })->reverse()->values();
        }

        // Payroll status distribution
        $insights['status_distribution'] = [
            'draft' => PayrollRun::draft()->count(),
            'calculated' => PayrollRun::where('status', 'calculated')->count(),
            'locked' => PayrollRun::where('status', 'locked')->count(),
            'pending_approval' => PayrollRun::pendingApproval()->count(),
            'approved' => PayrollRun::where('status', 'approved')->count(),
            'posted' => PayrollRun::posted()->count(),
        ];

        // Recent payroll activity
        $insights['recent_payrolls'] = PayrollRun::with(['creator'])
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($run) use ($canViewNetGross) {
                return [
                    'id' => $run->id,
                    'title' => $run->title,
                    'status' => $run->status,
                    'status_display' => $run->status_display,
                    'pay_period' => $run->pay_period,
                    'total_employees' => $run->total_employees,
                    'total_net' => $canViewNetGross ? $run->total_net : null,
                    'created_by' => $run->creator?->name,
                    'created_at' => $run->created_at,
                ];
            });

        return $insights;
    }

    /**
     * Get recent activities across the system.
     */
    public function getRecentActivities($user): array
    {
        $activities = \Spatie\Activitylog\Models\Activity::with(['causer', 'subject'])
            ->whereIn('log_name', ['employee', 'contract', 'payroll_run', 'payslip', 'salary_structure'])
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'log_name' => $activity->log_name,
                    'subject_type' => $activity->subject_type,
                    'subject_id' => $activity->subject_id,
                    'causer_name' => $activity->causer?->name ?? 'System',
                    'created_at' => $activity->created_at,
                    'time_ago' => $activity->created_at->diffForHumans(),
                ];
            });

        return $activities->toArray();
    }

    /**
     * Get critical alerts that need attention.
     */
    public function getCriticalAlerts($user): array
    {
        $alerts = [];
        $currentDate = now();

        // Contract expiry alerts
        $urgentContracts = Contract::active()
            ->where('end_date', '<=', $currentDate->copy()->addDays(7))
            ->count();

        if ($urgentContracts > 0) {
            $alerts[] = [
                'type' => 'contract_expiry',
                'severity' => 'urgent',
                'title' => __('hrms.dashboard.urgent_contract_expiry'),
                'message' => __('hrms.dashboard.contracts_expiring_in_days', ['count' => $urgentContracts, 'days' => 7]),
                'count' => $urgentContracts,
                'link' => route('contracts.index', ['expiring' => 'urgent']),
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger'
            ];
        }

        // Payroll alerts
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            $pendingPayrolls = PayrollRun::pendingApproval()->count();
            if ($pendingPayrolls > 0) {
                $alerts[] = [
                    'type' => 'payroll_approval',
                    'severity' => 'warning',
                    'title' => __('hrms.dashboard.payroll_pending_approval'),
                    'message' => __('hrms.dashboard.payrolls_awaiting_approval', ['count' => $pendingPayrolls]),
                    'count' => $pendingPayrolls,
                    'link' => route('payroll.index', ['status' => 'pending_approval']),
                    'icon' => 'fas fa-clock',
                    'color' => 'warning'
                ];
            }
        }

        // Employee without salary structures
        $employeesWithoutSalary = Employee::active()
            ->whereDoesntHave('currentSalaryStructure')
            ->count();

        if ($employeesWithoutSalary > 0) {
            $alerts[] = [
                'type' => 'missing_salary_structure',
                'severity' => 'info',
                'title' => __('hrms.dashboard.missing_salary_structures'),
                'message' => __('hrms.dashboard.employees_without_salary_structure', ['count' => $employeesWithoutSalary]),
                'count' => $employeesWithoutSalary,
                'link' => route('employees.index', ['without_salary_structure' => 1]),
                'icon' => 'fas fa-money-bill-alt',
                'color' => 'info'
            ];
        }

        // Document expiry alerts (if implemented)
        $expiringDocuments = Document::where('expires_at', '<=', $currentDate->copy()->addDays(30))
            ->where('expires_at', '>', $currentDate)
            ->count();

        if ($expiringDocuments > 0) {
            $alerts[] = [
                'type' => 'document_expiry',
                'severity' => 'warning',
                'title' => __('hrms.dashboard.documents_expiring'),
                'message' => __('hrms.dashboard.documents_expiring_soon', ['count' => $expiringDocuments]),
                'count' => $expiringDocuments,
                'link' => route('documents.index', ['expiring' => 1]),
                'icon' => 'fas fa-file-alt',
                'color' => 'warning'
            ];
        }

        return $alerts;
    }

    /**
     * Get data for dashboard charts.
     */
    public function getChartsData($user): array
    {
        $canViewPayrollData = $user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager', 'HR_Coordinator']);

        $charts = [
            'employee_status_pie' => $this->getEmployeeStatusPieChart(),
            'department_breakdown' => $this->getDepartmentBreakdownChart(),
            'hiring_trends' => $this->getHiringTrendsChart(),
            'contract_expiry_timeline' => $this->getContractExpiryTimelineChart(),
        ];

        if ($canViewPayrollData) {
            $charts['payroll_trends'] = $this->getPayrollTrendsChart($user);
        }

        return $charts;
    }

    /**
     * Get employee status pie chart data.
     */
    protected function getEmployeeStatusPieChart(): array
    {
        return [
            'labels' => [
                __('hrms.status.active'),
                __('hrms.status.inactive'),
                __('hrms.status.terminated'),
                __('hrms.status.on_leave')
            ],
            'data' => [
                Employee::active()->count(),
                Employee::byStatus('inactive')->count(),
                Employee::byStatus('terminated')->count(),
                Employee::byStatus('on_leave')->count(),
            ],
            'colors' => ['#28a745', '#6c757d', '#dc3545', '#ffc107']
        ];
    }

    /**
     * Get department breakdown chart data.
     */
    protected function getDepartmentBreakdownChart(): array
    {
        $departments = Employee::active()
            ->select('departments.name_en', 'departments.name_ar', DB::raw('count(*) as total'))
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name_en', 'departments.name_ar')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $departments->map(function ($dept) {
                return app()->getLocale() === 'ar' ? $dept->name_ar : $dept->name_en;
            })->toArray(),
            'data' => $departments->pluck('total')->toArray(),
        ];
    }

    /**
     * Get hiring trends chart data.
     */
    protected function getHiringTrendsChart(): array
    {
        $trends = collect(range(0, 11))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'month' => $date->format('M Y'),
                'hires' => Employee::whereMonth('hire_date', $date->month)
                    ->whereYear('hire_date', $date->year)
                    ->count(),
                'terminations' => Employee::whereMonth('updated_at', $date->month)
                    ->whereYear('updated_at', $date->year)
                    ->where('status', 'terminated')
                    ->count()
            ];
        })->reverse()->values();

        return [
            'labels' => $trends->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => __('hrms.dashboard.new_hires'),
                    'data' => $trends->pluck('hires')->toArray(),
                    'color' => '#28a745'
                ],
                [
                    'label' => __('hrms.dashboard.terminations'),
                    'data' => $trends->pluck('terminations')->toArray(),
                    'color' => '#dc3545'
                ]
            ]
        ];
    }

    /**
     * Get contract expiry timeline chart data.
     */
    protected function getContractExpiryTimelineChart(): array
    {
        $timeline = collect(range(0, 11))->map(function ($monthsAhead) {
            $date = now()->addMonths($monthsAhead);
            return [
                'month' => $date->format('M Y'),
                'expiring' => Contract::active()
                    ->whereMonth('end_date', $date->month)
                    ->whereYear('end_date', $date->year)
                    ->count()
            ];
        });

        return [
            'labels' => $timeline->pluck('month')->toArray(),
            'data' => $timeline->pluck('expiring')->toArray(),
        ];
    }

    /**
     * Get payroll trends chart data.
     */
    protected function getPayrollTrendsChart($user): array
    {
        $canViewNetGross = $user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager']);

        $trends = collect(range(0, 11))->map(function ($monthsAgo) use ($canViewNetGross) {
            $date = now()->subMonths($monthsAgo);
            $monthlyRuns = PayrollRun::posted()
                ->whereMonth('pay_date', $date->month)
                ->whereYear('pay_date', $date->year);

            $data = [
                'month' => $date->format('M Y'),
                'employees_paid' => $monthlyRuns->sum('total_employees'),
                'runs_count' => $monthlyRuns->count(),
            ];

            if ($canViewNetGross) {
                $data['total_net'] = $monthlyRuns->sum('total_net');
                $data['total_gross'] = $monthlyRuns->sum('total_gross');
            }

            return $data;
        })->reverse()->values();

        $chart = [
            'labels' => $trends->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => __('hrms.dashboard.employees_paid'),
                    'data' => $trends->pluck('employees_paid')->toArray(),
                    'color' => '#007bff'
                ]
            ]
        ];

        if ($canViewNetGross) {
            $chart['datasets'][] = [
                'label' => __('hrms.dashboard.total_net_pay'),
                'data' => $trends->pluck('total_net')->toArray(),
                'color' => '#28a745'
            ];
        }

        return $chart;
    }

    /**
     * Widget-specific data generation methods.
     */
    private function getEmployeeStatsWidget($user): array
    {
        $stats = $this->getEmployeeStatistics($user);
        return [
            'total' => $stats['total_employees'],
            'active' => $stats['active_employees'],
            'new_this_month' => $stats['new_hires_this_month'],
            'on_leave' => $stats['on_leave_employees'] ?? 0,
            'departments' => $stats['by_department'] ?? [],
        ];
    }

    private function getContractExpiryWidget($user): array
    {
        if (!$user->can('viewAny', Contract::class)) {
            return ['access_denied' => true];
        }

        $contractAnalytics = $this->getContractAnalytics($user);
        return [
            'urgent' => $contractAnalytics['expiry_alerts']['urgent']->take(5),
            'critical' => $contractAnalytics['expiry_alerts']['critical']->take(3),
            'soon' => $contractAnalytics['expiry_alerts']['soon']->count(),
        ];
    }

    private function getPayrollSummaryWidget($user): array
    {
        $insights = $this->getPayrollInsights($user);

        if (isset($insights['access_restricted'])) {
            return ['access_denied' => true];
        }

        $canViewNetGross = $user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager']);
        $data = [
            'total_runs' => $insights['total_payroll_runs'],
            'pending' => $insights['pending_approval'],
            'employees_with_structures' => $insights['employees_with_salary_structures'],
        ];

        if ($canViewNetGross && isset($insights['financial_summary'])) {
            $data['last_gross'] = $insights['financial_summary']['last_payroll_total_gross'];
            $data['last_net'] = $insights['financial_summary']['last_payroll_total_net'];
        }

        return $data;
    }

    private function getDocumentExpiryWidget($user): array
    {
        $expiringDocs = Document::where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>=', now())
            ->with('employee')
            ->orderBy('expires_at')
            ->take(10)
            ->get();

        $expiredDocs = Document::where('expires_at', '<', now())
            ->count();

        return [
            'expiring_soon' => $expiringDocs,
            'expired_count' => $expiredDocs,
        ];
    }

    private function getProfileCompletionWidget($user): array
    {
        if (!$user->employee) {
            return ['no_profile' => true];
        }

        $employee = $user->employee;
        $fields = ['phone', 'personal_email', 'address', 'emergency_contact_name', 'emergency_contact_phone'];
        $completed = 0;
        $missing = [];

        foreach ($fields as $field) {
            if (empty($employee->$field)) {
                $missing[] = ucfirst(str_replace('_', ' ', $field));
            } else {
                $completed++;
            }
        }

        $percentage = round(($completed / count($fields)) * 100);

        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'total' => count($fields),
            'missing_fields' => $missing,
        ];
    }

    private function getSystemHealthWidget($user): array
    {
        if (!$user->hasRole('IT_Admin')) {
            return ['access_denied' => true];
        }

        // Basic system health indicators
        $cacheWorking = Cache::has('test_key') || Cache::put('test_key', true, 1);
        $dbWorking = true;

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbWorking = false;
        }

        $diskSpace = disk_free_space('.') / disk_total_space('.') * 100;

        return [
            'database' => $dbWorking,
            'cache' => $cacheWorking,
            'disk_free_percent' => round($diskSpace, 2),
            'active_users' => \App\Models\User::where('updated_at', '>=', now()->subMinutes(30))->count(),
            'total_activities_today' => activity()->whereDate('created_at', today())->count(),
        ];
    }

    private function getAuditLogsWidget($user): array
    {
        if (!$user->can('viewAny', \Spatie\Activitylog\Models\Activity::class)) {
            return ['access_denied' => true];
        }

        $recentActivities = activity()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'causer' => $activity->causer?->name ?? 'System',
                    'created_at' => $activity->created_at->diffForHumans(),
                    'subject_type' => class_basename($activity->subject_type ?? ''),
                ];
            });

        return [
            'recent_activities' => $recentActivities,
            'total_today' => activity()->whereDate('created_at', today())->count(),
            'total_this_week' => activity()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    /**
     * Clear dashboard cache for a user.
     */
    public function clearUserCache($userId): void
    {
        $pattern = "dashboard_analytics_{$userId}_*";
        Cache::flush(); // In production, you'd want more targeted cache clearing
    }
}
