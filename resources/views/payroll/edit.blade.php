@extends('layouts.app')

@section('title', __('Edit Payroll Run'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Edit Payroll Run') }}</h1>
            <p class="text-muted mb-0">{{ __('Update the pay period details before recalculating or posting.') }}</p>
        </div>
        <a href="{{ route('payroll.show', $payrollRun) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('Back to run') }}</span>
        </a>
    </div>

    @php
    $currencyOptions = $currencies ?? payrollCurrencies();
    if (empty($currencyOptions)) {
    $currencyOptions = ['EGP' => 'EGP'];
    }
    @endphp

    <form method="POST" action="{{ route('payroll.update', $payrollRun) }}" class="row g-3">
        @csrf
        @method('PUT')

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
                                value="{{ old('title', $payrollRun->title) }}"
                                class="form-control @error('title') is-invalid @enderror"
                                maxlength="255"
                                required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                maxlength="500">{{ old('description', $payrollRun->description) }}</textarea>
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
                                value="{{ old('pay_period_start', optional($payrollRun->pay_period_start)->format('Y-m-d')) }}"
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
                                value="{{ old('pay_period_end', optional($payrollRun->pay_period_end)->format('Y-m-d')) }}"
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
                                value="{{ old('pay_date', optional($payrollRun->pay_date)->format('Y-m-d')) }}"
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
                                <option value="{{ $value }}" {{ old('currency', $payrollRun->currency) === $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                maxlength="1000">{{ old('notes', $payrollRun->notes) }}</textarea>
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
                                    {{ old('approval_required', $payrollRun->approval_required) ? 'checked' : '' }}>
                                <label class="form-check-label" for="approval_required">
                                    {{ __('Require approval before posting this payroll run') }}
                                </label>
                                @error('approval_required')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('payroll.show', $payrollRun) }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span class="ms-1">{{ __('Save Changes') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection