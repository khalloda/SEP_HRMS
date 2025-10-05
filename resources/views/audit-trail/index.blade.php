@extends('layouts.app')

@section('title', __('Audit Trail'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Audit Trail') }}</h2>
            <p class="text-muted mb-0">{{ __('System activity and change history') }}</p>
        </div>
        @can('export', \Spatie\Activitylog\Models\Activity::class)
            <div>
                <a href="{{ route('audit-trail.export', request()->query()) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-download"></i> {{ __('Export') }}
                </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <h5 class="card-title">{{ $contractStats['total_contract_changes'] }}</h5>
                <p class="card-text text-muted">{{ __('Contract Changes') }}</p>
                <small class="text-success">{{ $contractStats['recent_changes'] }} {{ __('this week') }}</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-dark-green mb-2">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <h5 class="card-title">{{ $salaryStats['total_salary_changes'] }}</h5>
                <p class="card-text text-muted">{{ __('Salary Changes') }}</p>
                <small class="text-success">{{ $salaryStats['recent_changes'] }} {{ __('this week') }}</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h5 class="card-title">{{ $salaryStats['critical_employee_changes'] }}</h5>
                <p class="card-text text-muted">{{ __('Employee Critical Changes') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h5 class="card-title">{{ $activities->total() }}</h5>
                <p class="card-text text-muted">{{ __('Total Activities') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Filters') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('audit-trail.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="days" class="form-label">{{ __('Time Period') }}</label>
                    <select name="days" id="days" class="form-select">
                        <option value="7" {{ $days == 7 ? 'selected' : '' }}>{{ __('Last 7 days') }}</option>
                        <option value="30" {{ $days == 30 ? 'selected' : '' }}>{{ __('Last 30 days') }}</option>
                        <option value="90" {{ $days == 90 ? 'selected' : '' }}>{{ __('Last 90 days') }}</option>
                        <option value="" {{ !$days ? 'selected' : '' }}>{{ __('All time') }}</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="log_type" class="form-label">{{ __('Activity Type') }}</label>
                    <select name="log_type" id="log_type" class="form-select">
                        <option value="all" {{ $logType == 'all' ? 'selected' : '' }}>{{ __('All Types') }}</option>
                        <option value="contract" {{ $logType == 'contract' ? 'selected' : '' }}>{{ __('Contract Changes') }}</option>
                        <option value="salary_structure" {{ $logType == 'salary_structure' ? 'selected' : '' }}>{{ __('Salary Changes') }}</option>
                        <option value="employee_critical" {{ $logType == 'employee_critical' ? 'selected' : '' }}>{{ __('Employee Critical') }}</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="user_id" class="form-label">{{ __('User') }}</label>
                    <input type="text" name="user_id" id="user_id" class="form-control" value="{{ $userId }}" placeholder="{{ __('User ID (optional)') }}">
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-brand-primary w-100">
                        <i class="fas fa-filter"></i> {{ __('Filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activities List -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Recent Activities') }}</h5>
    </div>
    <div class="card-body p-0">
        @if($activities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Timestamp') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $activity->created_at->format('M j, Y') }}<br>
                                    {{ $activity->created_at->format('g:i A') }}
                                </small>
                            </td>
                            <td>
                                @if($activity->causer)
                                    <strong>{{ $activity->causer->name }}</strong>
                                    @if($activity->causer->employee)
                                        <br><small class="text-muted">{{ $activity->causer->employee->code }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('System') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if(str_contains($activity->description, 'created')) bg-success
                                    @elseif(str_contains($activity->description, 'updated')) bg-primary
                                    @elseif(str_contains($activity->description, 'deleted') || str_contains($activity->description, 'terminated')) bg-danger
                                    @elseif(str_contains($activity->description, 'renewed')) bg-warning
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ $activity->description }}
                                </span>
                            </td>
                            <td>
                                {{ class_basename($activity->subject_type) }}
                                @if($activity->subject)
                                    <br><small class="text-muted">#{{ $activity->subject_id }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-outline-secondary">{{ $activity->log_name }}</span>
                            </td>
                            <td>
                                @if($activity->properties && count($activity->properties) > 0)
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $activity->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @if($activity->properties && count($activity->properties) > 0)
                        <tr class="collapse" id="details-{{ $activity->id }}">
                            <td colspan="6" class="bg-light">
                                <div class="p-3">
                                    <h6>{{ __('Change Details') }}</h6>
                                    <pre class="small mb-0">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3">
                {{ $activities->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No audit trail entries found') }}</h5>
                <p class="text-muted">{{ __('Try adjusting your filters or check back later.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.8em;
}
pre {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.75rem;
    font-size: 0.875rem;
    max-height: 200px;
    overflow-y: auto;
}
</style>
@endpush