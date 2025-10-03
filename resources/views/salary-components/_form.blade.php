@php
/** @var \App\Models\SalaryComponent|null $salaryComponent */
$salaryComponent = $salaryComponent ?? null;
$selectedRoles = old('visible_to_roles', $salaryComponent?->visible_to_roles ?? []);
@endphp

<div class="row g-4">
    <div class="col-md-4">
        <label for="code" class="form-label">{{ __('Component Code') }}</label>
        <input
            type="text"
            name="code"
            id="code"
            class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $salaryComponent?->code) }}"
            maxlength="40"
            required>
        <div class="form-text">{{ __('Use uppercase codes (e.g. BASIC_SALARY).') }}</div>
        @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="name_en" class="form-label">{{ __('Name (English)') }}</label>
        <input
            type="text"
            name="name_en"
            id="name_en"
            class="form-control @error('name_en') is-invalid @enderror"
            value="{{ old('name_en', $salaryComponent?->name_en) }}"
            maxlength="120"
            required>
        @error('name_en')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="name_ar" class="form-label">{{ __('Name (Arabic)') }}</label>
        <input
            type="text"
            name="name_ar"
            id="name_ar"
            class="form-control @error('name_ar') is-invalid @enderror"
            value="{{ old('name_ar', $salaryComponent?->name_ar) }}"
            maxlength="120"
            required>
        @error('name_ar')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="comp_type" class="form-label">{{ __('Component Type') }}</label>
        <select
            name="comp_type"
            id="comp_type"
            class="form-select @error('comp_type') is-invalid @enderror"
            required>
            @foreach($componentTypes as $value => $label)
            <option value="{{ $value }}" {{ old('comp_type', $salaryComponent?->comp_type) === $value ? 'selected' : '' }}>
                {{ __($label) }}
            </option>
            @endforeach
        </select>
        @error('comp_type')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="calc_mode" class="form-label">{{ __('Calculation Mode') }}</label>
        <select
            name="calc_mode"
            id="calc_mode"
            class="form-select @error('calc_mode') is-invalid @enderror"
            required>
            @foreach($calcModes as $value => $label)
            <option value="{{ $value }}" {{ old('calc_mode', $salaryComponent?->calc_mode) === $value ? 'selected' : '' }}>
                {{ __($label) }}
            </option>
            @endforeach
        </select>
        @error('calc_mode')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">{{ __('Formula components should reference other codes (e.g. BASIC_SALARY * 0.1 ).') }}</div>
    </div>

    <div class="col-md-4">
        <label for="priority_order" class="form-label">{{ __('Priority Order') }}</label>
        <input
            type="number"
            name="priority_order"
            id="priority_order"
            class="form-control @error('priority_order') is-invalid @enderror"
            value="{{ old('priority_order', $salaryComponent?->priority_order ?? 0) }}"
            min="0"
            max="999"
            required>
        @error('priority_order')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('Taxable') }}</label>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="taxable"
                name="taxable"
                value="1"
                {{ old('taxable', $salaryComponent?->taxable) ? 'checked' : '' }}>
            <label class="form-check-label" for="taxable">{{ __('Enable if the component should be taxed') }}</label>
        </div>
        @error('taxable')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">{{ __('Visible To Roles') }}</label>
        <div class="row g-2">
            @foreach($availableRoles as $roleKey => $roleLabel)
            <div class="col-md-6">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="visible_to_roles[]"
                        id="role_{{ $roleKey }}"
                        value="{{ $roleKey }}"
                        {{ in_array($roleKey, $selectedRoles, true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="role_{{ $roleKey }}">{{ __($roleLabel) }}</label>
                </div>
            </div>
            @endforeach
        </div>
        <div class="form-text">{{ __('Leave unchecked to allow all roles to view this component.') }}</div>
        @error('visible_to_roles')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>