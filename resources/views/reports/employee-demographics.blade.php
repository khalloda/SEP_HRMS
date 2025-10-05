@extends('layouts.app')

@section('title', __('Employee Demographics Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Employee Demographics Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Statistical analysis of employee demographics and tenure') }}</p>
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
                <a href="{{ route('reports.employee.demographics', ['export_format' => 'excel']) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                </a>
                <a href="{{ route('reports.employee.demographics', ['export_format' => 'pdf']) }}"
                   class="btn btn-danger ms-2">
                    <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Demographics Charts -->
<div class="row">
    <!-- By Department -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-building"></i> {{ __('By Department') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_department'] as $department => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $department }}</span>
                    <div>
                        <span class="badge bg-primary">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar" style="width: {{ $demographics['by_department']->count() > 0 ? ($count / $demographics['by_department']->sum()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No department data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- By Position -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie"></i> {{ __('By Position') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_position'] as $position => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $position }}</span>
                    <div>
                        <span class="badge bg-info">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $demographics['by_position']->count() > 0 ? ($count / $demographics['by_position']->sum()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No position data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- By Status -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-toggle-on"></i> {{ __('By Status') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_status'] as $status => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ __(ucfirst($status)) }}</span>
                    <div>
                        <span class="badge bg-success">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ array_sum($demographics['by_status']->toArray()) > 0 ? ($count / array_sum($demographics['by_status']->toArray())) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No status data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- By Employment Type -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-file-contract"></i> {{ __('By Employment Type') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_employment_type'] as $type => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $type }}</span>
                    <div>
                        <span class="badge bg-warning">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $demographics['by_employment_type']->count() > 0 ? ($count / $demographics['by_employment_type']->sum()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No employment type data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- By Tenure Group -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt"></i> {{ __('By Tenure Group') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_tenure_group'] as $group => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $group }}</span>
                    <div>
                        <span class="badge bg-secondary">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-secondary" style="width: {{ array_sum($demographics['by_tenure_group']) > 0 ? ($count / array_sum($demographics['by_tenure_group'])) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No tenure data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- By Hire Year -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line"></i> {{ __('By Hire Year') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($demographics['by_hire_year'] as $year => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $year }}</span>
                    <div>
                        <span class="badge bg-dark">{{ $count }}</span>
                        <div class="progress" style="width: 100px; height: 8px;">
                            <div class="progress-bar bg-dark" style="width: {{ array_sum($demographics['by_hire_year']->toArray()) > 0 ? ($count / array_sum($demographics['by_hire_year']->toArray())) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">{{ __('No hire year data available') }}</p>
                @endforelse
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

.brand-dark-green {
    color: var(--color-dark-green);
}

.progress {
    background-color: rgba(0,0,0,0.1);
}

.progress-bar {
    background-color: var(--color-gold);
}
</style>
@endpush