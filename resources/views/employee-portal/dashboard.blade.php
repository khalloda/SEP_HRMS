@extends('layouts.app')

@section('title', __('Employee Portal'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="h3 brand-dark-green mb-1">{{ __('Welcome, :name', ['name' => $employee->first_name]) }}</h2>
        <p class="text-muted mb-0">{{ $employee->position->name }} - {{ $employee->department->name }}</p>
    </div>
    <div class="text-end">
        <small class="text-muted">{{ __('Employee Code') }}: <strong>{{ $employee->code }}</strong></small>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Profile Completion Card -->
        @if($profile_completion < 100)
            <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-user-check"></i> {{ __('Complete Your Profile') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $profile_completion }}%"></div>
                        </div>
                    </div>
                    <span class="ms-3 fw-bold">{{ $profile_completion }}%</span>
                </div>
                <p class="mb-2">{{ __('Your profile is :percent% complete. Complete your profile to access all features.', ['percent' => $profile_completion]) }}</p>
                <a href="{{ route('employee-portal.profile') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> {{ __('Update Profile') }}
                </a>
            </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0"><i class="fas fa-bolt"></i> {{ __('common.quick_actions') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($quick_actions as $action)
                <div class="col-md-4 col-sm-6 mb-3">
                    <a href="{{ $action['url'] }}" class="btn btn-outline-{{ $action['color'] }} w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                        <i class="{{ $action['icon'] }} fa-2x mb-2"></i>
                        <span class="small fw-bold">{{ $action['title'] }}</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Documents -->
    @if($recent_documents->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-folder-open"></i> {{ __('Recent Documents') }}</h5>
            <a href="{{ route('employee-portal.documents') }}" class="btn btn-sm btn-outline-light">{{ __('View All') }}</a>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($recent_documents as $document)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="{{ $document->type_icon }} text-primary me-3"></i>
                        <div>
                            <h6 class="mb-1">{{ $document->original_name }}</h6>
                            <small class="text-muted">{{ $document->type_display_name }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">{{ $document->created_at->diffForHumans() }}</small>
                        @if($document->is_expired)
                        <br><span class="badge bg-danger">{{ __('Expired') }}</span>
                        @elseif($document->is_expiring_soon)
                        <br><span class="badge bg-warning">{{ __('Expiring Soon') }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Events -->
    @if($upcoming_events->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> {{ __('Upcoming Events') }}</h5>
        </div>
        <div class="card-body">
            @foreach($upcoming_events as $event)
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-{{ $event['color'] }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="{{ $event['icon'] }}"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $event['title'] }}</h6>
                    <small class="text-muted">{{ $event['date']->format('M j, Y') }} ({{ $event['date']->diffForHumans() }})</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Right Column -->
<div class="col-lg-4">
    <!-- Employee Info Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
            @if($employee->photo)
            <img src="{{ $employee->photo_url }}" alt="{{ $employee->display_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
            @else
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 80px; height: 80px;">
                <i class="fas fa-user fa-2x text-white"></i>
            </div>
            @endif
            <h5 class="mb-1">{{ $employee->display_name }}</h5>
            <p class="text-muted mb-2">{{ $employee->position->name }}</p>
            <span class="badge bg-success">{{ $employee->employment_status }}</span>
        </div>
        <div class="card-footer bg-light">
            <div class="row text-center">
                <div class="col">
                    <small class="text-muted d-block">{{ __('Department') }}</small>
                    <strong>{{ $employee->department->name }}</strong>
                </div>
                <div class="col">
                    <small class="text-muted d-block">{{ __('Hire Date') }}</small>
                    <strong>{{ $employee->hire_date->format('M Y') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Contract -->
    @if($active_contract)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0"><i class="fas fa-file-contract"></i> {{ __('Current Contract') }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted d-block">{{ __('Type') }}</small>
                    <strong>{{ $active_contract->type_name }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">{{ __('Status') }}</small>
                    <span class="badge bg-success">{{ $active_contract->status_name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted d-block">{{ __('Start Date') }}</small>
                    <strong>{{ $active_contract->start_date->format('M j, Y') }}</strong>
                </div>
                @if($active_contract->end_date)
                <div class="col-6">
                    <small class="text-muted d-block">{{ __('End Date') }}</small>
                    <strong>{{ $active_contract->end_date->format('M j, Y') }}</strong>
                </div>
                @endif
            </div>
            @if($active_contract->end_date && $active_contract->end_date->diffInDays(now()) <= 60)
                <div class="alert alert-warning mt-3 mb-0">
                <small><i class="fas fa-exclamation-triangle"></i> {{ __('Contract expires in :days days', ['days' => now()->diffInDays($active_contract->end_date, false)]) }}</small>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Attendance Summary -->
@if($attendance_summary)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0"><i class="fas fa-clock"></i> {{ __('This Month') }}</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-6 mb-3">
                <div class="border-end">
                    <h4 class="brand-gold mb-1">{{ $attendance_summary->present_days ?? 0 }}</h4>
                    <small class="text-muted">{{ __('Present Days') }}</small>
                </div>
            </div>
            <div class="col-6 mb-3">
                <h4 class="brand-dark-green mb-1">{{ number_format($attendance_summary->total_hours ?? 0, 1) }}</h4>
                <small class="text-muted">{{ __('Work Hours') }}</small>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-6">
                <div class="border-end">
                    <h5 class="text-danger mb-1">{{ $attendance_summary->late_days ?? 0 }}</h5>
                    <small class="text-muted">{{ __('Late Days') }}</small>
                </div>
            </div>
            <div class="col-6">
                <h5 class="text-success mb-1">{{ number_format($attendance_summary->total_overtime ?? 0, 1) }}</h5>
                <small class="text-muted">{{ __('OT Hours') }}</small>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('employee-portal.attendance') }}" class="btn btn-outline-primary btn-sm w-100">
                {{ __('View Details') }}
            </a>
        </div>
    </div>
</div>
@endif

<!-- Recent Payslips -->
@if($recent_payslips->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-invoice-dollar"></i> {{ __('Recent Payslips') }}</h5>
        <a href="{{ route('employee-portal.payslips') }}" class="btn btn-sm btn-outline-light">{{ __('View All') }}</a>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($recent_payslips as $payslip)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">{{ $payslip->pay_period_start->format('M Y') }}</h6>
                    <small class="text-muted">{{ $payslip->payrollRun->name ?? 'Regular Payroll' }}</small>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block">{{ $payslip->created_at->format('M j') }}</small>
                    <a href="{{ route('payslips.download-pdf', $payslip) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
</div>
</div>
@endsection

@push('styles')
<style>
    .card-header-custom {
        background-color: var(--color-gold);
        color: white;
        font-weight: 600;
    }

    .brand-gold {
        color: var(--color-gold);
    }

    .brand-dark-green {
        color: var(--color-dark-green);
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .quick-action-card {
        transition: transform 0.2s;
    }

    .quick-action-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush