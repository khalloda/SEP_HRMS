@extends('layouts.app')

@section('title', __('Contract Status Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Contract Status Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Contract tracking with expiry alerts and status analysis') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <form method="POST" action="{{ route('reports.saved.store') }}" class="d-inline">
              @csrf
              <input type="hidden" name="name" value="Contract Status">
              <input type="hidden" name="report_key" value="reports.contract.status">
              <input type="hidden" name="params" value='@json(request()->query())'>
              <input type="hidden" name="format" value="xlsx">
              <button class="btn btn-outline-primary" type="submit">
                <i class="fas fa-bookmark"></i> {{ __('Save Report') }}
              </button>
            </form>
            <a class="btn btn-outline-secondary" href="{{ route('reports.saved.index') }}">
                <i class="fas fa-list"></i> {{ __('Saved Reports') }}
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total']) }}</h4>
                <p class="card-text text-muted">{{ __('Total Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['by_status']['active'] ?? 0) }}</h4>
                <p class="card-text text-muted">{{ __('Active Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['expiring_soon']) }}</h4>
                <p class="card-text text-muted">{{ __('Expiring Soon') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-danger mb-2">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['by_status']['expired'] ?? 0) }}</h4>
                <p class="card-text text-muted">{{ __('Expired') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Export -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">{{ __('Contract Status') }}</label>
                <select name="status" id="status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>
                        {{ __('Active') }}
                    </option>
                    <option value="expired" {{ ($filters['status'] ?? '') == 'expired' ? 'selected' : '' }}>
                        {{ __('Expired') }}
                    </option>
                    <option value="terminated" {{ ($filters['status'] ?? '') == 'terminated' ? 'selected' : '' }}>
                        {{ __('Terminated') }}
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="contract_type" class="form-label">{{ __('Contract Type') }}</label>
                <select name="contract_type" id="contract_type" class="form-select">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="permanent" {{ ($filters['contract_type'] ?? '') == 'permanent' ? 'selected' : '' }}>
                        {{ __('Permanent') }}
                    </option>
                    <option value="fixed_term" {{ ($filters['contract_type'] ?? '') == 'fixed_term' ? 'selected' : '' }}>
                        {{ __('Fixed Term') }}
                    </option>
                    <option value="probation" {{ ($filters['contract_type'] ?? '') == 'probation' ? 'selected' : '' }}>
                        {{ __('Probation') }}
                    </option>
                    <option value="internship" {{ ($filters['contract_type'] ?? '') == 'internship' ? 'selected' : '' }}>
                        {{ __('Internship') }}
                    </option>
                    <option value="consultancy" {{ ($filters['contract_type'] ?? '') == 'consultancy' ? 'selected' : '' }}>
                        {{ __('Consultancy') }}
                    </option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-brand-primary me-2">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('reports.contract.status') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
            </div>
        </form>

        <hr class="my-3">
        <div class="row">
            <div class="col-12">
                <h6 class="mb-2">{{ __('Export Options') }}</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contract Status Breakdown -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Contracts by Status') }}</h5>
            </div>
            <div class="card-body">
                @foreach($summary['by_status'] as $status => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ ucfirst($status) }}</span>
                    <span class="badge bg-secondary">{{ number_format($count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Contracts by Type') }}</h5>
            </div>
            <div class="card-body">
                @foreach($summary['by_type'] as $type => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ ucfirst(str_replace('_', ' ', $type)) }}</span>
                    <span class="badge bg-secondary">{{ number_format($count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Contracts List -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Contract Details') }}</h5>
        <span class="badge bg-light text-dark">{{ number_format($contracts->count()) }} {{ __('contracts') }}</span>
    </div>
    <div class="card-body p-0">
        @if($contracts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Contract Type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Days Until Expiry') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contracts as $contract)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $contract->employee->display_name }}</strong>
                                    <br><small class="text-muted">{{ $contract->employee->code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary">{{ $contract->employee->department->name_en }}</span>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $contract->type)) }}</td>
                            <td>
                                @if($contract->status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @elseif($contract->status === 'expired')
                                    <span class="badge bg-danger">{{ __('Expired') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($contract->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $contract->start_date->format('M j, Y') }}</td>
                            <td>
                                @if($contract->end_date)
                                    {{ $contract->end_date->format('M j, Y') }}
                                @else
                                    <span class="text-muted">{{ __('No End Date') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($contract->end_date)
                                    @php
                                        $daysUntilExpiry = now()->diffInDays($contract->end_date, false);
                                    @endphp
                                    @if($daysUntilExpiry < 0)
                                        <span class="badge bg-danger">{{ __('Expired :days days ago', ['days' => abs($daysUntilExpiry)]) }}</span>
                                    @elseif($daysUntilExpiry <= 7)
                                        <span class="badge bg-danger">{{ __(':days days', ['days' => $daysUntilExpiry]) }}</span>
                                    @elseif($daysUntilExpiry <= 30)
                                        <span class="badge bg-warning">{{ __(':days days', ['days' => $daysUntilExpiry]) }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __(':days days', ['days' => $daysUntilExpiry]) }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('N/A') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No contracts found') }}</h5>
                <p class="text-muted">{{ __('Try adjusting your filters to see more results.') }}</p>
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

.table-header-custom {
    background-color: var(--color-dark-green);
    color: white;
}

.brand-dark-green {
    color: var(--color-dark-green);
}

.brand-gold {
    color: var(--color-gold);
}

.btn-brand-primary {
    background-color: var(--color-gold);
    border-color: var(--color-gold);
    color: white;
}

.btn-brand-primary:hover {
    background-color: var(--color-light-gold);
    border-color: var(--color-light-gold);
    color: white;
}

.badge.bg-outline-primary {
    color: var(--color-gold);
    border: 1px solid var(--color-gold);
    background-color: transparent;
}
</style>
@endpush

@push('scripts')
<script>
function exportReport(format) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export_format', format);
    window.location.href = currentUrl.toString();
}
</script>
@endpush
