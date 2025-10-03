@extends('layouts.app')

@section('title', __('Create Salary Structure'))

@section('content')
<div class="container-fluid" x-data="salaryStructureForm()">
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
                            <div class="form-text">{{ __('Currencies come from the payroll configuration to keep parity across modules.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_from" class="form-label">{{ __('Effective From') }}</label>
                            <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from') }}" class="form-control @error('effective_from') is-invalid @enderror" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('New structures must start today or later.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_to" class="form-label">{{ __('Effective To') }}</label>
                            <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to') }}" class="form-control @error('effective_to') is-invalid @enderror">
                            @error('effective_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Leave blank to keep the structure active until replaced.') }}</div>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Optional memo visible to payroll managers only.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card" x-data="componentRepeater({{ $components->toJson() }}, {{ json_encode(old('components', [])) }})">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Components') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" x-on:click="addRow()">
                        <i class="fas fa-plus-circle"></i>
                        <span class="ms-1">{{ __('Add Component') }}</span>
                    </button>
                </div>
                <div class="card-body">
                    <template x-if="rows.length === 0">
                        <p class="text-muted mb-0">{{ __('Add at least one component to activate this structure.') }}</p>
                    </template>

                    <template x-for="(row, index) in rows" :key="row.uuid">
                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <label class="form-label small text-muted">{{ __('Component') }}</label>
                                    <select class="form-select form-select-sm" :name="`components[${row.uuid}][component_id]`" x-model="row.component">
                                        <option value="" disabled>{{ __('Select component') }}</option>
                                        <template x-for="group in componentGroups" :key="group.type">
                                            <optgroup :label="group.label">
                                                <template x-for="component in group.items" :key="component.id">
                                                    <option :value="component.id" x-text="component.label"></option>
                                                </template>
                                            </optgroup>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small text-muted">{{ __('Amount') }}</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" :name="`components[${row.uuid}][value_numeric]`" x-model="row.amount" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">{{ __('Formula') }}</label>
                                    <input type="text" class="form-control form-control-sm" :name="`components[${row.uuid}][formula_expr]`" x-model="row.formula" placeholder="{{ __('Optional formula expression') }}">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">{{ __('Priority') }}</label>
                                    <input type="number" min="1" max="999" class="form-control form-control-sm" :name="`components[${row.uuid}][priority_order]`" x-model="row.priority" placeholder="1">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-4" x-on:click="removeRow(index)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>

                    <input type="hidden" name="components_present" x-bind:value="rows.length">
                </div>
                <div class="card-footer">
                    <div class="form-text">{{ __('Set priority order to control calculation sequencing. Higher numbers run later.') }}</div>
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

@push('scripts')
<script>
    function salaryStructureForm() {
        return {
            // wrapper for potential shared helpers
        };
    }

    function componentRepeater(componentGroups, oldComponents) {
        const normalisedGroups = Object.entries(componentGroups).map(([type, items]) => ({
            type,
            label: type.charAt(0).toUpperCase() + type.slice(1),
            items: items.map(item => ({
                id: item.id,
                label: `${item.code} · ${item.name}`,
            })),
        }));

        const oldRows = Object.values(oldComponents || {}).map(component => ({
            uuid: crypto.randomUUID(),
            component: component.component_id ?? '',
            amount: component.value_numeric ?? '',
            formula: component.formula_expr ?? '',
            priority: component.priority_order ?? '',
        }));

        if (oldRows.length === 0) {
            oldRows.push({ uuid: crypto.randomUUID(), component: '', amount: '', formula: '', priority: '' });
        }

        return {
            componentGroups: normalisedGroups,
            rows: oldRows,
            addRow() {
                this.rows.push({ uuid: crypto.randomUUID(), component: '', amount: '', formula: '', priority: '' });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            },
        };
    }
</script>
@endpush