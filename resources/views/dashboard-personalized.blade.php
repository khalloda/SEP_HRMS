@extends('layouts.app')

@section('title', isset($analytics['role_config']['custom_title']) ? $analytics['role_config']['custom_title'] : __('hrms.dashboard.title'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0 brand-dark-green">
            {{ isset($analytics['role_config']['custom_title']) ? $analytics['role_config']['custom_title'] : __('hrms.dashboard.title') }}
        </h1>
        <p class="text-muted mb-0">
            {{ __('hrms.dashboard.welcome_message') }} -
            <span class="badge bg-{{ isset($analytics['role_config']['theme_color']) ? $analytics['role_config']['theme_color'] : 'primary' }}">
                {{ Auth::user()->getRoleNames()->first() ?? __('hrms.employee.title') }}
            </span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success">{{ __('common.system_active') }}</span>
        <button class="btn btn-outline-secondary btn-sm" onclick="refreshDashboard()">
            <i class="fas fa-sync-alt"></i> {{ __('hrms.dashboard.refresh') }}
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Quick Actions Section -->
@if(isset($analytics['quick_actions']) && count($analytics['quick_actions']) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> {{ __('common.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($analytics['quick_actions'] as $action)
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ $action['url'] }}" class="btn btn-outline-{{ $action['color'] }} w-100 d-flex align-items-center justify-content-center p-3 text-decoration-none quick-action-btn">
                            <i class="{{ $action['icon'] }} me-2"></i>
                            {{ $action['title'] }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Personalized Widgets -->
<div class="row">
    @if(isset($analytics['personalized_widgets']))
    @foreach($analytics['personalized_widgets'] as $widget)
    <div class="{{ $widget['size'] }} mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-{{ $widget['color'] }} text-white">
                <h5 class="mb-0">
                    <i class="{{ $widget['icon'] }}"></i> {{ $widget['title'] }}
                </h5>
            </div>
            <div class="card-body">
                @include('dashboard.widgets.' . $widget['type'], ['data' => $widget['data']])
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ __('Dashboard customization is loading...') }}
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary ms-2">
                {{ __('Refresh') }}
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Additional Context-Aware Sections -->
@if(isset($analytics['role_config']['visible_sections']) && in_array('charts', $analytics['role_config']['visible_sections']))
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> {{ __('hrms.dashboard.employee_analytics') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="dashboardChart" width="400" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Activities for Authorized Users -->
@if(Auth::user()->hasAnyRole(['HR_Admin_Manager', 'IT_Admin']) && isset($analytics['recent_activities']))
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history"></i> {{ __('hrms.dashboard.recent_activities') }}</h5>
                <a href="{{ route('audit-trail.index') }}" class="btn btn-sm btn-outline-light">
                    {{ __('hrms.dashboard.view_all') }}
                </a>
            </div>
            <div class="card-body p-0">
                @if(isset($analytics['recent_activities']) && count($analytics['recent_activities']) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <tbody>
                            @foreach(array_slice($analytics['recent_activities'], 0, 8) as $activity)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="fas fa-circle fa-xs text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ __('hrms.activity.' . $activity['description']) }}</div>
                                            <small class="text-muted">
                                                {{ $activity['causer_name'] === 'System' ? __('common.system') : $activity['causer_name'] }} •
                                                {{ $activity['time_ago'] }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                    <p class="text-muted">{{ __('hrms.dashboard.no_recent_activities') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .card-header-custom {
        background-color: var(--color-gold);
        color: white;
        font-weight: 600;
    }

    .brand-dark-green {
        color: var(--color-dark-green);
    }

    .quick-action-btn {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .widget-stat {
        font-size: 2rem;
        font-weight: bold;
    }

    .widget-progress {
        height: 8px;
        border-radius: 4px;
    }

    .badge-role {
        font-size: 0.75rem;
    }

    /* Role-specific theme colors */
    .theme-hr-admin .card-header {
        background-color: var(--color-success);
    }

    .theme-accounting .card-header {
        background-color: var(--color-warning);
    }

    .theme-hr-coordinator .card-header {
        background-color: var(--color-info);
    }

    .theme-employee .card-header {
        background-color: var(--color-primary);
    }

    .theme-it-admin .card-header {
        background-color: var(--color-dark);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dashboard refresh functionality
    function refreshDashboard() {
        location.reload();
    }

    // Initialize charts if data is available
    @if(isset($analytics['charts_data']) && $analytics['charts_data'])
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dashboardChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {
                        !!json_encode($analytics['charts_data']['labels'] ?? []) !!
                    },
                    datasets: {
                        !!json_encode($analytics['charts_data']['datasets'] ?? []) !!
                    }
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
    });
    @endif

    // Auto-refresh dashboard every 5 minutes for real-time data
    setInterval(function() {
        // Only auto-refresh if user is active (has interacted in last 5 minutes)
        if (document.hidden === false) {
            fetch('{{ route("dashboard.analytics") }}')
                .then(response => response.json())
                .then(data => {
                    // Update key statistics without full page reload
                    updateDashboardStats(data);
                })
                .catch(error => console.log('Dashboard auto-refresh failed:', error));
        }
    }, 300000); // 5 minutes

    function updateDashboardStats(data) {
        // Update statistics counters
        if (data.employee_stats) {
            const totalEmployeesEl = document.getElementById('total-employees');
            if (totalEmployeesEl && data.employee_stats.total_employees) {
                totalEmployeesEl.textContent = data.employee_stats.total_employees.toLocaleString();
            }
        }

        // Add other real-time updates as needed
    }
</script>
@endpush