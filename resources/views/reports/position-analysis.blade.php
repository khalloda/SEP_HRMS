@extends('layouts.app')

@section('title', __('Position Analysis Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Position Analysis Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Detailed analysis of position distribution and tenure by role') }}</p>
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
                <a href="{{ route('reports.position.analysis', ['export_format' => 'excel']) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                </a>
                <a href="{{ route('reports.position.analysis', ['export_format' => 'pdf']) }}"
                   class="btn btn-danger ms-2">
                    <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Position Analysis -->
<div class="row">
    @foreach($analysis as $item)
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie"></i> {{ $item['position']->name_en }}
                    @if($item['position']->overtime_eligible)
                        <span class="badge bg-light text-dark ms-2">{{ __('OT Eligible') }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h4 class="brand-gold mb-1">{{ $item['total_employees'] }}</h4>
                        <p class="text-muted small mb-3">{{ __('Total') }}</p>
                    </div>
                    <div class="col-4">
                        <h4 class="text-info mb-1">{{ number_format($item['avg_tenure'], 1) }}</h4>
                        <p class="text-muted small mb-3">{{ __('Avg. Tenure') }}</p>
                    </div>
                    <div class="col-4">
                        <h4 class="text-success mb-1">{{ $item['eligible_overtime'] }}</h4>
                        <p class="text-muted small mb-3">{{ __('OT Eligible') }}</p>
                    </div>
                </div>

                @if($item['by_department']->isNotEmpty())
                <h6 class="mb-3">{{ __('By Department') }}:</h6>
                @foreach($item['by_department'] as $department => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">{{ $department }}</span>
                    <span class="badge bg-info">{{ $count }}</span>
                </div>
                @endforeach
                @else
                <p class="text-muted">{{ __('No department breakdown available') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($analysis->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">{{ __('No Positions Found') }}</h4>
        <p class="text-muted">{{ __('No position data is available for analysis.') }}</p>
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