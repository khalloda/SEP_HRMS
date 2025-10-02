@extends('layouts.app')

@section('title', $payrollRun->title)

@section('content')
<div class="container-fluid">
    @php
        $mergedValidationIssues = $validationIssues ?? [];
    @endphp


    @include('payroll.partials.status-banners', [
        'successMessage' => $successMessage ?? null,
        'errorMessage' => $errorMessage ?? null,
        'calculationReference' => $calculationReference ?? null,
        'calculationJob' => $calculationJob ?? null,
        'calculationResults' => $calculationResults ?? null,
        'calculationErrors' => $calculationErrors ?? null,
    ])

    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1">{{ $payrollRun->title }}</h1>
            <p class="text-muted mb-0">
                {{ optional($payrollRun->pay_period_start)->format('d M Y') ?? __('TBD') }} -
                {{ optional($payrollRun->pay_period_end)->format('d M Y') ?? __('TBD') }} |
                {{ __('Pay Date') }}: {{ optional($payrollRun->pay_date)->format('d M Y') ?? __('TBD') }}
            </p>
            @if($payrollRun->description)
                <p class="mt-2 mb-0">{{ $payrollRun->description }}</p>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge bg-secondary text-uppercase px-3 py-2">{{ \Illuminate\Support\Str::headline($payrollRun->status) }}</span>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
                <span class="ms-1">{{ __('Back to list') }}</span>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Totals') }}</h6>
                    <p class="mb-1"><strong>{{ number_format($payrollRun->total_employees) }}</strong> {{ __('Employees') }}</p>
                    <p class="mb-1"><strong>{{ number_format($payrollRun->total_gross, 2) }}</strong> {{ $payrollRun->currency }} {{ __('Gross') }}</p>
                    <p class="mb-0"><strong>{{ number_format($payrollRun->total_net, 2) }}</strong> {{ $payrollRun->currency }} {{ __('Net') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Lifecycle') }}</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li>{{ __('Created by') }}: {{ $payrollRun->creator?->name ?? __('Unknown') }}</li>
                        <li>{{ __('Locked by') }}: {{ $payrollRun->locker?->name ?? __('N/A') }}</li>
                        <li>{{ __('Approved by') }}: {{ $payrollRun->approver?->name ?? __('N/A') }}</li>
                        <li>{{ __('Posted by') }}: {{ $payrollRun->poster?->name ?? __('N/A') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Actions') }}</h6>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @can('calculate', $payrollRun)
                            <form method="POST" action="{{ route('payroll.calculate', ['payrollRun' => $payrollRun]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-calculator"></i>
                                    <span class="ms-1">{{ __('Calculate') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('lock', $payrollRun)
                            <form method="POST" action="{{ route('payroll.lock', ['payrollRun' => $payrollRun]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-lock"></i>
                                    <span class="ms-1">{{ __('Lock') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('unlock', $payrollRun)
                            <form method="POST" action="{{ route('payroll.unlock', ['payrollRun' => $payrollRun]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-unlock"></i>
                                    <span class="ms-1">{{ __('Unlock') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('approve', $payrollRun)
                            <form method="POST" action="{{ route('payroll.approve', ['payrollRun' => $payrollRun]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-check"></i>
                                    <span class="ms-1">{{ __('Approve') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('post', $payrollRun)
                            <form method="POST" action="{{ route('payroll.post', ['payrollRun' => $payrollRun]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span class="ms-1">{{ __('Post') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('delete', $payrollRun)
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelPayrollModal-{{ $payrollRun->id }}">
                                <i class="fas fa-ban"></i>
                                <span class="ms-1">{{ __('Cancel') }}</span>
                            </button>
                        @endcan
                    </div>
                    <div class="mt-auto small text-muted pt-3">
                        {{ __('Available actions adjust dynamically based on the payroll status and your permissions.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="cancelPayrollModal-{{ $payrollRun->id }}" tabindex="-1" aria-labelledby="cancelPayrollModalLabel-{{ $payrollRun->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelPayrollModalLabel-{{ $payrollRun->id }}">{{ __('Cancel Payroll Run') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <form method="POST" action="{{ route('payroll.cancel', ['payrollRun' => $payrollRun]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancel-reason-{{ $payrollRun->id }}" class="form-label">{{ __('Cancellation Reason') }}</label>
                            <textarea id="cancel-reason-{{ $payrollRun->id }}" name="cancellation_reason" class="form-control" rows="3" required placeholder="{{ __('Provide the reason for cancellation...') }}"></textarea>
                        </div>
                        <p class="small text-muted mb-0">{{ __('Cancelling reverts the run to a historical snapshot; recalculation will be required to resume payroll processing.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Confirm Cancellation') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!empty($mergedValidationIssues))
        <div class="alert alert-warning" role="alert">
            <h6 class="fw-semibold mb-2">{{ __('Validation Issues Detected') }}</h6>
            <ul class="mb-0">
                @foreach($mergedValidationIssues as $issue)
                    <li>{{ $issue }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('payroll.partials.calculation-summary', [
        'summary' => $summary,
        'payrollRun' => $payrollRun,
    ])

    @if(empty($summary))
        <div class="card mb-4">
            <div class="card-body text-center text-muted py-5">
                {{ __('Calculation summary will appear once this payroll run is processed.') }}
            </div>
        </div>
    @endif
</div>
@endsection




