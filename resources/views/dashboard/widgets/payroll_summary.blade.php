@if(isset($data['access_denied']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-lock fa-2x mb-2"></i>
        <p>{{ __('hrms.dashboard.payroll_access_restricted') }}</p>
    </div>
@else
    <div class="row text-center mb-3">
        <div class="col-4">
            <div class="text-primary">
                <div class="h4 mb-0">{{ number_format($data['total_runs']) }}</div>
                <small>{{ __('hrms.dashboard.runs_this_year') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-warning">
                <div class="h4 mb-0">{{ number_format($data['pending']) }}</div>
                <small>{{ __('hrms.dashboard.pending_approval') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-success">
                <div class="h4 mb-0">{{ number_format($data['employees_with_structures']) }}</div>
                <small>{{ __('hrms.dashboard.employees_paid') }}</small>
            </div>
        </div>
    </div>

    @if(isset($data['last_gross']) && isset($data['last_net']))
        <hr class="my-3">
        <h6 class="mb-2">{{ __('hrms.payroll.title') }}</h6>
        <div class="row">
            <div class="col-6">
                <div class="text-center p-2 bg-light rounded">
                    <div class="text-success fw-bold">{{ number_format($data['last_gross'], 0) }}</div>
                    <small class="text-muted">{{ __('hrms.payroll.total_gross') }}</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-2 bg-light rounded">
                    <div class="text-info fw-bold">{{ number_format($data['last_net'], 0) }}</div>
                    <small class="text-muted">{{ __('hrms.payroll.total_net') }}</small>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-3">
            <small>
                <i class="fas fa-info-circle"></i>
                {{ __('hrms.dashboard.payroll_access_restricted') }}
            </small>
        </div>
    @endif
@endif