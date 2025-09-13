@extends('layouts.app')

@section('title', __('hrms.add_contract'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.add_contract') }}</h1>
        
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('hrms.back_to_contracts') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-file-contract"></i> {{ __('hrms.contract_details') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('contracts.store') }}">
                        @csrf

                        <!-- Employee Selection -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="employee_id" class="form-label required">{{ __('hrms.employee.title') }}</label>
                                <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                    <option value="">{{ __('hrms.select_employee') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ 
                                            (old('employee_id', $selectedEmployee?->id) == $employee->id) ? 'selected' : '' 
                                        }}>
                                            {{ $employee->code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contract Type -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label required">{{ __('hrms.contract_type') }}</label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">{{ __('hrms.select_contract_type') }}</option>
                                    @foreach($contractTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                            {{ __('hrms.contract_types.' . $key) ?: $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('hrms.contract_type_help') }}</div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label required">{{ __('hrms.status.status') }}</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        {{ __('hrms.contract_status.active') }}
                                    </option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                        {{ __('hrms.contract_status.pending') }}
                                    </option>
                                    <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>
                                        {{ __('hrms.contract_status.terminated') }}
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contract Dates -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label required">{{ __('hrms.start_date') }}</label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">
                                    {{ __('hrms.end_date') }}
                                    <span id="end_date_required" class="text-danger d-none">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="end_date_help">{{ __('hrms.end_date_help') }}</div>
                            </div>
                        </div>

                        <!-- Contract Terms Section -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-list-check"></i> {{ __('hrms.contract_terms') }}
                                <small class="text-muted">({{ __('hrms.optional') }})</small>
                            </h6>
                            
                            <div id="terms-section">
                                <!-- Terms will be populated dynamically based on contract type -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="terms_json[confidentiality]" 
                                                   id="confidentiality" {{ old('terms_json.confidentiality') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="confidentiality">
                                                {{ __('hrms.confidentiality_clause') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="terms_json[non_compete]" 
                                                   id="non_compete" {{ old('terms_json.non_compete') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="non_compete">
                                                {{ __('hrms.non_compete_clause') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="notice_period_days" class="form-label">{{ __('hrms.notice_period_days') }}</label>
                                        <input type="number" 
                                               name="terms_json[notice_period_days]" 
                                               id="notice_period_days" 
                                               class="form-control"
                                               value="{{ old('terms_json.notice_period_days', 30) }}" 
                                               min="0" max="365">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="working_hours" class="form-label">{{ __('hrms.working_hours_per_day') }}</label>
                                        <input type="number" 
                                               name="terms_json[working_hours]" 
                                               id="working_hours" 
                                               class="form-control"
                                               value="{{ old('terms_json.working_hours', 8) }}" 
                                               min="1" max="24" step="0.5">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="probation_period" class="form-label">{{ __('hrms.probation_period_days') }}</label>
                                        <input type="number" 
                                               name="terms_json[probation_period]" 
                                               id="probation_period" 
                                               class="form-control"
                                               value="{{ old('terms_json.probation_period') }}" 
                                               min="0" max="365">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="annual_leave_days" class="form-label">{{ __('hrms.annual_leave_days') }}</label>
                                        <input type="number" 
                                               name="terms_json[annual_leave_days]" 
                                               id="annual_leave_days" 
                                               class="form-control"
                                               value="{{ old('terms_json.annual_leave_days', 21) }}" 
                                               min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="sick_leave_days" class="form-label">{{ __('hrms.sick_leave_days') }}</label>
                                        <input type="number" 
                                               name="terms_json[sick_leave_days]" 
                                               id="sick_leave_days" 
                                               class="form-control"
                                               value="{{ old('terms_json.sick_leave_days', 15) }}" 
                                               min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="terms_json[health_insurance]" 
                                                   id="health_insurance" {{ old('terms_json.health_insurance') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="health_insurance">
                                                {{ __('hrms.health_insurance_provided') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> {{ __('hrms.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-brand-primary">
                                <i class="fas fa-save"></i> {{ __('hrms.create_contract') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .required::after {
            content: ' *';
            color: #dc3545;
        }
        .form-text {
            font-size: 0.875em;
            color: #6c757d;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const endDateInput = document.getElementById('end_date');
            const endDateRequired = document.getElementById('end_date_required');
            const endDateHelp = document.getElementById('end_date_help');

            // Contract types that require end date
            const typesRequiringEndDate = ['fixed_term', 'probation', 'internship', 'consultancy'];

            function updateEndDateRequirement() {
                const selectedType = typeSelect.value;
                const requiresEndDate = typesRequiringEndDate.includes(selectedType);
                
                if (requiresEndDate) {
                    endDateInput.required = true;
                    endDateRequired.classList.remove('d-none');
                    endDateHelp.textContent = '{{ __("hrms.end_date_required_for_type") }}';
                } else {
                    endDateInput.required = false;
                    endDateRequired.classList.add('d-none');
                    endDateHelp.textContent = '{{ __("hrms.end_date_help") }}';
                }
            }

            // Update on page load
            updateEndDateRequirement();

            // Update when contract type changes
            typeSelect.addEventListener('change', updateEndDateRequirement);

            // Auto-populate contract terms based on type
            typeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                populateDefaultTerms(selectedType);
            });

            function populateDefaultTerms(type) {
                // Default terms based on contract type
                const defaultTerms = {
                    permanent: {
                        confidentiality: true,
                        non_compete: false,
                        notice_period_days: 30,
                        working_hours: 8,
                        annual_leave_days: 21,
                        sick_leave_days: 15,
                        health_insurance: true,
                        probation_period: null
                    },
                    fixed_term: {
                        confidentiality: true,
                        non_compete: false,
                        notice_period_days: 30,
                        working_hours: 8,
                        annual_leave_days: 21,
                        sick_leave_days: 15,
                        health_insurance: true,
                        probation_period: null
                    },
                    probation: {
                        confidentiality: true,
                        non_compete: false,
                        notice_period_days: 7,
                        working_hours: 8,
                        annual_leave_days: 0,
                        sick_leave_days: 15,
                        health_insurance: false,
                        probation_period: 90
                    },
                    internship: {
                        confidentiality: true,
                        non_compete: false,
                        notice_period_days: 3,
                        working_hours: 6,
                        annual_leave_days: 0,
                        sick_leave_days: 5,
                        health_insurance: false,
                        probation_period: null
                    },
                    consultancy: {
                        confidentiality: true,
                        non_compete: true,
                        notice_period_days: 15,
                        working_hours: 8,
                        annual_leave_days: 0,
                        sick_leave_days: 0,
                        health_insurance: false,
                        probation_period: null
                    }
                };

                if (defaultTerms[type]) {
                    const terms = defaultTerms[type];
                    
                    document.getElementById('confidentiality').checked = terms.confidentiality;
                    document.getElementById('non_compete').checked = terms.non_compete;
                    document.getElementById('notice_period_days').value = terms.notice_period_days;
                    document.getElementById('working_hours').value = terms.working_hours;
                    document.getElementById('annual_leave_days').value = terms.annual_leave_days;
                    document.getElementById('sick_leave_days').value = terms.sick_leave_days;
                    document.getElementById('health_insurance').checked = terms.health_insurance;
                    document.getElementById('probation_period').value = terms.probation_period || '';
                }
            }
        });
    </script>
@endpush