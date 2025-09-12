@extends('layouts.app')

@section('title', $employee->display_name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 brand-dark-green">{{ $employee->display_name }}</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-secondary">{{ $employee->code }}</span>
                @switch($employee->status)
                    @case('active')
                        <span class="badge bg-success fs-6">{{ __('hrms.status.active') }}</span>
                        @break
                    @case('inactive')
                        <span class="badge bg-warning fs-6">{{ __('hrms.status.inactive') }}</span>
                        @break
                    @case('terminated')
                        <span class="badge bg-danger fs-6">{{ __('hrms.status.terminated') }}</span>
                        @break
                    @case('on_leave')
                        <span class="badge bg-info fs-6">{{ __('hrms.status.on_leave') }}</span>
                        @break
                @endswitch
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
            </a>
            
            @can('update', $employee)
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-brand-primary">
                    <i class="fas fa-edit"></i> {{ __('hrms.edit') }}
                </a>
            @endcan
            
            @can('terminate', $employee)
                @if($employee->status === 'active')
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#terminateModal">
                        <i class="fas fa-ban"></i> {{ __('hrms.employee.terminate') }}
                    </button>
                @endif
            @endcan
            
            @can('reactivate', $employee)
                @if($employee->status === 'terminated')
                    <form method="POST" action="{{ route('employees.reactivate', $employee) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('{{ __('Are you sure?') }}')">
                            <i class="fas fa-undo"></i> {{ __('hrms.employee.reactivate') }}
                        </button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Employee Information -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> {{ __('hrms.personal_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th class="w-40">{{ __('hrms.employee_code') }}:</th>
                                    <td><code class="text-dark">{{ $employee->code }}</code></td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.first_name') }}:</th>
                                    <td>{{ $employee->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.last_name') }}:</th>
                                    <td>{{ $employee->last_name }}</td>
                                </tr>
                                @if($employee->arabic_name)
                                    <tr>
                                        <th>{{ __('hrms.arabic_name') }}:</th>
                                        <td>{{ $employee->arabic_name }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                @if($employee->email)
                                    <tr>
                                        <th class="w-40">{{ __('hrms.email') }}:</th>
                                        <td><a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></td>
                                    </tr>
                                @endif
                                @if($employee->phone)
                                    <tr>
                                        <th>{{ __('hrms.phone') }}:</th>
                                        <td><a href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a></td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>{{ __('hrms.hire_date') }}:</th>
                                    <td>
                                        {{ $employee->hire_date?->format('Y-m-d') ?? __('N/A') }}
                                        @if($employee->hire_date)
                                            <br><small class="text-muted">({{ $employee->hire_date->diffForHumans() }})</small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-briefcase"></i> {{ __('hrms.employment_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th class="w-40">{{ __('hrms.department') }}:</th>
                                    <td>
                                        @if($employee->department)
                                            <span class="badge bg-secondary">{{ $employee->department->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.position') }}:</th>
                                    <td>
                                        @if($employee->position)
                                            <span class="badge bg-info">{{ $employee->position->name }}</span>
                                            <br><small class="text-muted">{{ $employee->hierarchy_level }}</small>
                                        @else
                                            <span class="text-muted">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.employee.employment_type') }}:</th>
                                    <td>
                                        @if($employee->employmentType)
                                            <span class="badge bg-primary">{{ $employee->employmentType->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th class="w-40">{{ __('hrms.manager') }}:</th>
                                    <td>
                                        @if($employee->manager)
                                            <a href="{{ route('employees.show', $employee->manager) }}">
                                                {{ $employee->manager->display_name }}
                                            </a>
                                            <br><code class="text-muted small">{{ $employee->manager->code }}</code>
                                        @else
                                            <span class="text-muted">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.employee.years_of_service') }}:</th>
                                    <td>{{ $stats['years_of_service'] ?? __('N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('hrms.employee.is_overtime_eligible') }}:</th>
                                    <td>
                                        @if($employee->isOvertimeEligible())
                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($activities->count() > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> {{ __('Recent Activity') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($activities as $activity)
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-light p-2">
                                        <i class="fas fa-history text-muted"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $activity->description }}</div>
                                    <div class="text-muted small">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->causer)
                                            by {{ $activity->causer->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line"></i> {{ __('Quick Stats') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $stats['direct_reports_count'] ?? 0 }}</h4>
                                <small class="text-muted">{{ __('hrms.employee.direct_reports') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $stats['contracts_count'] ?? 0 }}</h4>
                            <small class="text-muted">{{ __('hrms.contracts') }}</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                @if($stats['has_user_account'] ?? false)
                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('No') }}</span>
                                @endif
                                <br><small class="text-muted">{{ __('hrms.employee.has_user_account') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            @if($stats['active_contract'] ?? false)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-warning">{{ __('Inactive') }}</span>
                            @endif
                            <br><small class="text-muted">{{ __('Contract Status') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Direct Reports -->
            @if($employee->directReports->count() > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> {{ __('hrms.employee.direct_reports') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($employee->directReports as $report)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <a href="{{ route('employees.show', $report) }}" class="text-decoration-none">
                                        {{ $report->display_name }}
                                    </a>
                                    <br><small class="text-muted">{{ $report->position?->name }}</small>
                                </div>
                                <span class="badge bg-light text-dark">{{ $report->code }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> {{ __('hrms.actions') }}
                    </h5>
                </div>
                <div class="card-body d-grid gap-2">
                    @can('update', $employee)
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-brand-primary">
                            <i class="fas fa-edit"></i> {{ __('hrms.edit') }} {{ __('Employee') }}
                        </a>
                    @endcan
                    
                    <!-- Add more action buttons as needed -->
                    <button type="button" class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-file-contract"></i> {{ __('View Contracts') }}
                        <small class="text-muted d-block">Coming Soon</small>
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-file-alt"></i> {{ __('View Documents') }}
                        <small class="text-muted d-block">Coming Soon</small>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terminate Employee Modal -->
    @can('terminate', $employee)
        @if($employee->status === 'active')
            <div class="modal fade" id="terminateModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('employees.terminate', $employee) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('hrms.employee.terminate') }} {{ $employee->display_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="termination_date" class="form-label">{{ __('hrms.employee.termination_date') }}</label>
                                    <input type="date" class="form-control" id="termination_date" name="termination_date" 
                                           value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="termination_reason" class="form-label">{{ __('hrms.employee.termination_reason') }}</label>
                                    <textarea class="form-control" id="termination_reason" name="termination_reason" 
                                              rows="3" placeholder="{{ __('Enter termination reason...') }}" required></textarea>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ __('This action will terminate the employee. Are you sure you want to continue?') }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('hrms.cancel') }}</button>
                                <button type="submit" class="btn btn-warning">{{ __('hrms.employee.terminate') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endcan
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .w-40 {
            width: 40%;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush