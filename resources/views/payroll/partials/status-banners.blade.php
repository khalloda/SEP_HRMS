@php
    $successMessage = $successMessage ?? session('success');
    $errorMessage = $errorMessage ?? session('error');
    $calculationReference = $calculationReference ?? session('calculation_reference');
    $calculationJob = $calculationJob ?? session('calculation_job');
    $calculationResults = $calculationResults ?? session('calculation_results');
    $calculationErrors = $calculationErrors ?? session('calculation_errors');
@endphp

@if($successMessage)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $successMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errorMessage)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errorMessage }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($calculationReference)
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
            <div>
                <strong>{{ __('Correlation ID') }}:</strong> <code>{{ $calculationReference }}</code>
            </div>
            @if($calculationJob)
                <div class="small text-muted">{{ __('Processing continues in the background. Monitor logs using this ID.') }}</div>
            @else
                <div class="small text-muted">{{ __('Use this ID when reviewing logs or opening support tickets.') }}</div>
            @endif
        </div>
        @if(is_array($calculationJob) && !empty($calculationJob['payroll_run_id']))
            <div class="small text-muted mt-2">{{ __('Run ID') }}: {{ $calculationJob['payroll_run_id'] }}</div>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(!empty($calculationResults))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="fw-semibold">{{ __('Latest calculation completed successfully.') }}</div>
        <ul class="mb-0 small">
            <li>{{ __('Employees processed') }}: {{ number_format($calculationResults['employees_processed'] ?? 0) }}</li>
            <li>{{ __('Payslips created') }}: {{ number_format($calculationResults['payslips_created'] ?? 0) }}</li>
        </ul>
        @if(!empty($calculationResults['errors']))
            <div class="mt-2 small text-muted">{{ __('Warnings logged during calculation.') }}</div>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(!empty($calculationErrors))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="fw-semibold">{{ __('Calculation errors reported') }}</div>
        <ul class="mb-0 small">
            @foreach((array) $calculationErrors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif