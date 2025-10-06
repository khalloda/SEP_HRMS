@extends('layouts.app')

@section('title', __('Reports & Analytics'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Reports & Analytics') }}</h2>
            <p class="text-muted mb-0">{{ __('Generate comprehensive reports and export data') }}</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ number_format($stats['total_employees']) }}</h4>
                <p class="card-text text-muted small">{{ __('Total Employees') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ number_format($stats['active_contracts']) }}</h4>
                <p class="card-text text-muted small">{{ __('Active Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ number_format($stats['expiring_contracts']) }}</h4>
                <p class="card-text text-muted small">{{ __('Expiring Soon') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ number_format($stats['documents_count']) }}</h4>
                <p class="card-text text-muted small">{{ __('Total Documents') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-dark-green mb-2">
                    <i class="fas fa-file-invoice-dollar fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ number_format($stats['payslips_this_month']) }}</h4>
                <p class="card-text text-muted small">{{ __('Payslips This Month') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row">
    @foreach($reportCategories as $categoryKey => $category)
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="{{ $category['icon'] }}"></i> {{ $category['title'] }}
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">{{ $category['description'] }}</p>

                <div class="list-group list-group-flush">
                    @foreach($category['reports'] as $reportKey => $reportName)
                    <a href="{{ route('reports.' . str_replace('-', '.', $reportKey)) }}"
                       class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            <span>{{ $reportName }}</span>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning"></i> {{ __('common.quick_actions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('reports.employee.list') }}?export_format=excel"
                           class="btn btn-outline-success w-100">
                            <i class="fas fa-file-excel"></i> {{ __('Export All Employees') }}
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('reports.contract.status') }}?status=active&export_format=pdf"
                           class="btn btn-outline-danger w-100">
                            <i class="fas fa-file-pdf"></i> {{ __('Active Contracts PDF') }}
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('reports.payroll.summary') }}?month={{ now()->format('Y-m') }}&export_format=excel"
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-calculator"></i> {{ __('Current Payroll') }}
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('reports.document.inventory') }}?expiry_status=expiring&export_format=pdf"
                           class="btn btn-outline-warning w-100">
                            <i class="fas fa-clock"></i> {{ __('Expiring Documents') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

.list-group-item-action:hover {
    background-color: var(--color-cream);
    transform: translateX(5px);
    transition: all 0.2s ease;
}

.card-title {
    font-size: 1.8rem;
    font-weight: bold;
}

.quick-action-btn {
    transition: transform 0.2s;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
}
</style>
@endpush