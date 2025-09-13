@if(isset($data['access_denied']))
    <div class="text-center text-muted py-3">
        <i class="fas fa-lock fa-2x mb-2"></i>
        <p>{{ __('Access restricted') }}</p>
    </div>
@else
    <!-- Summary Stats -->
    <div class="row text-center mb-3">
        <div class="col-4">
            <div class="text-danger">
                <div class="h4 mb-0">{{ $data['urgent']->count() }}</div>
                <small>{{ __('Urgent') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-warning">
                <div class="h4 mb-0">{{ $data['critical']->count() }}</div>
                <small>{{ __('Critical') }}</small>
            </div>
        </div>
        <div class="col-4">
            <div class="text-info">
                <div class="h4 mb-0">{{ $data['soon'] }}</div>
                <small>{{ __('Soon') }}</small>
            </div>
        </div>
    </div>

    <!-- Urgent Contracts List -->
    @if($data['urgent']->count() > 0)
        <h6 class="text-danger mb-2">{{ __('Urgent Renewals (≤7 days)') }}</h6>
        <div class="list-group list-group-flush mb-3">
            @foreach($data['urgent'] as $contract)
            <div class="list-group-item px-0 py-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $contract->employee->display_name }}</div>
                        <small class="text-muted">{{ $contract->employee->department->name_en }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-danger">
                            {{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Critical Contracts List -->
    @if($data['critical']->count() > 0)
        <h6 class="text-warning mb-2">{{ __('Critical Renewals (≤15 days)') }}</h6>
        <div class="list-group list-group-flush">
            @foreach($data['critical'] as $contract)
            <div class="list-group-item px-0 py-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $contract->employee->display_name }}</div>
                        <small class="text-muted">{{ $contract->employee->department->name_en }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning">
                            {{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($data['urgent']->count() === 0 && $data['critical']->count() === 0)
        <div class="text-center text-muted py-3">
            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
            <p>{{ __('No urgent contract renewals') }}</p>
        </div>
    @endif
@endif