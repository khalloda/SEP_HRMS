@extends('layouts.app')

@section('title', __('Payslips'))

@section('content')
@php
    $isRtl = app()->getLocale() === 'ar';
    $currencyFor = static fn($payslip) => $payslip->currency ?? optional($payslip->payrollRun)->currency ?? '';
@endphp
<div class="container-fluid"
    @include('payroll.partials.status-banners') @if($isRtl) dir="rtl" @endif>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Payslips') }}</h1>
            @if($employee)
                <p class="text-muted mb-0">{{ __('Employee:') }} {{ $employee->full_name ?? $employee->first_name }}</p>
            @endif
        </div>
        <div class="text-muted small">
            {{ __('Updated at:') }} {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <form method="GET" class="card shadow-sm mb-4 border-0">
        @php($resetUrl = $employee ? route('employees.payslips.index', $employee) : route('payslips.index'))
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="payroll_run_id" class="form-label">{{ __('Payroll Run') }}</label>
                    <select name="payroll_run_id" id="payroll_run_id" class="form-select">
                        <option value="">{{ __('All runs') }}</option>
                        @foreach($payrollRuns as $run)
                            <option value="{{ $run->id }}" {{ request('payroll_run_id') == $run->id ? 'selected' : '' }}>
                                {{ $run->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">{{ __('All statuses') }}</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label">{{ __('Year') }}</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">{{ __('Any year') }}</option>
                        @foreach($yearOptions as $yearOption)
                            <option value="{{ $yearOption }}" {{ request('year') == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="month" class="form-label">{{ __('Month') }}</label>
                    <select name="month" id="month" class="form-select">
                        <option value="">{{ __('Any month') }}</option>
                        @foreach($monthOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('month') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Employee name or code') }}">
                        <button class="btn btn-primary" type="submit">{{ __('Apply') }}</button>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    {{ __('Showing :count payslips', ['count' => $payslips->total()]) }}
                </div>
                <div>
                    <a href="{{ $resetUrl }}" class="btn btn-link text-decoration-none">{{ __('Reset filters') }}</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Payroll Run') }}</th>
                            <th class="text-end">{{ __('Gross Pay') }}</th>
                            <th class="text-end">{{ __('Deductions') }}</th>
                            <th class="text-end">{{ __('Net Pay') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Pay Date') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payslips as $payslip)
                            @php($canViewAmounts = $payslip->canViewNetGrossBy(auth()->user()))
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $payslip->employee_name }}</div>
                                    <div class="text-muted small">{{ $payslip->employee_code }}</div>
                                </td>
                                <td>{{ optional(optional($payslip->employee)->department)->name ?? 'N/A' }}</td>
                                <td>{{ optional($payslip->payrollRun)->title ?? 'N/A' }}</td>
                                <td class="text-end">
                                    @if($canViewAmounts)
                                        {{ number_format($payslip->gross_pay, 2) }} {{ $currencyFor($payslip) }}
                                    @else
                                        <span class="text-muted">{{ __('Restricted') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($canViewAmounts)
                                        {{ number_format($payslip->total_deductions, 2) }} {{ $currencyFor($payslip) }}
                                    @else
                                        <span class="text-muted">{{ __('Restricted') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($canViewAmounts)
                                        {{ number_format($payslip->net_pay, 2) }} {{ $currencyFor($payslip) }}
                                    @else
                                        <span class="text-muted">{{ __('Restricted') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ \App\Models\Payslip::STATUSES[$payslip->status] ?? \Illuminate\Support\Str::title($payslip->status) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ optional($payslip->pay_date)->format('Y-m-d') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('payslips.show', $payslip) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    {{ __('No payslips found for the selected criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payslips->hasPages())
            <div class="card-footer border-0">
                {{ $payslips->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
