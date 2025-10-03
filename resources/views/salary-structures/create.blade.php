@extends('layouts.app')

@section('title', __('Create Salary Structure'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Create Salary Structure for :name', ['name' => $employee->display_name]) }}</h1>
            <p class="text-muted mb-0">{{ __('Define the currency and components that will determine this employee’s compensation.') }}</p>
        </div>
        <a href="{{ route('employees.salary-structures.index', $employee) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('Back to structures') }}</span>
        </a>
    </div>

    @php
    $currencyOptions = payrollCurrencies();
    if (empty($currencyOptions)) {
    $currencyOptions = ['EGP' => 'EGP'];
    }
    $defaultCurrency = array_key_first($currencyOptions) ?? 'EGP';
    @endphp

    <form method="POST" action="{{ route('employees.salary-structures.store', $employee) }}" class="row g-3">
        @csrf

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Structure Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="currency" class="form-label">{{ __('Currency') }}</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                @foreach($currencyOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('currency', $currentStructure?->currency ?? $defaultCurrency) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="effective_from" class="form-label">{{ __('Effective From') }}</label>
                            <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from') }}" class="form-control @error('effective_from') is-invalid @enderror" required>
                            @error('effective_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="effective_to" class="form-label">{{ __('Effective To') }}</label>
                            <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to') }}" class="form-control @error('effective_to') is-invalid @enderror">
                            @error('effective_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Components') }}</h5>
                    <span class="text-muted small">{{ __('Specify at least one component to activate this structure.') }}</span>
                </div>
                <div class="card-body">
                    @if(!empty($components))
                    @foreach($components as $type => $group)
                    <div class="mb-4">
                        <h6 class="fw-semibold text-uppercase small text-muted">{{ ucfirst($type) }}</h6>
                        @foreach($group as $component)
                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $component->name }}</strong>
                                    <div class="text-muted small">{{ $component->code }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <input type="hidden" name="components[{{ $component->id }}][component_id]" value="{{ $component->id }}">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="components[{{ $component->id }}][value_numeric]" placeholder="{{ __('Amount') }}" value="{{ old("components.{$component->id}.value_numeric") }}">
                                    <input type="text" class="form-control form-control-sm" name="components[{{ $component->id }}][formula_expr]" placeholder="{{ __('Formula') }}" value="{{ old("components.{$component->id}.formula_expr") }}">
                                    <input type="number" class="form-control form-control-sm" name="components[{{ $component->id }}][priority_order]" placeholder="{{ __('Priority') }}" value="{{ old("components.{$component->id}.priority_order", $loop->iteration) }}" min="1" max="999">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted mb-0">{{ __('No salary components are available. Please configure components first.') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('employees.salary-structures.index', $employee) }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="ms-1">{{ __('Create Structure') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection