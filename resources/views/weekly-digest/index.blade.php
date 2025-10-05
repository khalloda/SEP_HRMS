@extends('layouts.app')

@section('title', __('Weekly Digest'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Weekly Digest') }}</h2>
            <p class="text-muted mb-0">{{ __('Manage and preview weekly digest emails') }}</p>
        </div>
        <div>
            <a href="{{ route('weekly-digest.preview') }}" class="btn btn-outline-secondary me-2" target="_blank">
                <i class="fas fa-eye"></i> {{ __('Preview Email') }}
            </a>
            <button type="button" class="btn btn-brand-secondary" onclick="sendTestDigest()">
                <i class="fas fa-paper-plane"></i> {{ __('Send Test') }}
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Current Week Summary -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('This Week\'s Digest Content') }}</h5>
            </div>
            <div class="card-body">
                <!-- Send Status -->
                <div class="alert {{ $preview['should_send'] ? 'alert-success' : 'alert-warning' }} mb-4">
                    @if($preview['should_send'])
                        <i class="fas fa-check-circle"></i> {{ __('Weekly digest will be sent automatically (criteria met)') }}
                    @else
                        <i class="fas fa-exclamation-triangle"></i> {{ __('Weekly digest will not be sent automatically (criteria not met)') }}
                    @endif
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-start border-danger border-3 bg-light">
                            <div class="card-body text-center p-3">
                                <h4 class="text-danger mb-1">{{ $preview['summary']['expiring_contracts_total'] }}</h4>
                                <small class="text-muted">{{ __('Expiring Contracts') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-start border-warning border-3 bg-light">
                            <div class="card-body text-center p-3">
                                <h4 class="text-warning mb-1">{{ $preview['summary']['expiring_documents_total'] }}</h4>
                                <small class="text-muted">{{ __('Expiring Documents') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-start border-info border-3 bg-light">
                            <div class="card-body text-center p-3">
                                <h4 class="text-info mb-1">{{ $preview['summary']['birthdays_total'] }}</h4>
                                <small class="text-muted">{{ __('Upcoming Birthdays') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-start border-success border-3 bg-light">
                            <div class="card-body text-center p-3">
                                <h4 class="text-success mb-1">{{ $preview['summary']['activity_total'] }}</h4>
                                <small class="text-muted">{{ __('Weekly Activities') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Breakdown -->
                <div class="row">
                    <!-- Contract Expiries -->
                    @if($preview['summary']['expiring_contracts_total'] > 0)
                    <div class="col-md-6 mb-4">
                        <h6 class="text-danger">
                            <i class="fas fa-file-contract"></i> {{ __('Contract Expiries') }}
                            <span class="badge bg-danger">{{ $preview['summary']['expiring_contracts_total'] }}</span>
                        </h6>
                        <ul class="list-group list-group-flush">
                            @foreach($preview['data']['contracts']['expiring_urgently'] as $contract)
                            <li class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $contract->employee->display_name }}</strong>
                                        <br><small class="text-muted">{{ $contract->type_name }}</small>
                                    </div>
                                    <span class="badge bg-danger">{{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }}</span>
                                </div>
                            </li>
                            @endforeach
                            @foreach($preview['data']['contracts']['expiring_critically'] as $contract)
                            <li class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $contract->employee->display_name }}</strong>
                                        <br><small class="text-muted">{{ $contract->type_name }}</small>
                                    </div>
                                    <span class="badge bg-warning">{{ now()->diffInDays($contract->end_date, false) }} {{ __('days') }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Document Expiries -->
                    @if($preview['summary']['expiring_documents_total'] > 0)
                    <div class="col-md-6 mb-4">
                        <h6 class="text-warning">
                            <i class="fas fa-folder-open"></i> {{ __('Document Expiries') }}
                            <span class="badge bg-warning">{{ $preview['summary']['expiring_documents_total'] }}</span>
                        </h6>
                        <ul class="list-group list-group-flush">
                            @foreach($preview['data']['documents']['expiring'] as $document)
                            <li class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $document->owner_name }}</strong>
                                        <br><small class="text-muted">{{ $document->type_display_name }}</small>
                                    </div>
                                    <span class="badge bg-warning">
                                        {{ $document->expires_at ? now()->diffInDays($document->expires_at, false) : 0 }} {{ __('days') }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Birthdays -->
                    @if($preview['summary']['birthdays_total'] > 0)
                    <div class="col-md-6 mb-4">
                        <h6 class="text-info">
                            <i class="fas fa-birthday-cake"></i> {{ __('Upcoming Birthdays') }}
                            <span class="badge bg-info">{{ $preview['summary']['birthdays_total'] }}</span>
                        </h6>
                        <ul class="list-group list-group-flush">
                            @foreach($preview['data']['birthdays'] as $employee)
                            <li class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $employee->display_name }}</strong>
                                        <br><small class="text-muted">{{ $employee->department->name ?? 'N/A' }}</small>
                                    </div>
                                    <span class="badge bg-info">{{ $employee->birth_date->format('M j') }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- New Hires -->
                    @if($preview['summary']['new_hires_total'] > 0)
                    <div class="col-md-6 mb-4">
                        <h6 class="text-success">
                            <i class="fas fa-user-plus"></i> {{ __('New Hires This Week') }}
                            <span class="badge bg-success">{{ $preview['summary']['new_hires_total'] }}</span>
                        </h6>
                        <ul class="list-group list-group-flush">
                            @foreach($preview['data']['new_hires'] as $employee)
                            <li class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $employee->display_name }}</strong>
                                        <br><small class="text-muted">{{ $employee->position->name }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ $employee->hire_date->format('M j') }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- No Content Message -->
                @if($preview['summary']['expiring_contracts_total'] == 0 &&
                    $preview['summary']['expiring_documents_total'] == 0 &&
                    $preview['summary']['birthdays_total'] == 0 &&
                    $preview['summary']['new_hires_total'] == 0)
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No significant activities this week') }}</h5>
                    <p class="text-muted">{{ __('The weekly digest will not be sent automatically.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Send Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Send Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-brand-primary" onclick="sendDigest(false)">
                        <i class="fas fa-paper-plane"></i> {{ __('Send Now') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="sendDigest(true)">
                        <i class="fas fa-paper-plane"></i> {{ __('Force Send') }}
                    </button>
                    <hr>
                    <button type="button" class="btn btn-outline-secondary" onclick="sendTestDigest()">
                        <i class="fas fa-vial"></i> {{ __('Send Test to Me') }}
                    </button>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>{{ __('Send Now') }}:</strong> {{ __('Only sends if criteria are met') }}<br>
                        <strong>{{ __('Force Send') }}:</strong> {{ __('Sends regardless of criteria') }}<br>
                        <strong>{{ __('Send Test') }}:</strong> {{ __('Sends test digest to your email only') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Recipients -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Recipients') }} ({{ $preview['recipients']->count() }})</h5>
            </div>
            <div class="card-body">
                @if($preview['recipients']->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($preview['recipients'] as $user)
                        <li class="list-group-item px-0 py-2">
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <br><small class="text-muted">{{ $user->email }}</small>
                                <br><span class="badge bg-secondary">{{ $user->roles->pluck('name')->join(', ') }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('No recipients configured') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule Information -->
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Schedule') }}</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="fas fa-clock text-muted me-2"></i>
                    {{ __('Weekly digest is sent automatically every') }} <strong>{{ __('Monday at 9:00 AM') }}</strong>
                </p>
                <p class="mb-2">
                    <i class="fas fa-calendar text-muted me-2"></i>
                    {{ __('Next scheduled send') }}: <strong>{{ now()->next('Monday')->setTime(9, 0)->format('M j, Y \a\t g:i A') }}</strong>
                </p>
                <hr>
                <small class="text-muted">
                    {{ __('The digest is only sent when there are expiring contracts, documents, birthdays, new hires, or significant system activity.') }}
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                </div>
                <p class="mt-3 mb-0">{{ __('Sending digest...') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sendDigest(force = false) {
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();

    fetch('{{ route("weekly-digest.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ force: force })
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        modal.hide();
        alert('❌ Error: ' + error.message);
    });
}

function sendTestDigest() {
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();

    fetch('{{ route("weekly-digest.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        modal.hide();
        if (data.success) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        modal.hide();
        alert('❌ Error: ' + error.message);
    });
}
</script>
@endpush