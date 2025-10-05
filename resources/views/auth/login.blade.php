@extends('layouts.app')

@section('title', __('Login'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg">
                <!-- Header with Logo -->
                <div class="card-header bg-brand-dark-green text-white text-center py-4">
                    <h3 class="mb-2">{{ __('HRMS Login') }}</h3>
                    <p class="mb-0 small">{{ __('Sarie Eldin & Partners') }}</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
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
                                       autofocus
                                       placeholder="{{ __('Enter your email address') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="{{ __('Enter your password') }}">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="remember" 
                                       name="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-brand-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> {{ __('Sign In') }}
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <div class="mb-2">
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    {{ __("Don't have an account? Register here") }}
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

            <!-- Development Info -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    {{ __('HRMS Development Build') }} - {{ config('app.env') }}
                </small>
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
        
        .form-control:focus {
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
        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField && !emailField.value) {
                emailField.focus();
            }
        });
    </script>
@endpush