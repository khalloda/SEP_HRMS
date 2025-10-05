@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <!-- Header with Logo -->
                <div class="card-header bg-brand-dark-green text-white text-center py-4">
                    <h3 class="mb-2">{{ __('Create Account') }}</h3>
                    <p class="mb-0 small">{{ __('Join Sarie Eldin & Partners HRMS') }}</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus
                                           maxlength="255"
                                           placeholder="{{ __('Enter your full name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="col-12 mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required
                                           maxlength="255"
                                           placeholder="{{ __('Enter your email address') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">{{ __('Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           minlength="8"
                                           placeholder="{{ __('Enter password') }}">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    {{ __('Minimum 8 characters required') }}
                                </div>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           minlength="8"
                                           placeholder="{{ __('Confirm password') }}">
                                </div>
                            </div>

                            <!-- Employee Linking -->
                            <div class="col-12 mb-3">
                                <label for="employee_id" class="form-label">{{ __('Link to Employee Record') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                                            id="employee_id" 
                                            name="employee_id">
                                        <option value="">{{ __('Select Employee (Optional)') }}</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->code }} - {{ $employee->first_name }} {{ $employee->last_name }}
                                                @if($employee->email)
                                                    ({{ $employee->email }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    {{ __('Link your account to an existing employee record for full access') }}
                                </div>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-brand-primary btn-lg">
                                <i class="fas fa-user-plus"></i> {{ __('Create Account') }}
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <div class="mb-2">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    {{ __('Already have an account? Sign in here') }}
                                </a>
                            </div>
                            <div>
                                <small class="text-muted">
                                    <a href="/" class="text-decoration-none">
                                        <i class="fas fa-home"></i> {{ __('Back to Homepage') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="card mt-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> {{ __('Registration Information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong>{{ __('Account Types') }}:</strong>
                        </div>
                        <ul class="mb-3">
                            <li><strong>{{ __('Linked Account') }}:</strong> {{ __('Connect to existing employee record for full HRMS access') }}</li>
                            <li><strong>{{ __('Standalone Account') }}:</strong> {{ __('Basic access - can be linked later') }}</li>
                        </ul>
                        
                        <div class="mb-2">
                            <strong>{{ __('Next Steps') }}:</strong>
                        </div>
                        <ol class="mb-0">
                            <li>{{ __('Complete registration') }}</li>
                            <li>{{ __('Verify your account via dashboard') }}</li>
                            <li>{{ __('Contact HR admin for role assignment if needed') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, var(--color-cream) 0%, #f8f9fa 100%);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .input-group-text {
            background-color: var(--color-cream);
            border-color: #dee2e6;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--color-gold);
            box-shadow: 0 0 0 0.25rem rgba(198, 164, 74, 0.25);
        }
        
        .btn-brand-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(198, 164, 74, 0.5);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            function validatePasswordMatch() {
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Passwords do not match');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
            }
            
            password.addEventListener('input', validatePasswordMatch);
            passwordConfirm.addEventListener('input', validatePasswordMatch);
            
            // Auto-select employee if email matches
            const emailField = document.getElementById('email');
            const employeeSelect = document.getElementById('employee_id');
            
            emailField.addEventListener('blur', function() {
                const email = this.value.toLowerCase();
                if (email) {
                    // Look for matching employee email in options
                    for (let option of employeeSelect.options) {
                        if (option.text.toLowerCase().includes(email)) {
                            employeeSelect.value = option.value;
                            break;
                        }
                    }
                }
            });
        });
    </script>
@endpush