@extends('layouts.app')

@section('title', __('hrms.edit') . ' ' . $employee->display_name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 brand-dark-green">{{ __('hrms.edit') }} {{ __('Employee') }}</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-secondary">{{ $employee->code }}</span>
                <span class="text-muted">{{ $employee->display_name }}</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary">
                <i class="fas fa-eye"></i> {{ __('hrms.view') }}
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('employees.update', $employee) }}" novalidate>
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-user"></i> {{ __('hrms.personal_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">{{ __('hrms.first_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', $employee->first_name) }}" 
                                           required maxlength="80">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">{{ __('hrms.last_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', $employee->last_name) }}" 
                                           required maxlength="80">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="arabic_name" class="form-label">{{ __('hrms.arabic_name') }}</label>
                            <input type="text" 
                                   class="form-control @error('arabic_name') is-invalid @enderror" 
                                   id="arabic_name" 
                                   name="arabic_name" 
                                   value="{{ old('arabic_name', $employee->arabic_name) }}" 
                                   maxlength="160"
                                   placeholder="{{ __('Optional - Arabic name') }}">
                            @error('arabic_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('hrms.email') }}</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $employee->email) }}" 
                                           maxlength="190">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('hrms.phone') }}</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $employee->phone) }}" 
                                           maxlength="40">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">{{ __('hrms.hire_date') }}</label>
                                    <input type="date" 
                                           class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" 
                                           name="hire_date" 
                                           value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" 
                                           max="{{ date('Y-m-d') }}">
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="national_id" class="form-label">{{ __('hrms.national_id') }}</label>
                                    <input type="text" 
                                           class="form-control @error('national_id') is-invalid @enderror" 
                                           id="national_id" 
                                           name="national_id" 
                                           value="{{ old('national_id', $employee->national_id) }}" 
                                           maxlength="20"
                                           placeholder="{{ __('National ID or Passport Number') }}">
                                    @error('national_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">
                                        <i class="fas fa-lock"></i> {{ __('This information will be encrypted') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase"></i> {{ __('hrms.employment_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('hrms.status.status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>
                                            {{ __('hrms.status.active') }}
                                        </option>
                                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>
                                            {{ __('hrms.status.inactive') }}
                                        </option>
                                        <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>
                                            {{ __('hrms.status.on_leave') }}
                                        </option>
                                        @if(auth()->user()->hasRole('HR_Admin_Manager'))
                                            <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>
                                                {{ __('hrms.status.terminated') }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">{{ __('hrms.department') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" 
                                            id="department_id" 
                                            name="department_id" 
                                            required>
                                        <option value="">{{ __('Select Department') }}</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">{{ __('hrms.position') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('position_id') is-invalid @enderror" 
                                            id="position_id" 
                                            name="position_id" 
                                            required>
                                        <option value="">{{ __('Select Position') }}</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" 
                                                {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }} 
                                                <small class="text-muted">({{ ucfirst($position->category) }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type_id" class="form-label">{{ __('hrms.employee.employment_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('employment_type_id') is-invalid @enderror" 
                                            id="employment_type_id" 
                                            name="employment_type_id" 
                                            required>
                                        <option value="">{{ __('Select Employment Type') }}</option>
                                        @foreach($employmentTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                {{ old('employment_type_id', $employee->employment_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employment_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manager_id" class="form-label">{{ __('hrms.manager') }}</label>
                                    <select class="form-select @error('manager_id') is-invalid @enderror" 
                                            id="manager_id" 
                                            name="manager_id">
                                        <option value="">{{ __('Select Manager (Optional)') }}</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" 
                                                {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->first_name }} {{ $manager->last_name }}
                                                <small class="text-muted">({{ $manager->id }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('manager_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('salary_visibility_flag') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="salary_visibility_flag" 
                                       name="salary_visibility_flag" 
                                       value="1" 
                                       {{ old('salary_visibility_flag', $employee->salary_visibility_flag) ? 'checked' : '' }}>
                                <label class="form-check-label" for="salary_visibility_flag">
                                    {{ __('hrms.employee.salary_visibility') }}
                                    <small class="text-muted d-block">{{ __('Allow employee to view their own salary information') }}</small>
                                </label>
                                @error('salary_visibility_flag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-lg-4">
                <!-- Form Actions -->
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-save"></i> {{ __('hrms.actions') }}
                        </h5>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <button type="submit" class="btn btn-brand-primary btn-lg">
                            <i class="fas fa-save"></i> {{ __('hrms.update') }} {{ __('Employee') }}
                        </button>
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> {{ __('hrms.cancel') }}
                        </a>
                    </div>
                </div>

                <!-- Employee Info -->
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> {{ __('Current Information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">
                            <div class="mb-2">
                                <strong>{{ __('hrms.employee_code') }}:</strong><br>
                                <code class="text-dark">{{ $employee->code }}</code>
                            </div>
                            
                            <div class="mb-2">
                                <strong>{{ __('Created') }}:</strong><br>
                                {{ $employee->created_at->format('Y-m-d H:i') }}
                            </div>
                            
                            <div class="mb-2">
                                <strong>{{ __('Last Updated') }}:</strong><br>
                                {{ $employee->updated_at->format('Y-m-d H:i') }}
                            </div>
                            
                            @if($employee->hire_date)
                                <div class="mb-2">
                                    <strong>{{ __('hrms.employee.years_of_service') }}:</strong><br>
                                    {{ $employee->hire_date->diffInYears(now()) }} {{ __('years') }}
                                </div>
                            @endif
                            
                            <div class="mb-2">
                                <strong>{{ __('hrms.employee.direct_reports') }}:</strong><br>
                                {{ $employee->directReports->count() }} {{ __('employees') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Log -->
                @if(auth()->user()->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator']))
                    <div class="card">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0">
                                <i class="fas fa-history"></i> {{ __('Recent Changes') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="small text-muted">
                                @forelse($employee->activities()->latest()->limit(3)->get() as $activity)
                                    <div class="mb-2">
                                        <div class="fw-bold">{{ $activity->description }}</div>
                                        <div>{{ $activity->created_at->format('Y-m-d H:i') }}</div>
                                        @if($activity->causer)
                                            <div>by {{ $activity->causer->name }}</div>
                                        @endif
                                    </div>
                                    @unless($loop->last)<hr class="my-2">@endunless
                                @empty
                                    <p class="mb-0">{{ __('No recent changes') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation and enhancements
        document.addEventListener('DOMContentLoaded', function() {
            const statusField = document.getElementById('status');
            const originalStatus = '{{ $employee->status }}';
            
            // Warn if changing status to terminated
            statusField.addEventListener('change', function() {
                if (this.value === 'terminated' && originalStatus !== 'terminated') {
                    if (!confirm('{{ __("Are you sure you want to terminate this employee? This action should be carefully considered.") }}')) {
                        this.value = originalStatus;
                    }
                }
            });
            
            // Manager validation - prevent self-assignment
            const managerField = document.getElementById('manager_id');
            const employeeId = '{{ $employee->id }}';
            
            managerField.addEventListener('change', function() {
                if (this.value === employeeId) {
                    alert('{{ __("hrms.employee.cannot_be_own_manager") }}');
                    this.value = '';
                }
            });
        });
    </script>
@endpush