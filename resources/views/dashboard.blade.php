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
                        <button class="btn btn-outline-light btn-lg" disabled>
                            <i class="fas fa-file-contract"></i> {{ __('hrms.contracts') }}
                            <small class="d-block">{{ __('Coming Soon') }}</small>
                        </button>
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
                    <h3 class="card-title text-warning">0</h3>
                    <p class="card-text text-muted">{{ __('Expiring Contracts') }}</p>
                </div>
            </div>
        </div>
    </div>

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
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-file-contract"></i> {{ __('New Contract') }}
                            <small class="text-muted d-block">{{ __('Coming Soon') }}</small>
                        </button>
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
            // Simulate loading employee stats (you can make AJAX call to employees.statistics route later)
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
                    document.getElementById('total-employees').textContent = '0';
                    document.getElementById('active-employees').textContent = '0';
                    document.getElementById('new-hires').textContent = '0';
                });
        });
    </script>
@endpush