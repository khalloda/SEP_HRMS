@extends('layouts.app')

@section('title', __('hrms.dashboard.title'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.dashboard.title') }}</h1>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success">{{ __('System Active') }}</span>
        </div>
    </div>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient" style="background: linear-gradient(135deg, var(--color-dark-green) 0%, var(--color-gold) 100%);">
                <div class="card-body text-white text-center py-5">
                    <h2 class="card-title mb-3">{{ __('Welcome to HRMS') }}</h2>
                    <p class="card-text fs-5 mb-4">
                        {{ __('Sarie Eldin & Partners - Legal Advisors') }}<br>
                        {{ __('Human Resource Management System') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('employees.index') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-users"></i> {{ __('hrms.employees') }}
                        </a>
                        <a href="{{ route('contracts.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-file-contract"></i> {{ __('hrms.contracts') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-6 text-primary mb-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title text-primary" id="total-employees">-</h3>
                    <p class="card-text text-muted">{{ __('hrms.employee.total_employees') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-6 text-success mb-2">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3 class="card-title text-success" id="active-employees">-</h3>
                    <p class="card-text text-muted">{{ __('hrms.employee.active_employees') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-6 text-info mb-2">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="card-title text-info" id="new-hires">-</h3>
                    <p class="card-text text-muted">{{ __('hrms.employee.new_hires_this_month') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-6 text-warning mb-2">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="card-title text-warning" id="expiring-contracts">{{ $expiryAlerts['urgent']->count() + $expiryAlerts['critical']->count() }}</h3>
                    <p class="card-text text-muted">{{ __('Expiring Contracts') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Expiry Alerts -->
    @if (!empty($expiryAlerts) && ($expiryAlerts['urgent']->count() > 0 || $expiryAlerts['critical']->count() > 0 || $expiryAlerts['soon']->count() > 0))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('hrms.notifications.expiry_alerts') }}
                    </h5>
                    <button class="btn btn-outline-secondary btn-sm" onclick="refreshAlerts()">
                        <i class="fas fa-sync-alt"></i> {{ __('hrms.notifications.refresh_alerts') }}
                    </button>
                </div>
                <div class="card-body">
                    @if ($expiryAlerts['urgent']->count() > 0)
                        <div class="alert alert-danger border-start border-5 border-danger">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-circle"></i> 
                                {{ __('hrms.expiring_urgently') }} ({{ $expiryAlerts['urgent']->count() }})
                            </h6>
                            <div class="row">
                                @foreach ($expiryAlerts['urgent'] as $contract)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <strong>{{ $contract->employee->display_name }}</strong><br>
                                                <small class="text-muted">{{ $contract->employee->code }} • {{ $contract->type_name }}</small><br>
                                                <small class="text-danger">{{ $contract->days_until_expiry }} days remaining</small>
                                            </div>
                                            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($expiryAlerts['critical']->count() > 0)
                        <div class="alert alert-warning border-start border-5 border-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle"></i> 
                                {{ __('hrms.expiring_critically') }} ({{ $expiryAlerts['critical']->count() }})
                            </h6>
                            <div class="row">
                                @foreach ($expiryAlerts['critical'] as $contract)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <strong>{{ $contract->employee->display_name }}</strong><br>
                                                <small class="text-muted">{{ $contract->employee->code }} • {{ $contract->type_name }}</small><br>
                                                <small class="text-warning">{{ $contract->days_until_expiry }} days remaining</small>
                                            </div>
                                            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($expiryAlerts['soon']->count() > 0)
                        <div class="alert alert-info border-start border-5 border-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i> 
                                {{ __('hrms.expiring_soon') }} ({{ $expiryAlerts['soon']->count() }})
                            </h6>
                            <div class="row">
                                @foreach ($expiryAlerts['soon']->take(6) as $contract)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <strong>{{ $contract->employee->display_name }}</strong><br>
                                                <small class="text-muted">{{ $contract->employee->code }} • {{ $contract->type_name }}</small><br>
                                                <small class="text-info">{{ $contract->days_until_expiry }} days remaining</small>
                                            </div>
                                            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($expiryAlerts['soon']->count() > 6)
                                <div class="text-center mt-3">
                                    <a href="{{ route('contracts.index', ['expiry_filter' => 'soon']) }}" class="btn btn-outline-info">
                                        {{ __('hrms.notifications.view_all') }} ({{ $expiryAlerts['soon']->count() - 6 }} more)
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('contracts.index') }}" class="btn btn-brand-primary">
                            <i class="fas fa-file-contract"></i> {{ __('Manage All Contracts') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> {{ __('Quick Actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-brand-primary">
                            <i class="fas fa-user-plus"></i> {{ __('hrms.add_employee') }}
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list"></i> {{ __('View All Employees') }}
                        </a>
                        <a href="{{ route('contracts.create') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-contract"></i> {{ __('New Contract') }}
                        </a>
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-file-upload"></i> {{ __('Upload Documents') }}
                            <small class="text-muted d-block">{{ __('Coming Soon') }}</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> {{ __('System Information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="w-50">{{ __('Application') }}:</th>
                            <td>{{ config('app.name', 'HRMS') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Version') }}:</th>
                            <td>Phase 1 - Foundation</td>
                        </tr>
                        <tr>
                            <th>{{ __('Laravel') }}:</th>
                            <td>{{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('PHP') }}:</th>
                            <td>{{ phpversion() }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Language') }}:</th>
                            <td>
                                {{ app()->getLocale() === 'ar' ? __('hrms.arabic') : __('hrms.english') }}
                                <a href="{{ route('language.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" 
                                   class="btn btn-outline-secondary btn-sm ms-2">
                                    {{ __('Switch') }}
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Analytics Widgets -->
    @if(isset($analytics))
        <!-- Analytics Dashboard Row -->
        <div class="row mb-4">
            <!-- Employee Analytics Widget -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> {{ __('hrms.dashboard.employee_analytics') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($analytics['employee_stats']))
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h4 class="text-success">{{ $analytics['employee_stats']['active_employees'] }}</h4>
                                    <small class="text-muted">{{ __('hrms.status.active') }}</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-primary">{{ $analytics['employee_stats']['new_hires_this_month'] }}</h4>
                                    <small class="text-muted">{{ __('hrms.dashboard.new_this_month') }}</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h5 class="text-warning">{{ $analytics['employee_stats']['employees_without_salary_structures'] }}</h5>
                                    <small class="text-muted">{{ __('hrms.dashboard.no_salary_structure') }}</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-info">{{ $analytics['employee_stats']['employees_without_contracts'] }}</h5>
                                    <small class="text-muted">{{ __('hrms.dashboard.no_contract') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payroll Analytics Widget -->
            @if(auth()->user()->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager', 'HR_Coordinator']))
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0">
                                <i class="fas fa-money-bill-wave"></i> {{ __('hrms.dashboard.payroll_insights') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($analytics['payroll_insights']) && !isset($analytics['payroll_insights']['access_restricted']))
                                <div class="row text-center">
                                    <div class="col-6 border-end">
                                        <h4 class="text-success">{{ $analytics['payroll_insights']['posted_runs_this_year'] }}</h4>
                                        <small class="text-muted">{{ __('hrms.dashboard.runs_this_year') }}</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-warning">{{ $analytics['payroll_insights']['pending_approval'] }}</h4>
                                        <small class="text-muted">{{ __('hrms.dashboard.pending_approval') }}</small>
                                    </div>
                                </div>
                                <hr>
                                @if(auth()->user()->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager']) && isset($analytics['payroll_insights']['financial_summary']))
                                    <div class="text-center">
                                        <h5 class="text-primary">{{ number_format($analytics['payroll_insights']['financial_summary']['year_to_date_net'], 0) }}</h5>
                                        <small class="text-muted">{{ __('hrms.dashboard.ytd_net_payroll') }}</small>
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-lock fa-2x mb-2"></i>
                                    <p class="mb-0">{{ __('hrms.dashboard.payroll_access_restricted') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Critical Alerts Widget -->
            <div class="col-lg-4 col-md-12 mb-3">
                <div class="card h-100">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle"></i> {{ __('hrms.dashboard.critical_alerts') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($analytics['alerts']) && count($analytics['alerts']) > 0)
                            @foreach(array_slice($analytics['alerts'], 0, 3) as $alert)
                                <div class="alert alert-{{ $alert['color'] }} alert-sm mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $alert['icon'] }} me-2"></i>
                                        <div class="flex-grow-1">
                                            <strong>{{ $alert['title'] }}</strong>
                                            <br><small>{{ $alert['message'] }}</small>
                                        </div>
                                        @if(isset($alert['link']))
                                            <a href="{{ $alert['link'] }}" class="btn btn-outline-{{ $alert['color'] }} btn-sm">
                                                {{ __('hrms.view') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                <p class="mb-0">{{ __('hrms.dashboard.no_critical_alerts') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Department Breakdown Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie"></i> {{ __('hrms.dashboard.employees_by_department') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="departmentChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Hiring Trends Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card h-100">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line"></i> {{ __('hrms.dashboard.hiring_trends') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="hiringTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Enhanced -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> {{ __('hrms.dashboard.recent_activities') }}
                        </h5>
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshActivities()">
                            <i class="fas fa-refresh"></i> {{ __('hrms.refresh') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="recent-activities-content">
                            @if(isset($analytics['recent_activities']) && count($analytics['recent_activities']) > 0)
                                <div class="timeline">
                                    @foreach(array_slice($analytics['recent_activities'], 0, 10) as $activity)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $activity['description'] }}</h6>
                                                        <p class="text-muted mb-0">
                                                            <small>{{ __('by') }} {{ $activity['causer_name'] }}</small>
                                                        </p>
                                                    </div>
                                                    <small class="text-muted">{{ $activity['time_ago'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-clock fa-2x mb-3"></i>
                                    <p>{{ __('hrms.dashboard.no_recent_activities') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Original Recent Activity (fallback) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> {{ __('Recent System Activity') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <p>{{ __('Activity logging will appear here once employees start using the system.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Dashboard-specific styling */
        .card-header-custom {
            background: linear-gradient(135deg, #c6a44a 0%, #b8934a 100%);
            color: white;
            border: none;
        }

        .card-header-custom h5 {
            font-weight: 600;
            margin: 0;
        }

        .card-header-custom i {
            margin-right: 8px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e3e6f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            padding-left: 15px;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            top: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e3e6f0;
        }

        .timeline-marker.bg-primary {
            background-color: #c6a44a !important;
            box-shadow: 0 0 0 2px #c6a44a;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 6px;
            border-left: 3px solid #c6a44a;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: #2e4029;
            margin: 0 0 5px 0;
        }

        .stats-card {
            transition: transform 0.2s ease-in-out;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(198, 164, 74, 0.1);
        }

        .text-gold {
            color: #c6a44a !important;
        }

        .text-green {
            color: #2e4029 !important;
        }

        .btn-outline-secondary:hover {
            background-color: #c6a44a;
            border-color: #c6a44a;
        }

        /* Chart containers */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        #departmentChart, #hiringTrendsChart {
            max-height: 300px !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .timeline {
                padding-left: 20px;
            }

            .timeline-item {
                padding-left: 10px;
            }

            .timeline-marker {
                left: -15px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <script>
        // Global chart instances
        let departmentChart = null;
        let hiringTrendsChart = null;

        // Load employee statistics and initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Load employee stats
            fetch('{{ route("employees.statistics") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-employees').textContent = data.total_employees || 0;
                    document.getElementById('active-employees').textContent = data.active_employees || 0;
                    document.getElementById('new-hires').textContent = data.new_hires_this_month || 0;
                })
                .catch(error => {
                    console.log('Statistics not available yet:', error);
                    // Set default values
                    document.getElementById('total-employees').textContent = '{{ $stats["total_employees"] ?? 0 }}';
                    document.getElementById('active-employees').textContent = '{{ $stats["active_employees"] ?? 0 }}';
                    document.getElementById('new-hires').textContent = '0';
                });

            // Initialize dashboard charts
            initializeCharts();
        });

        // Initialize all dashboard charts
        function initializeCharts() {
            // Load charts data
            fetch('{{ route("dashboard.charts-data") }}')
                .then(response => response.json())
                .then(data => {
                    initializeDepartmentChart(data.department_breakdown || {});
                    initializeHiringTrendsChart(data.hiring_trends || {});
                })
                .catch(error => {
                    console.error('Error loading charts data:', error);
                    // Initialize with empty data
                    initializeDepartmentChart({});
                    initializeHiringTrendsChart({});
                });
        }

        // Initialize department breakdown pie chart
        function initializeDepartmentChart(data) {
            const ctx = document.getElementById('departmentChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (departmentChart) {
                departmentChart.destroy();
            }

            const labels = Object.keys(data);
            const values = Object.values(data);
            const colors = generateColors(labels.length);

            departmentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize hiring trends line chart
        function initializeHiringTrendsChart(data) {
            const ctx = document.getElementById('hiringTrendsChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (hiringTrendsChart) {
                hiringTrendsChart.destroy();
            }

            // Prepare data for line chart
            const months = data.months || [];
            const hires = data.hires || [];
            const terminations = data.terminations || [];

            hiringTrendsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{ __("hrms.dashboard.new_hires") }}',
                        data: hires,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: '{{ __("hrms.dashboard.terminations") }}',
                        data: terminations,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // Generate colors for charts
        function generateColors(count) {
            const baseColors = [
                '#c6a44a', // Gold
                '#2e4029', // Dark Green
                '#f9f5e6', // Cream
                '#5e7a5e', // Medium Green
                '#8b7e3a', // Darker Gold
                '#1a2a1a', // Darker Green
                '#f0e6d2', // Light Cream
                '#9d8b4f', // Olive
                '#3d4f3d', // Forest Green
                '#e6d4b7'  // Beige
            ];

            const colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(baseColors[i % baseColors.length]);
            }
            return colors;
        }

        // Refresh contract expiry alerts
        function refreshAlerts() {
            const button = event.target.closest('button');
            const icon = button.querySelector('i');

            // Add spinning animation
            icon.classList.add('fa-spin');
            button.disabled = true;

            fetch('{{ route("dashboard.expiry-alerts") }}')
                .then(response => response.json())
                .then(data => {
                    // Update the expiring contracts count
                    const total = data.counts.urgent + data.counts.critical;
                    document.getElementById('expiring-contracts').textContent = total;

                    // Reload the page to show updated alerts
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error refreshing alerts:', error);
                    alert('Failed to refresh alerts. Please try again.');
                })
                .finally(() => {
                    // Remove spinning animation
                    icon.classList.remove('fa-spin');
                    button.disabled = false;
                });
        }

        // Refresh recent activities
        function refreshActivities() {
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            const contentDiv = document.getElementById('recent-activities-content');

            // Add spinning animation
            icon.classList.add('fa-spin');
            button.disabled = true;

            fetch('{{ route("dashboard.recent-activities") }}?limit=10')
                .then(response => response.json())
                .then(activities => {
                    if (activities.length > 0) {
                        let html = '<div class="timeline">';
                        activities.forEach(activity => {
                            const date = new Date(activity.created_at).toLocaleString();
                            html += `
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">${activity.description}</h6>
                                        <p class="text-muted mb-0">
                                            <small>
                                                <i class="fas fa-user"></i> ${activity.causer_name || 'System'}
                                                <i class="fas fa-clock ms-2"></i> ${date}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        contentDiv.innerHTML = html;
                    } else {
                        contentDiv.innerHTML = `
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-clock fa-2x mb-3"></i>
                                <p>{{ __('hrms.dashboard.no_recent_activities') }}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error refreshing activities:', error);
                    contentDiv.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <p>{{ __('hrms.dashboard.error_loading_activities') }}</p>
                        </div>
                    `;
                })
                .finally(() => {
                    // Remove spinning animation
                    icon.classList.remove('fa-spin');
                    button.disabled = false;
                });
        }

        // Auto-refresh dashboard data every 5 minutes
        setInterval(function() {
            // Refresh charts data
            fetch('{{ route("dashboard.charts-data") }}')
                .then(response => response.json())
                .then(data => {
                    initializeDepartmentChart(data.department_breakdown || {});
                    initializeHiringTrendsChart(data.hiring_trends || {});
                })
                .catch(error => {
                    console.log('Auto-refresh failed:', error);
                });

            // Refresh employee statistics
            fetch('{{ route("employees.statistics") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-employees').textContent = data.total_employees || 0;
                    document.getElementById('active-employees').textContent = data.active_employees || 0;
                    document.getElementById('new-hires').textContent = data.new_hires_this_month || 0;
                })
                .catch(error => {
                    console.log('Statistics refresh failed:', error);
                });
        }, 300000); // 5 minutes
    </script>
@endpush