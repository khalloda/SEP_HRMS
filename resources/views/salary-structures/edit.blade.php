@extends('layouts.app')

@section('title', __('Edit Salary Structure'))

@section('content')
<div class="container-fluid" x-data="salaryStructureForm()">
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

    <form method="POST" action="{{ route('employees.salary-structures.update', [$employee, $salaryStructure]) }}" class="row g-3" x-on:submit.prevent="handleSubmit($event)">
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
                            <div class="form-text">{{ __('Currencies come from payroll configuration to keep parity across modules.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_from" class="form-label">{{ __('Effective From') }}</label>
                            <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', optional($salaryStructure->effective_from)->format('Y-m-d')) }}" class="form-control @error('effective_from') is-invalid @enderror" required>
                            @error('effective_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Updating the start date will affect historic payroll calculations.') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_to" class="form-label">{{ __('Effective To') }}</label>
                            <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to', optional($salaryStructure->effective_to)->format('Y-m-d')) }}" class="form-control @error('effective_to') is-invalid @enderror">
                            @error('effective_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Set a date to schedule expiration or leave blank to keep active.') }}</div>
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
            <div class="card"
                x-data="componentRepeater(@js($components), @js(old('components', $salaryStructure->structureComponents->mapWithKeys(fn ($component) => [
                     (string) $component->component_id => [
                         'component_id' => $component->component_id,
                         'value_numeric' => $component->value_numeric,
                         'formula_expr' => $component->formula_expr,
                         'priority_order' => $component->priority_order,
                     ],
                 ])->toArray())))"
                x-init="init()"
                data-salary-structure-repeater>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Components') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" x-on:click="addRow()">
                        <i class="fas fa-plus-circle"></i>
                        <span class="ms-1">{{ __('Add Component') }}</span>
                    </button>
                </div>
                <div class="card-body">
                    @error('components')
                    <div class="alert alert-danger small">{{ $message }}</div>
                    @enderror
                    <template x-if="rows.length === 0">
                        <p class="text-muted mb-0">{{ __('Add at least one component to keep this structure active.') }}</p>
                    </template>

                    <div class="list-group" x-ref="sortable">
                        <template x-for="(row, index) in rows" :key="row.uuid">
                            <div class="list-group-item border rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="drag-handle text-muted" title="{{ __('Drag to reorder') }}">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
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
                                        <label class="form-label small text-muted d-flex align-items-center gap-1">
                                            <span>{{ __('Formula') }}</span>
                                            <span class="badge bg-info text-dark" x-show="config.useConditionalsFlag" x-cloak>IF</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" :name="`components[${row.uuid}][formula_expr]`" x-model="row.formula" placeholder="{{ __('Optional formula expression') }}">
                                        <div class="form-text" x-show="config.useConditionalsFlag" x-cloak>
                                            {{ __('Conditional formulas support IF statements, e.g. IF(BASIC_SALARY>12000, BASIC_SALARY*0.12, BASIC_SALARY*0.05). Nested IFs work when the safe engine flag is enabled.') }}
                                        </div>
                                    </div>
                                    <input type="hidden" :name="`components[${row.uuid}][priority_order]`" x-model="row.priority">
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-4" x-on:click="removeRow(index)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <input type="hidden" name="components_present" x-bind:value="rows.length">
                </div>
                <div class="card-footer">
                    <div class="form-text">{{ __('Reorder priorities to control calculation order. Components with higher priority run later.') }}</div>
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

@push('scripts')
<script>
    function salaryStructureForm() {
        return {
            config: {
                useConditionalsFlag: {{ config('payroll.use_safe_engine_conditionals') ? 'true' : 'false' }}
            }
        };
    }

    function componentRepeater(componentGroups, seededComponents) {
        const uuid = () => {
            if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                return window.crypto.randomUUID();
            }

            return `uuid-${Math.random().toString(36).slice(2, 11)}`;
        };

        const parsedGroups = typeof componentGroups === 'string' ? JSON.parse(componentGroups) : componentGroups;
        const normalisedGroups = Object.entries(parsedGroups || {}).map(([type, items]) => ({
            type,
            label: type.charAt(0).toUpperCase() + type.slice(1),
            items: items.map(item => ({
                id: item.id,
                label: `${item.code} · ${item.name}`,
            })),
        }));

        const parsedSeeded = typeof seededComponents === 'string' ? JSON.parse(seededComponents) : seededComponents;
        const initialRows = Object.entries(parsedSeeded || {}).map(([seed, component]) => ({
            uuid: seed !== '0' ? seed : uuid(),
            component: component.component_id ?? '',
            amount: component.value_numeric ?? '',
            formula: component.formula_expr ?? '',
            priority: component.priority_order ?? '',
        }));

        const ensureRow = collection => {
            if (collection.length === 0) {
                collection.push({
                    uuid: uuid(),
                    component: '',
                    amount: '',
                    formula: '',
                    priority: '',
                });
            }
            return collection;
        };

        const reorder = collection => {
            collection.forEach((row, idx) => {
                row.priority = idx + 1;
            });
        };

        const rows = ensureRow(initialRows);
        reorder(rows);

        return {
            componentGroups: normalisedGroups,
            rows,
            init() {
                this.$nextTick(() => {
                    if (this.$refs.sortable && window.Sortable) {
                        window.Sortable.create(this.$refs.sortable, {
                            handle: '.drag-handle',
                            animation: 150,
                            onEnd: () => reorder(this.rows),
                        });
                    }
                });
            },
            handleSubmit(event) {
                reorder(this.rows);
                if (this.rows.length === 0 || this.rows.every(row => !row.component)) {
                    event.preventDefault();
                    this.addRow();
                    this.$dispatch('flash', {
                        type: 'danger',
                        message: '{{ __('
                        Add at least one valid component before saving.
                        ') }}'
                    });
                    return;
                }
                event.target.submit();
            },
            addRow() {
                this.rows.push({
                    uuid: uuid(),
                    component: '',
                    amount: '',
                    formula: '',
                    priority: '',
                });
                reorder(this.rows);
            },
            removeRow(index) {
                this.rows.splice(index, 1);
                ensureRow(this.rows);
                reorder(this.rows);
            },
        };
    }
</script>
@endpush