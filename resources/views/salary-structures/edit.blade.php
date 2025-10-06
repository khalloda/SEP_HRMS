@extends('layouts.app')

@section('title', __('hrms.salary_structure.edit'))

@section('content')
<div class="container-fluid" x-data="salaryStructureForm()">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('hrms.salary_structure.edit') }} — {{ $employee->display_name }}</h1>
            <p class="text-muted mb-0">{{ __('hrms.salary_structure_edit_help') }}</p>
        </div>
        <a href="{{ route('employees.salary-structures.show', [$employee, $salaryStructure]) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('common.back') }}</span>
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
                    <h5 class="mb-0">{{ __('hrms.salary_structure_details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="currency" class="form-label">{{ __('hrms.salary_structure.currency') }}</label>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                @foreach($currencyOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('currency', $salaryStructure->currency ?? $defaultCurrency) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('hrms.currencies_from_config_hint') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_from" class="form-label">{{ __('hrms.salary_structure.effective_from') }}</label>
                            <input type="date" id="effective_from" name="effective_from" value="{{ old('effective_from', optional($salaryStructure->effective_from)->format('Y-m-d')) }}" class="form-control @error('effective_from') is-invalid @enderror" required>
                            @error('effective_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('hrms.updating_start_date_affects_history') }}</div>
                        </div>
                        <div class="col-md-4">
                            <label for="effective_to" class="form-label">{{ __('hrms.salary_structure.effective_to') }}</label>
                            <input type="date" id="effective_to" name="effective_to" value="{{ old('effective_to', optional($salaryStructure->effective_to)->format('Y-m-d')) }}" class="form-control @error('effective_to') is-invalid @enderror">
                            @error('effective_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('hrms.set_expiration_or_blank') }}</div>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('hrms.salary_structure.notes') }}</label>
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
                    <h5 class="mb-0">{{ __('hrms.salary_structure.components') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" x-on:click="addRow()">
                        <i class="fas fa-plus-circle"></i>
                        <span class="ms-1">{{ __('hrms.add_component') }}</span>
                    </button>
                </div>
                <div class="card-body">
                    @error('components')
                    <div class="alert alert-danger small">{{ $message }}</div>
                    @enderror
                    <template x-if="rows.length === 0">
                        <p class="text-muted mb-0">{{ __('hrms.add_at_least_one_component') }}</p>
                    </template>

                    <div class="list-group" x-ref="sortable">
                        <template x-for="(row, index) in rows" :key="row.uuid ?? `row-${index}`">
                            <div class="list-group-item border rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="drag-handle text-muted" title="{{ __('hrms.drag_to_reorder') }}">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="form-label small text-muted">{{ __('hrms.salary_structure.component_value') }}</label>
                                        <select class="form-select form-select-sm" :name="`components[${rows[index]?.uuid ?? 'row-' + index}][component_id]`" x-model="row.component">
                                            <option value="" disabled>{{ __('hrms.select_component') }}</option>
                                            <template x-for="group in componentGroups" :key="group.type">
                                                <optgroup :label="group.label">
                                                    <template x-for="component in group.items" :key="component.id">
                                                        <option :value="component.id" x-text="component.label" :selected="component.id === row.component"></option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label small text-muted">{{ __('hrms.salary_structure.component_value') }}</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" :name="`components[${rows[index]?.uuid ?? 'row-' + index}][value_numeric]`" x-model="row.amount" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="form-label small text-muted d-flex align-items-center gap-1">
                                            <span>{{ __('hrms.salary_structure.formula_expression') }}</span>
                                            <span class="badge bg-info text-dark" x-show="$root.useConditionalsFlag" x-cloak>IF</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" :name="`components[${rows[index]?.uuid ?? 'row-' + index}][formula_expr]`" x-model="row.formula" placeholder="{{ __('hrms.optional_formula_expression') }}">
                                        <div class="form-text" x-show="$root.useConditionalsFlag" x-cloak>
                                            {{ __('hrms.conditional_if_help') }}
                                        </div>
                                    </div>
                                    <input type="hidden" :name="`components[${rows[index]?.uuid ?? 'row-' + index}][priority_order]`" x-model="row.priority">
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
                    <div class="form-text">{{ __('hrms.reorder_priority_help') }}</div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('employees.salary-structures.show', [$employee, $salaryStructure]) }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="ms-1">{{ __('common.update') }}</span>
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
            useConditionalsFlag: @json(config('payroll.use_safe_engine_conditionals'))
        };
    }

    function componentRepeater(componentGroups, seededComponents) {
        const uuid = () => {
            if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                return window.crypto.randomUUID();
            }

            return `uuid-${Math.random().toString(36).slice(2, 11)}`;
        };

        const newRow = () => ({
            uuid: uuid(),
            component: '',
            amount: '',
            formula: '',
            priority: '',
        });

        const normaliseSeed = (data) => {
            if (Array.isArray(data)) {
                return data;
            }

            if (data && typeof data === 'object') {
                return Object.values(data);
            }

            return [];
        };

        const parsedGroups = typeof componentGroups === 'string' ? JSON.parse(componentGroups) : componentGroups;
        const normalisedGroups = Object.entries(parsedGroups || {}).map(([type, items]) => ({
            type,
            label: type.charAt(0).toUpperCase() + type.slice(1),
            items: items.map(item => ({
                id: String(item.id),
                label: `${item.code} · ${item.name}`,
            })),
        }));

        const seedRows = normaliseSeed(seededComponents).map((component, index) => ({
            uuid: component.row_key ?? component.uuid ?? `row-${index}`,
            component: component.component_id !== undefined && component.component_id !== null ?
                String(component.component_id) : '',
            amount: component.value_numeric ?? component.amount ?? '',
            formula: component.formula_expr ?? component.formula ?? '',
            priority: component.priority_order ?? component.priority ?? '',
        }));

        if (seedRows.length === 0) {
            seedRows.push(newRow());
        }

        const reorder = collection => {
            collection.forEach((row, idx) => {
                row.priority = idx + 1;
            });
        };

        reorder(seedRows);

        return {
            componentGroups: normalisedGroups,
            rows: seedRows,
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
                        hrms.add_at_least_one_component ') }}'
                    });
                    return;
                }
                event.target.submit();
            },
            addRow() {
                this.rows.push(newRow());
                reorder(this.rows);
            },
            removeRow(index) {
                this.rows.splice(index, 1);
                if (this.rows.length === 0) {
                    this.rows.push(newRow());
                }
                reorder(this.rows);
            },
        };
    }
</script>
@endpush