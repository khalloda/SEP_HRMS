@extends('layouts.app')

@section('title', __('Audit Trail') . ' - ' . class_basename($subject))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Audit Trail') }}</h2>
            <p class="text-muted mb-0">
                {{ class_basename($subject) }} #{{ $subject->id }}
                @if(method_exists($subject, 'getDisplayNameAttribute'))
                    - {{ $subject->display_name }}
                @elseif(isset($subject->name))
                    - {{ $subject->name }}
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('audit-trail.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Audit Trail') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Activities Timeline -->
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Activity History') }}</h5>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="timeline">
                        @foreach($activities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                @if(str_contains($activity->description, 'created'))
                                    <i class="fas fa-plus-circle text-success"></i>
                                @elseif(str_contains($activity->description, 'updated'))
                                    <i class="fas fa-edit text-primary"></i>
                                @elseif(str_contains($activity->description, 'deleted') || str_contains($activity->description, 'terminated'))
                                    <i class="fas fa-trash text-danger"></i>
                                @elseif(str_contains($activity->description, 'renewed'))
                                    <i class="fas fa-refresh text-warning"></i>
                                @else
                                    <i class="fas fa-circle text-secondary"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $activity->description }}</h6>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted">
                                        {{ $activity->created_at->format('F j, Y \a\t g:i A') }}
                                        @if($activity->causer)
                                            {{ __('by') }} <strong>{{ $activity->causer->name }}</strong>
                                        @endif
                                    </small>
                                </div>

                                @if($activity->properties && count($activity->properties) > 0)
                                    <div class="changes-detail">
                                        @if(isset($activity->properties['changes']))
                                            <h6 class="small text-uppercase text-muted mb-2">{{ __('Changes Made') }}</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Field') }}</th>
                                                            <th>{{ __('From') }}</th>
                                                            <th>{{ __('To') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($activity->properties['changes'] as $field => $change)
                                                        <tr>
                                                            <td><code>{{ $field }}</code></td>
                                                            <td>
                                                                @if(is_array($change) && isset($change['old']))
                                                                    <span class="text-muted">{{ $change['old'] ?? 'null' }}</span>
                                                                @else
                                                                    <span class="text-muted">{{ $change }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(is_array($change) && isset($change['new']))
                                                                    <strong>{{ $change['new'] ?? 'null' }}</strong>
                                                                @else
                                                                    <strong>{{ $change }}</strong>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        @if(isset($activity->properties['termination_details']))
                                            <h6 class="small text-uppercase text-muted mb-2 mt-3">{{ __('Termination Details') }}</h6>
                                            <ul class="list-unstyled">
                                                @foreach($activity->properties['termination_details'] as $key => $value)
                                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if(isset($activity->properties['renewal_details']))
                                            <h6 class="small text-uppercase text-muted mb-2 mt-3">{{ __('Renewal Details') }}</h6>
                                            <ul class="list-unstyled">
                                                @foreach($activity->properties['renewal_details'] as $key => $value)
                                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No activity history') }}</h5>
                        <p class="text-muted">{{ __('This record has no audit trail entries.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Subject Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Record Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('Type') }}</label>
                    <div><strong>{{ class_basename($subject) }}</strong></div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('ID') }}</label>
                    <div><code>#{{ $subject->id }}</code></div>
                </div>

                @if($subjectType === 'contract' && $subject->employee)
                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('Employee') }}</label>
                        <div>
                            <a href="{{ route('employees.show', $subject->employee) }}" class="text-decoration-none">
                                {{ $subject->employee->display_name }}
                            </a>
                            <br><small class="text-muted">{{ $subject->employee->code }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('Contract Type') }}</label>
                        <div>{{ $subject->type_name }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('Status') }}</label>
                        <div>
                            <span class="badge
                                @if($subject->status === 'active') bg-success
                                @elseif($subject->status === 'expired') bg-danger
                                @elseif($subject->status === 'terminated') bg-dark
                                @else bg-secondary
                                @endif
                            ">
                                {{ $subject->status_name }}
                            </span>
                        </div>
                    </div>
                @endif

                @if($subjectType === 'employee')
                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('Employee Code') }}</label>
                        <div><code>{{ $subject->code }}</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">{{ __('Name') }}</label>
                        <div>{{ $subject->display_name }}</div>
                    </div>

                    @if($subject->department)
                        <div class="mb-3">
                            <label class="form-label small text-muted">{{ __('Department') }}</label>
                            <div>{{ $subject->department->name }}</div>
                        </div>
                    @endif
                @endif

                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('Created') }}</label>
                    <div>{{ $subject->created_at->format('M j, Y \a\t g:i A') }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted">{{ __('Last Updated') }}</label>
                    <div>{{ $subject->updated_at->format('M j, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Activity Summary') }}</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="brand-gold mb-1">{{ $activities->count() }}</h4>
                            <small class="text-muted">{{ __('Total Activities') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="brand-dark-green mb-1">
                            {{ $activities->where('created_at', '>=', now()->subDays(30))->count() }}
                        </h4>
                        <small class="text-muted">{{ __('Last 30 Days') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 20px;
    width: 2px;
    height: calc(100% + 1rem);
    background-color: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-radius: 50%;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 3px solid var(--color-gold);
}

.changes-detail {
    background-color: white;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #e9ecef;
    margin-top: 0.75rem;
}

code {
    background-color: #f1f3f4;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}
</style>
@endpush