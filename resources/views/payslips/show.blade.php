@extends('layouts.app')

@section('title', $payslip->employee_name)

@section('content')
@php
    $isRtl = app()->getLocale() === 'ar';
    $periodStart = $payslip->pay_period_start ? $payslip->pay_period_start->format('Y-m-d') : 'N/A';
    $periodEnd = $payslip->pay_period_end ? $payslip->pay_period_end->format('Y-m-d') : 'N/A';
    $statusLabel = \App\Models\Payslip::STATUSES[$payslip->status] ?? \Illuminate\Support\Str::title($payslip->status);
    $departmentName = optional(optional($payslip->employee)->department)->name ?? 'N/A';
    $positionName = optional(optional($payslip->employee)->position)->name ?? 'N/A';
    $currency = $payslip->currency ?? optional($payslip->payrollRun)->currency ?? '';
@endphp
<div class="container-fluid" @if($isRtl) dir="rtl" @endif>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start align-items-stretch gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $payslip->employee_name }}</h1>
            <p class="text-muted mb-0">
                {{ __('Payslip for :period', ['period' => $periodStart . ' - ' . $periodEnd]) }}
            </p>
        </div>
        <div class="d-flex flex-column align-items-md-end align-items-start gap-2">
            <span class="badge bg-light text-dark border">{{ $statusLabel }}</span>
            <a href="{{ url()->previous() === url()->current() ? route('payslips.index') : url()->previous() }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to payslips') }}</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">{{ __('Employee Details') }}</div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">{{ __('Employee Code') }}</dt>
                        <dd class="col-7">{{ $payslip->employee_code }}</dd>
                        <dt class="col-5 text-muted">{{ __('Department') }}</dt>
                        <dd class="col-7">{{ $departmentName }}</dd>
                        <dt class="col-5 text-muted">{{ __('Position') }}</dt>
                        <dd class="col-7">{{ $positionName }}</dd>
                        <dt class="col-5 text-muted">{{ __('Payroll Run') }}</dt>
                        <dd class="col-7">{{ optional($payslip->payrollRun)->title ?? 'N/A' }}</dd>
                        <dt class="col-5 text-muted">{{ __('Pay Date') }}</dt>
                        <dd class="col-7">{{ $payslip->pay_date ? $payslip->pay_date->format('Y-m-d') : 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">{{ __('Compensation Summary') }}</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('Gross Pay') }}</span>
                        <span class="fw-semibold">
                            @if($canViewNetGross)
                                {{ number_format($payslip->gross_pay, 2) }} {{ $currency }}
                            @else
                                {{ __('Restricted') }}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('Total Deductions') }}</span>
                        <span class="fw-semibold text-danger">
                            @if($canViewNetGross)
                                {{ number_format($payslip->total_deductions, 2) }} {{ $currency }}
                            @else
                                {{ __('Restricted') }}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="text-muted">{{ __('Net Pay') }}</span>
                        <span class="fw-bold text-success">
                            @if($canViewNetGross)
                                {{ number_format($payslip->net_pay, 2) }} {{ $currency }}
                            @else
                                {{ __('Restricted') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">{{ __('Generation Details') }}</div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">{{ __('Status') }}</dt>
                        <dd class="col-7">{{ $statusLabel }}</dd>
                        <dt class="col-5 text-muted">{{ __('Generated At') }}</dt>
                        <dd class="col-7">{{ $payslip->created_at ? $payslip->created_at->format('Y-m-d H:i') : 'N/A' }}</dd>
                        <dt class="col-5 text-muted">{{ __('PDF Generated At') }}</dt>
                        <dd class="col-7">{{ $payslip->pdf_generated_at ? $payslip->pdf_generated_at->format('Y-m-d H:i') : 'N/A' }}</dd>
                        <dt class="col-5 text-muted">{{ __('Currency') }}</dt>
                        <dd class="col-7">{{ $currency !== '' ? $currency : 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">{{ __('Earnings') }}</div>
                <div class="card-body p-0">
                    @include('payslips.partials._lines', [
                        'lines' => $earnings,
                        'currency' => $currency,
                        'showAmounts' => $canViewNetGross,
                        'emptyMessage' => __('No earnings components.')
                    ])
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">{{ __('Deductions') }}</div>
                <div class="card-body p-0">
                    @include('payslips.partials._lines', [
                        'lines' => $deductions,
                        'currency' => $currency,
                        'showAmounts' => $canViewNetGross,
                        'emptyMessage' => __('No deduction components.')
                    ])
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">{{ __('Additional Information') }}</div>
                <div class="card-body p-0">
                    @include('payslips.partials._lines', [
                        'lines' => $infoComponents,
                        'currency' => $currency,
                        'showAmounts' => $canViewNetGross,
                        'emptyMessage' => __('No additional information recorded.')
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
