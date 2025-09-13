@extends('layouts.app')

@section('title', __('My Profile'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('My Profile') }}</h2>
            <p class="text-muted mb-0">{{ __('Manage your personal information') }}</p>
        </div>
        <div>
            <a href="{{ route('employee-portal.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Editable Profile Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-user-edit"></i> {{ __('Personal Information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('employee-portal.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="personal_email" class="form-label">{{ __('Personal Email') }}</label>
                            <input type="email" class="form-control @error('personal_email') is-invalid @enderror"
                                   id="personal_email" name="personal_email" value="{{ old('personal_email', $employee->personal_email) }}">
                            @error('personal_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('Address') }}</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_name" class="form-label">{{ __('Emergency Contact Name') }}</label>
                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                   id="emergency_contact_name" name="emergency_contact_name"
                                   value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact_phone" class="form-label">{{ __('Emergency Contact Phone') }}</label>
                            <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                   id="emergency_contact_phone" name="emergency_contact_phone"
                                   value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}">
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-brand-primary">
                            <i class="fas fa-save"></i> {{ __('Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Read-only Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> {{ __('Employee Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($employee->photo)
                        <img src="{{ $employee->photo_url }}" alt="{{ $employee->display_name }}"
                             class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-2 mx-auto"
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    @endif
                    <h5 class="mb-0">{{ $employee->display_name }}</h5>
                    <p class="text-muted">{{ $employee->code }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">{{ __('Position') }}</small>
                        <strong>{{ $employee->position->name }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">{{ __('Department') }}</small>
                        <strong>{{ $employee->department->name }}</strong>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">{{ __('Hire Date') }}</small>
                        <strong>{{ $employee->hire_date->format('M j, Y') }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">{{ __('Status') }}</small>
                        <span class="badge bg-success">{{ $employee->employment_status }}</span>
                    </div>
                </div>

                @if($employee->manager)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Direct Manager') }}</small>
                    <strong>{{ $employee->manager->display_name }}</strong>
                    <br><small class="text-muted">{{ $employee->manager->position->name }}</small>
                </div>
                @endif

                @if($employee->birth_date)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Date of Birth') }}</small>
                    <strong>{{ $employee->birth_date->format('M j, Y') }}</strong>
                </div>
                @endif

                @if($employee->work_email)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Work Email') }}</small>
                    <strong>{{ $employee->work_email }}</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- Profile Completion -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> {{ __('Profile Completion') }}</h5>
            </div>
            <div class="card-body">
                @php
                    $fields = [
                        'phone' => __('Phone Number'),
                        'personal_email' => __('Personal Email'),
                        'address' => __('Address'),
                        'emergency_contact_name' => __('Emergency Contact Name'),
                        'emergency_contact_phone' => __('Emergency Contact Phone')
                    ];
                    $completed = 0;
                    foreach ($fields as $field => $label) {
                        if (!empty($employee->$field)) {
                            $completed++;
                        }
                    }
                    $percentage = round(($completed / count($fields)) * 100);
                @endphp

                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <span class="ms-3 fw-bold">{{ $percentage }}%</span>
                </div>

                <h6 class="mb-2">{{ __('Complete these fields:') }}</h6>
                <ul class="list-unstyled mb-0">
                    @foreach($fields as $field => $label)
                        <li class="small">
                            @if(empty($employee->$field))
                                <i class="fas fa-circle text-danger me-2"></i>{{ $label }}
                            @else
                                <i class="fas fa-check-circle text-success me-2"></i>{{ $label }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Account Security -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-shield-alt"></i> {{ __('Account Security') }}</h5>
            </div>
            <div class="card-body">
                <p class="mb-3">{{ __('To change your password or update login credentials, please contact HR or IT Administration.') }}</p>

                <div class="mb-2">
                    <small class="text-muted d-block">{{ __('Username') }}</small>
                    <strong>{{ Auth::user()->email }}</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Last Login') }}</small>
                    <strong>{{ Auth::user()->updated_at->diffForHumans() }}</strong>
                </div>

                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        {{ __('For security changes, contact HR at hr@sarieldin.com or IT at it@sarieldin.com') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header-custom {
    background-color: var(--color-gold);
    color: white;
    font-weight: 600;
}

.btn-brand-primary {
    background-color: var(--color-gold);
    border-color: var(--color-gold);
    color: white;
}

.btn-brand-primary:hover {
    background-color: var(--color-light-gold);
    border-color: var(--color-light-gold);
    color: white;
}

.brand-dark-green {
    color: var(--color-dark-green);
}
</style>
@endpush