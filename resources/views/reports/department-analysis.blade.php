@extends('layouts.app')

@section('title', __('Department Analysis Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Department Analysis Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Detailed analysis of department performance and employee distribution') }}</p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Export Options -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-download"></i> {{ __('Export Options') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('reports.department.analysis', ['export_format' => 'excel']) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                </a>
                <a href="{{ route('reports.department.analysis', ['export_format' => 'pdf']) }}"
                   class="btn btn-danger ms-2">
                    <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Department Analysis -->
<div class="row">
    @foreach($analysis as $item)
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-building"></i> {{ $item['department']->name_en }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="brand-gold mb-1">{{ $item['total_employees'] }}</h4>
                        <p class="text-muted small mb-3">{{ __('Total Employees') }}</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ number_format($item['avg_tenure'], 1) }}</h4>
                        <p class="text-muted small mb-3">{{ __('Avg. Tenure (months)') }}</p>
                    </div>
                </div>

                @if($item['by_position']->isNotEmpty())
                <h6 class="mb-3">{{ __('By Position') }}:</h6>
                @foreach($item['by_position'] as $position => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">{{ $position }}</span>
                    <span class="badge bg-primary">{{ $count }}</span>
                </div>
                @endforeach
                @else
                <p class="text-muted">{{ __('No position breakdown available') }}</p>
                @endif

                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">
                        <i class="fas fa-file-contract"></i> {{ __('Active Contracts') }}: {{ $item['active_contracts'] }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($analysis->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-building fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">{{ __('No Departments Found') }}</h4>
        <p class="text-muted">{{ __('No department data is available for analysis.') }}</p>
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

.brand-gold {
    color: var(--color-gold);
}
</style>
@endpush