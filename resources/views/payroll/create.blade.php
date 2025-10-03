@extends('layouts.app')

@section('title', __('Create Payroll Run'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Create Payroll Run') }}</h1>
            <p class="text-muted mb-0">{{ __('Define the upcoming payroll period and optional approval requirements.') }}</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('Back to list') }}</span>
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <h6 class="fw-semibold mb-2">{{ __('Please correct the highlighted fields below.') }}</h6>
        <ul class="mb-0 small">
            @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @php
        $currencyOptions = $currencies ?? payrollCurrencies();
        if (empty($currencyOptions)) {
            $currencyOptions = ['EGP' => 'EGP'];
        }
    $defaultCurrency = array_key_first($currencyOptions) ?? 'EGP';
    $suggestedStartValue = optional($suggestedStart)->format('Y-m-d');
    $suggestedEndValue = optional($suggestedEnd)->format('Y-m-d');
    $suggestedPayDateValue = optional($suggestedPayDate)->format('Y-m-d');
    @endphp

    <form method="POST" action="{{ route('payroll.store') }}" class="row g-3">
        @csrf

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Payroll Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="{{ __('Example: January 2026 Payroll') }}"
                                maxlength="255">
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Leave blank to auto-generate based on the pay period.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                maxlength="500"
                                placeholder="{{ __('Optional summary visible to payroll approvers.') }}">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="pay_period_start" class="form-label">{{ __('Pay Period Start') }}</label>
                            <input
                                type="date"
                                id="pay_period_start"
                                name="pay_period_start"
                                value="{{ old('pay_period_start', $suggestedStartValue) }}"
                                class="form-control @error('pay_period_start') is-invalid @enderror"
                                required>
                            @error('pay_period_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="pay_period_end" class="form-label">{{ __('Pay Period End') }}</label>
                            <input
                                type="date"
                                id="pay_period_end"
                                name="pay_period_end"
                                value="{{ old('pay_period_end', $suggestedEndValue) }}"
                                class="form-control @error('pay_period_end') is-invalid @enderror"
                                required>
                            @error('pay_period_end')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="pay_date" class="form-label">{{ __('Pay Date') }}</label>
                            <input
                                type="date"
                                id="pay_date"
                                name="pay_date"
                                value="{{ old('pay_date', $suggestedPayDateValue) }}"
                                class="form-control @error('pay_date') is-invalid @enderror"
                                required>
                            @error('pay_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="currency" class="form-label">{{ __('Currency') }}</label>
                            <select
                                id="currency"
                                name="currency"
                                class="form-select @error('currency') is-invalid @enderror"
                                required>
                                @foreach($currencyOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('currency', $defaultCurrency) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label for="notes" class="form-label">{{ __('Internal Notes') }}</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                class="form-control @error('notes') is-invalid @enderror"
                                maxlength="1000"
                                placeholder="{{ __('Optional notes visible to payroll managers only.') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    id="approval_required"
                                    name="approval_required"
                                    value="1"
                                    class="form-check-input @error('approval_required') is-invalid @enderror"
                                    {{ old('approval_required', false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="approval_required">
                                    {{ __('Require approval before posting this payroll run') }}
                                </label>
                                @error('approval_required')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('When enabled, the run must be approved after locking and before posting.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="ms-1">{{ __('Create Payroll Run') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection