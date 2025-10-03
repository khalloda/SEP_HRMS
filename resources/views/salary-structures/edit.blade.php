@extends('layouts.app')

@section('title', __('Edit Salary Structure'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Edit Salary Structure for :name', ['name' => $employee->display_name]) }}</h1>
            <p class="text-muted mb-0">{{ __('Adjust components or effective dates for this salary structure.') }}</p>
        </div>
        <a href="{{ route('employees.salary-structures.show', [$employee, $salaryStructure]) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('Back to structure') }}</span>
        </a>
    </div>

    @php
    $currencyOptions = payrollCurrencies();
    if (empty($currencyOptions)) {
    $currencyOptions = ['EGP' => 'EGP'];
    }
    $defaultCurrency = array_key_first($currencyOptions) ?? 'EGP';
    @endphp

    <form method="POST" action="{{ route('employees.salary-structures.update', [$employee, $salaryStructure]) }}" class="row g-3">
        @csrf
        @method('PUT')

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
                                <option value="{{ $value }}" {{ old('currency', $salaryStructure->currency ?? $defaultCurrency) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="effective_from" class="form-label">{{ __('Effective From') }}</label>
                            <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', optional($salaryStructure->effective_from)->format('Y-m-d')) }}" class="form-control @error('effective_from') is-invalid @enderror" required>
                            @error('effective_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="effective_to" class="form-label">{{ __('Effective To') }}</label>
                            <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to', optional($salaryStructure->effective_to)->format('Y-m-d')) }}" class="form-control @error('effective_to') is-invalid @enderror">
                            @error('effective_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" maxlength="500">{{ old('notes', $salaryStructure->notes) }}</textarea>
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
                    <span class="text-muted small">{{ __('Order components to control calculation priority.') }}</span>
                </div>
                <div class="card-body">
                    @forelse($salaryStructure->structureComponents->sortBy('priority_order') as $component)
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $component->component->name ?? __('Unknown Component') }}</strong>
                                <div class="text-muted small">{{ $component->component->code ?? '—' }}</div>
                            </div>
                            <div class="d-flex gap-2">
                                <input type="hidden" name="components[{{ $component->component_id }}][component_id]" value="{{ $component->component_id }}">
                                <input type="number" step="0.01" class="form-control form-control-sm" name="components[{{ $component->component_id }}][value_numeric]" value="{{ old("components.{$component->component_id}.value_numeric", $component->value_numeric) }}" placeholder="{{ __('Amount') }}">
                                <input type="text" class="form-control form-control-sm" name="components[{{ $component->component_id }}][formula_expr]" value="{{ old("components.{$component->component_id}.formula_expr", $component->formula_expr) }}" placeholder="{{ __('Formula') }}">
                                <input type="number" class="form-control form-control-sm" name="components[{{ $component->component_id }}][priority_order]" value="{{ old("components.{$component->component_id}.priority_order", $component->priority_order) }}" min="1" max="999" placeholder="{{ __('Priority') }}">
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted mb-0">{{ __('No components available for editing.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('employees.salary-structures.show', [$employee, $salaryStructure]) }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="ms-1">{{ __('Save Changes') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection