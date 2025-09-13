@extends('layouts.app')

@section('title', __('hrms.dashboard'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.dashboard') }}</h1>
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

    <!-- Recent Activity (placeholder) -->
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
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load employee statistics
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
        });

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
    </script>
@endpush