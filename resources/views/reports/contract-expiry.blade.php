@extends('layouts.app')

@section('title', __('Contract Expiry Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Contract Expiry Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Monitor contracts expiring in the next') }} {{ $daysAhead }} {{ __('days') }}</p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Filters & Export Options -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="days_ahead" class="form-label">{{ __('Days Ahead') }}</label>
                <select name="days_ahead" id="days_ahead" class="form-select">
                    <option value="30" {{ ($filters['days_ahead'] ?? 90) == 30 ? 'selected' : '' }}>{{ __('30 Days') }}</option>
                    <option value="60" {{ ($filters['days_ahead'] ?? 90) == 60 ? 'selected' : '' }}>{{ __('60 Days') }}</option>
                    <option value="90" {{ ($filters['days_ahead'] ?? 90) == 90 ? 'selected' : '' }}>{{ __('90 Days') }}</option>
                    <option value="180" {{ ($filters['days_ahead'] ?? 90) == 180 ? 'selected' : '' }}>{{ __('180 Days') }}</option>
                    <option value="365" {{ ($filters['days_ahead'] ?? 90) == 365 ? 'selected' : '' }}>{{ __('1 Year') }}</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> {{ __('Filter') }}
                    </button>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Export Options') }}</label>
                <div>
                    <a href="{{ route('reports.contract.expiry', array_merge($filters, ['export_format' => 'excel'])) }}"
                       class="btn btn-success">
                        <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                    </a>
                    <a href="{{ route('reports.contract.expiry', array_merge($filters, ['export_format' => 'pdf'])) }}"
                       class="btn btn-danger ms-2">
                        <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-danger mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ ($groupedContracts['urgent'] ?? collect())->count() }}</h4>
                <p class="card-text text-muted small">{{ __('Urgent (≤7 days)') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-warning mb-2">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ ($groupedContracts['soon'] ?? collect())->count() }}</h4>
                <p class="card-text text-muted small">{{ __('Soon (≤30 days)') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-info mb-2">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ ($groupedContracts['future'] ?? collect())->count() }}</h4>
                <p class="card-text text-muted small">{{ __('Future (>30 days)') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="brand-gold mb-2">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ $contracts->count() }}</h4>
                <p class="card-text text-muted small">{{ __('Total Contracts') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Contract Groups -->
@if($contracts->isNotEmpty())
    @foreach(['urgent', 'soon', 'future'] as $group)
        @if(($groupedContracts[$group] ?? collect())->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center"
                 style="background-color: {{ $group === 'urgent' ? '#dc3545' : ($group === 'soon' ? '#ffc107' : '#17a2b8') }}; color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-{{ $group === 'urgent' ? 'exclamation-triangle' : ($group === 'soon' ? 'clock' : 'calendar-alt') }}"></i>
                    @if($group === 'urgent')
                        {{ __('Urgent - Expiring Within 7 Days') }}
                    @elseif($group === 'soon')
                        {{ __('Soon - Expiring Within 30 Days') }}
                    @else
                        {{ __('Future - Expiring Later') }}
                    @endif
                </h5>
                <span class="badge bg-light text-dark">{{ ($groupedContracts[$group] ?? collect())->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Contract Type') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Days Until Expiry') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedContracts[$group] ?? collect() as $contract)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <div class="fw-bold">{{ $contract->employee->display_name }}</div>
                                            <div class="text-muted small">{{ $contract->employee->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $contract->employee->department->name_en ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ __(ucfirst(str_replace('_', ' ', $contract->type))) }}
                                    </span>
                                </td>
                                <td>{{ $contract->end_date->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $daysUntil = now()->diffInDays($contract->end_date, false);
                                        $badgeClass = $daysUntil <= 7 ? 'bg-danger' : ($daysUntil <= 30 ? 'bg-warning' : 'bg-info');
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $daysUntil }} {{ __('days') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('contracts.show', $contract->id) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           title="{{ __('View Contract') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $contract)
                                        <a href="{{ route('contracts.renew', $contract->id) }}"
                                           class="btn btn-outline-success btn-sm"
                                           title="{{ __('Renew Contract') }}">
                                            <i class="fas fa-sync"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">{{ __('No Contracts Expiring') }}</h4>
            <p class="text-muted">{{ __('No contracts are expiring in the next') }} {{ $daysAhead }} {{ __('days') }}.</p>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
.brand-dark-green {
    color: var(--color-dark-green);
}

.brand-gold {
    color: var(--color-gold);
}

.card-title {
    font-size: 1.8rem;
    font-weight: bold;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: var(--color-dark-green);
}

.btn-group .btn {
    border-radius: 0.25rem;
}

.btn-group .btn + .btn {
    margin-left: 0.25rem;
}
</style>
@endpush