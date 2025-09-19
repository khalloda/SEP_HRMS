@extends('layouts.app')

@section('title', __('hrms.contract_details'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.contract_details') }} - {{ $contract->employee->display_name }}</h1>
        
        <div class="d-flex gap-2">
            <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('hrms.back_to_contracts') }}
            </a>
            
            @can('update', $contract)
                <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> {{ __('hrms.edit') }}
                </a>
                @can('contracts.manage')
                    @if($contract->status === 'draft')
                        <form method="POST" action="{{ route('contracts.submit-review', $contract) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-paper-plane"></i> Submit for Review
                            </button>
                        </form>
                    @elseif($contract->status === 'review' || $contract->status === 'pending')
                        <form method="POST" action="{{ route('contracts.approve', $contract) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                    @elseif($contract->status === 'approved')
                        <form method="POST" action="{{ route('contracts.sign', $contract) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-brand-primary">
                                <i class="fas fa-signature"></i> Sign
                            </button>
                        </form>
                    @endif
                @endcan
            @endcan
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Contract Information -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract"></i> {{ __('hrms.contract_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.employee.title') }}</label>
                            <div class="d-flex align-items-center">
                                <img src="{{ $contract->employee->photo_url }}" 
                                     alt="{{ $contract->employee->display_name }}" 
                                     class="rounded-circle me-3 border border-2 border-brand-gold"
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold">{{ $contract->employee->display_name }}</div>
                                    <div class="text-muted small">{{ $contract->employee->code }}</div>
                                    <div class="text-muted small">{{ $contract->employee->department?->name }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.contract_type') }}</label>
                            <div>
                                <span class="badge bg-info fs-6">{{ $contract->type_name }}</span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.status.status') }}</label>
                            <div>
                                @switch($contract->status)
                                    @case('active')
                                        <span class="badge bg-success fs-6">{{ $contract->status_name }}</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-danger fs-6">{{ $contract->status_name }}</span>
                                        @break
                                    @case('terminated')
                                        <span class="badge bg-secondary fs-6">{{ $contract->status_name }}</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning fs-6">{{ $contract->status_name }}</span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.expiry_status') }}</label>
                            <div>
                                @if($contract->days_until_expiry !== null)
                                    @if($contract->isExpiringUrgently())
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                        </span>
                                    @elseif($contract->isExpiringCritically())
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-exclamation-circle"></i> 
                                            {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                        </span>
                                    @elseif($contract->isExpiringSoon())
                                        <span class="badge bg-info fs-6">
                                            <i class="fas fa-info-circle"></i> 
                                            {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                        </span>
                                    @else
                                        <span class="text-success">{{ __('hrms.ok') }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('N/A') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.start_date') }}</label>
                            <div>{{ $contract->start_date?->format('Y-m-d') ?? __('N/A') }}</div>
                            @if($contract->start_date)
                                <small class="text-muted">{{ $contract->start_date->diffForHumans() }}</small>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.end_date') }}</label>
                            <div>
                                @if($contract->end_date)
                                    {{ $contract->end_date->format('Y-m-d') }}
                                    <br><small class="text-muted">{{ $contract->end_date->diffForHumans() }}</small>
                                @else
                                    <span class="text-success">{{ __('hrms.permanent') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($contract->duration_in_months)
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('hrms.duration') }}</label>
                                <div>{{ $contract->duration_in_months }} {{ __('hrms.months') }}</div>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('hrms.properties') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @if($contract->requiresEndDate())
                                    <span class="badge bg-light text-dark">{{ __('hrms.requires_end_date') }}</span>
                                @endif
                                @if($contract->isRenewable())
                                    <span class="badge bg-light text-dark">{{ __('hrms.renewable') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contract Terms -->
            @if($contract->terms_json && count($contract->terms_json) > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-list-check"></i> {{ __('hrms.contract_terms') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($contract->terms_json as $key => $value)
                                @if($value !== null && $value !== '')
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-medium">{{ __('hrms.terms.' . $key, ucfirst(str_replace('_', ' ', $key))) }}:</span>
                                            <span class="text-end">
                                                @if(is_bool($value))
                                                    <span class="badge {{ $value ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $value ? __('hrms.yes') : __('hrms.no') }}
                                                    </span>
                                                @elseif(is_numeric($value))
                                                    {{ $value }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            @if($activities->count() > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> {{ __('hrms.recent_activity') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($activities as $activity)
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-light p-2">
                                        <i class="fas fa-history text-muted"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">
                                        @if(__('hrms.activity.' . $activity->description) !== 'hrms.activity.' . $activity->description)
                                            {{ __('hrms.activity.' . $activity->description) }}
                                        @else
                                            {{ $activity->description }}
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->causer)
                                            by {{ $activity->causer->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> {{ __('hrms.actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('update', $contract)
                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit"></i> {{ __('hrms.edit_contract') }}
                            </a>
                        @endcan

                        @if($contract->isRenewable() && $contract->isActive())
                            @can('renew', $contract)
                                <button type="button" class="btn btn-outline-success" onclick="renewContract({{ $contract->id }})">
                                    <i class="fas fa-redo"></i> {{ __('hrms.renew_contract') }}
                                </button>
                            @endcan
                        @endif

                        @if($contract->isActive())
                            @can('terminate', $contract)
                                <button type="button" class="btn btn-outline-danger" onclick="terminateContract({{ $contract->id }})">
                                    <i class="fas fa-times"></i> {{ __('hrms.terminate_contract') }}
                                </button>
                            @endcan
                        @endif

                        @can('delete', $contract)
                            <button type="button" class="btn btn-outline-dark" onclick="deleteContract({{ $contract->id }})">
                                <i class="fas fa-trash"></i> {{ __('hrms.delete') }}
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Contract Statistics -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i> {{ __('hrms.statistics') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-muted small">{{ __('hrms.contract_id') }}</div>
                            <div class="h5 mb-0">#{{ $contract->id }}</div>
                        </div>
                        @if($contract->duration_in_months)
                            <div class="col-6 mb-3">
                                <div class="text-muted small">{{ __('hrms.duration') }}</div>
                                <div class="h5 mb-0">{{ $contract->duration_in_months }}{{ __('hrms.months_short') }}</div>
                            </div>
                        @endif
                        @if($contract->days_until_expiry !== null)
                            <div class="col-12 mb-3">
                                <div class="text-muted small">{{ __('hrms.days_until_expiry') }}</div>
                                <div class="h5 mb-0">{{ $contract->days_until_expiry }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Documents -->
            @if($contract->documents->count() > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-paperclip"></i> {{ __('hrms.related_documents') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($contract->documents as $document)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-file me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="small fw-medium">{{ $document->original_name }}</div>
                                    <div class="text-muted small">{{ $document->created_at->format('Y-m-d') }}</div>
                                </div>
                                <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function renewContract(contractId) {
            if (confirm('{{ __("Are you sure you want to renew this contract?") }}')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/contracts/${contractId}/renew`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function terminateContract(contractId) {
            const reason = prompt('{{ __("Please enter termination reason:") }}');
            if (reason && reason.trim()) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/contracts/${contractId}/terminate`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'termination_reason';
                reasonInput.value = reason;
                form.appendChild(reasonInput);
                
                const dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'termination_date';
                dateInput.value = new Date().toISOString().split('T')[0];
                form.appendChild(dateInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteContract(contractId) {
            if (confirm('{{ __("Are you sure you want to delete this contract? This action cannot be undone.") }}')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/contracts/${contractId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
