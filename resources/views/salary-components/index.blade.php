@extends('layouts.app')

@section('title', __('Salary Components'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ __('Salary Components') }}</h1>
        <p class="text-muted mb-0">{{ __('Manage salary earnings, deductions, and information components') }}</p>
    </div>
    <div class="btn-group">
        @can('create', App\Models\SalaryComponent::class)
        <a href="{{ route('salary-components.create') }}" class="btn btn-brand-primary">
            <i class="fas fa-plus"></i> {{ __('Add Component') }}
        </a>
        <form method="POST" action="{{ route('salary-components.seed-predefined') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-brand-primary"
                onclick="return confirm('{{ __('This will create predefined salary components. Continue?') }}')">
                <i class="fas fa-seedling"></i> {{ __('Seed Predefined') }}
            </button>
        </form>
        @endcan
    </div>
</div>
@endsection

@section('content')
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('salary-components.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">{{ __('Search') }}</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="{{ __('Search components...') }}">
            </div>

            <div class="col-md-2">
                <label for="comp_type" class="form-label">{{ __('Type') }}</label>
                <select class="form-select" id="comp_type" name="comp_type">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="earning" {{ request('comp_type') == 'earning' ? 'selected' : '' }}>
                        {{ __('Earning') }}
                    </option>
                    <option value="deduction" {{ request('comp_type') == 'deduction' ? 'selected' : '' }}>
                        {{ __('Deduction') }}
                    </option>
                    <option value="info" {{ request('comp_type') == 'info' ? 'selected' : '' }}>
                        {{ __('Information') }}
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="calc_mode" class="form-label">{{ __('Calculation Mode') }}</label>
                <select class="form-select" id="calc_mode" name="calc_mode">
                    <option value="">{{ __('All Modes') }}</option>
                    <option value="fixed" {{ request('calc_mode') == 'fixed' ? 'selected' : '' }}>
                        {{ __('Fixed Amount') }}
                    </option>
                    <option value="formula" {{ request('calc_mode') == 'formula' ? 'selected' : '' }}>
                        {{ __('Formula Based') }}
                    </option>
                    <option value="variable_net_based" {{ request('calc_mode') == 'variable_net_based' ? 'selected' : '' }}>
                        {{ __('Variable') }}
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="taxable" class="form-label">{{ __('Taxable') }}</label>
                <select class="form-select" id="taxable" name="taxable">
                    <option value="">{{ __('All') }}</option>
                    <option value="1" {{ request('taxable') === '1' ? 'selected' : '' }}>
                        {{ __('Yes') }}
                    </option>
                    <option value="0" {{ request('taxable') === '0' ? 'selected' : '' }}>
                        {{ __('No') }}
                    </option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-brand-primary">
                    <i class="fas fa-search"></i> {{ __('Search') }}
                </button>
                <a href="{{ route('salary-components.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-start border-4 border-brand-gold">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('Total Components') }}</h5>
                        <h2 class="mb-0 brand-dark-green">{{ number_format($stats['total']) }}</h2>
                    </div>
                    <div class="text-brand-gold">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('Earnings') }}</h5>
                        <h2 class="mb-0 text-success">{{ number_format($stats['earnings']) }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-plus-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('Deductions') }}</h5>
                        <h2 class="mb-0 text-danger">{{ number_format($stats['deductions']) }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-minus-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('Information') }}</h5>
                        <h2 class="mb-0 text-info">{{ number_format($stats['info_only']) }}</h2>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Components Table -->
<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Salary Components') }}</h5>
    </div>
    <div class="card-body p-0">
        @if($components->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header-custom">
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Calculation') }}</th>
                        <th>{{ __('Taxable') }}</th>
                        <th>{{ __('Priority') }}</th>
                        <th>{{ __('Visibility') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($components as $component)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $component->code }}</span>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $component->display_name }}</div>
                                @if(app()->getLocale() === 'en' && $component->name_ar)
                                <small class="text-muted">{{ $component->name_ar }}</small>
                                @elseif(app()->getLocale() === 'ar' && $component->name_en)
                                <small class="text-muted">{{ $component->name_en }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($component->comp_type === 'earning')
                            <span class="badge bg-success">
                                <i class="fas fa-plus"></i> {{ __('Earning') }}
                            </span>
                            @elseif($component->comp_type === 'deduction')
                            <span class="badge bg-danger">
                                <i class="fas fa-minus"></i> {{ __('Deduction') }}
                            </span>
                            @else
                            <span class="badge bg-info">
                                <i class="fas fa-info"></i> {{ __('Information') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-brand-dark-green">
                                {{ $component->calc_mode_display_name }}
                            </span>
                        </td>
                        <td>
                            @if($component->taxable)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-check"></i> {{ __('Yes') }}
                            </span>
                            @else
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-times"></i> {{ __('No') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $component->priority_order }}</span>
                        </td>
                        <td>
                            @if(empty($component->visible_to_roles))
                            <span class="text-success">{{ __('All Roles') }}</span>
                            @else
                            <small class="text-muted">
                                {{ implode(', ', $component->visible_to_roles) }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('view', $component)
                                <a href="{{ route('salary-components.show', $component) }}"
                                    class="btn btn-outline-brand-primary" title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan

                                @can('update', $component)
                                <a href="{{ route('salary-components.edit', $component) }}"
                                    class="btn btn-outline-warning" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan

                                @can('delete', $component)
                                <form method="POST" action="{{ route('salary-components.destroy', $component) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this component?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3">
            <div class="text-muted">
                {{ __('Showing') }} {{ $components->firstItem() }} {{ __('to') }} {{ $components->lastItem() }}
                {{ __('of') }} {{ $components->total() }} {{ __('results') }}
            </div>
            {{ $components->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-list fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('No salary components found') }}</h5>
            <p class="text-muted">{{ __('Create salary components to build payroll structures.') }}</p>
            @can('create', App\Models\SalaryComponent::class)
            <div class="btn-group">
                <a href="{{ route('salary-components.create') }}" class="btn btn-brand-primary">
                    <i class="fas fa-plus"></i> {{ __('Add Component') }}
                </a>
                <form method="POST" action="{{ route('salary-components.seed-predefined') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-brand-primary">
                        <i class="fas fa-seedling"></i> {{ __('Seed Predefined') }}
                    </button>
                </form>
            </div>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-outline-brand-primary {
        color: var(--color-gold);
        border-color: var(--color-gold);
    }

    .btn-outline-brand-primary:hover {
        background-color: var(--color-gold);
        border-color: var(--color-gold);
        color: white;
    }
</style>
@endpush