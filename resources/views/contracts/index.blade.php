@extends('layouts.app')

@section('title', __('hrms.contracts'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.contracts') }}</h1>
        
        <div class="d-flex gap-2">
            {{-- Export temporarily disabled --}}
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" disabled>
                    <i class="fas fa-download"></i> {{ __('hrms.export') }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Excel (Coming Soon)</a></li>
                    <li><a class="dropdown-item" href="#">PDF (Coming Soon)</a></li>
                    <li><a class="dropdown-item" href="#">CSV (Coming Soon)</a></li>
                </ul>
            </div>
            
            <a href="{{ route('contracts.create') }}" class="btn btn-brand-primary">
                <i class="fas fa-plus"></i> {{ __('hrms.add_contract') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-file-contract text-primary fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">{{ __('hrms.total_contracts') }}</div>
                            <div class="h4 mb-0">{{ $stats['total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">{{ __('hrms.active_contracts') }}</div>
                            <div class="h4 mb-0">{{ $stats['active'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">{{ __('hrms.expiring_soon') }}</div>
                            <div class="h4 mb-0">{{ $stats['expiring_soon'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-times-circle text-danger fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">{{ __('hrms.expired') }}</div>
                            <div class="h4 mb-0">{{ $stats['expired'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">
                <i class="fas fa-search"></i> {{ __('hrms.search') }} & {{ __('hrms.filter') }}
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('contracts.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label for="search" class="form-label">{{ __('hrms.search_contracts') }}</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('hrms.search_by_employee') }}">
                </div>

                <!-- Contract Type Filter -->
                <div class="col-md-2">
                    <label for="type" class="form-label">{{ __('hrms.contract_type') }}</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">{{ __('hrms.all_types') }}</option>
                        @foreach($contractTypes as $typeOption)
                            <option value="{{ $typeOption['value'] }}" {{ request('type') === $typeOption['value'] ? 'selected' : '' }}>
                                {{ $typeOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label for="status" class="form-label">{{ __('hrms.status') }}</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">{{ __('hrms.all_statuses') }}</option>
                        @foreach($contractStatuses as $statusOption)
                            <option value="{{ $statusOption['value'] }}" {{ request('status') === $statusOption['value'] ? 'selected' : '' }}>
                                {{ $statusOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Employee Filter -->
                <div class="col-md-2">
                    <label for="employee_id" class="form-label">{{ __('hrms.employee') }}</label>
                    <select name="employee_id" id="employee_id" class="form-select">
                        <option value="">{{ __('hrms.all_employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Expiry Filter -->
                <div class="col-md-2">
                    <label for="expiry_filter" class="form-label">{{ __('hrms.expiry_status') }}</label>
                    <select name="expiry_filter" id="expiry_filter" class="form-select">
                        <option value="">{{ __('hrms.all_expiry_statuses') }}</option>
                        <option value="urgent" {{ request('expiry_filter') === 'urgent' ? 'selected' : '' }}>
                            {{ __('hrms.expiring_urgently') }}
                        </option>
                        <option value="critical" {{ request('expiry_filter') === 'critical' ? 'selected' : '' }}>
                            {{ __('hrms.expiring_critically') }}
                        </option>
                        <option value="soon" {{ request('expiry_filter') === 'soon' ? 'selected' : '' }}>
                            {{ __('hrms.expiring_soon') }}
                        </option>
                        <option value="expired" {{ request('expiry_filter') === 'expired' ? 'selected' : '' }}>
                            {{ __('hrms.expired') }}
                        </option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-brand-primary me-2">
                        <i class="fas fa-search"></i> {{ __('hrms.search') }}
                    </button>
                    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> {{ __('hrms.clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="row mb-3">
        <div class="col-md-6">
            <p class="mb-0 text-muted">
                {{ __('Showing :from to :to of :total contracts', [
                    'from' => $contracts->firstItem() ?? 0,
                    'to' => $contracts->lastItem() ?? 0,
                    'total' => $contracts->total()
                ]) }}
            </p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}" 
                   class="btn btn-outline-secondary {{ request('per_page', 15) == 15 ? 'active' : '' }}">15</a>
                <a href="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" 
                   class="btn btn-outline-secondary {{ request('per_page') == 25 ? 'active' : '' }}">25</a>
                <a href="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" 
                   class="btn btn-outline-secondary {{ request('per_page') == 50 ? 'active' : '' }}">50</a>
            </div>
        </div>
    </div>

    <!-- Contracts Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($contracts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-header-custom">
                            <tr>
                                <th>{{ __('hrms.employee') }}</th>
                                <th>{{ __('hrms.contract_type') }}</th>
                                <th>{{ __('hrms.start_date') }}</th>
                                <th>{{ __('hrms.end_date') }}</th>
                                <th>{{ __('hrms.status') }}</th>
                                <th>{{ __('hrms.expiry_status') }}</th>
                                <th class="text-center">{{ __('hrms.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contracts as $contract)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $contract->employee->photo_url }}" 
                                                 alt="{{ $contract->employee->display_name }}" 
                                                 class="rounded-circle me-3 border border-2 border-brand-gold"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                            <div>
                                                <strong>{{ $contract->employee->display_name }}</strong>
                                                <br><small class="text-muted">{{ $contract->employee->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $contract->type_name }}</span>
                                    </td>
                                    <td>
                                        {{ $contract->start_date?->format('Y-m-d') ?? __('N/A') }}
                                        @if($contract->start_date)
                                            <br><small class="text-muted">{{ $contract->start_date->diffForHumans() }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($contract->end_date)
                                            {{ $contract->end_date->format('Y-m-d') }}
                                            <br><small class="text-muted">{{ $contract->end_date->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">{{ __('hrms.permanent') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($contract->status)
                                            @case('active')
                                                <span class="badge bg-success">{{ __('hrms.contract_status.active') }}</span>
                                                @break
                                            @case('expired')
                                                <span class="badge bg-danger">{{ __('hrms.contract_status.expired') }}</span>
                                                @break
                                            @case('terminated')
                                                <span class="badge bg-secondary">{{ __('hrms.contract_status.terminated') }}</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">{{ __('hrms.contract_status.pending') }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($contract->days_until_expiry !== null)
                                            @if($contract->isExpiringUrgently())
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> 
                                                    {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                                </span>
                                            @elseif($contract->isExpiringCritically())
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-exclamation-circle"></i> 
                                                    {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                                </span>
                                            @elseif($contract->isExpiringSoon())
                                                <span class="badge bg-info">
                                                    <i class="fas fa-info-circle"></i> 
                                                    {{ $contract->days_until_expiry }} {{ __('hrms.days_left') }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('hrms.ok') }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('contracts.show', $contract) }}" 
                                               class="btn btn-outline-primary" 
                                               title="{{ __('hrms.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('contracts.edit', $contract) }}" 
                                               class="btn btn-outline-warning" 
                                               title="{{ __('hrms.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if($contract->isRenewable() && $contract->isActive())
                                                <button type="button" 
                                                        class="btn btn-outline-success" 
                                                        title="{{ __('hrms.renew') }}"
                                                        onclick="renewContract({{ $contract->id }})">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light">
                    {{ $contracts->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('hrms.no_results') }}</h5>
                    <p class="text-muted">{{ __('No contracts found matching your criteria.') }}</p>
                    
                    <a href="{{ route('contracts.create') }}" class="btn btn-brand-primary">
                        <i class="fas fa-plus"></i> {{ __('hrms.add_contract') }}
                    </a>
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
        // Auto-submit form on filter change
        document.querySelectorAll('select[name="type"], select[name="status"], select[name="employee_id"], select[name="expiry_filter"]').forEach(function(select) {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Renew contract function
        function renewContract(contractId) {
            if (confirm('{{ __("Are you sure you want to renew this contract?") }}')) {
                // Create form and submit
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
    </script>
@endpush