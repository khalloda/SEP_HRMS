@extends('layouts.app')

@section('title', __('Profile'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 brand-dark-green">{{ __('My Profile') }}</h1>
        <div>
            @if($user->employee)
                <span class="badge bg-success">{{ __('Linked to Employee') }}</span>
            @else
                <span class="badge bg-warning">{{ __('Not Linked') }}</span>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> {{ __('Account Information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3">{{ __('Change Password') }} ({{ __('Optional') }})</h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           minlength="8">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           minlength="8">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-brand-primary">
                                <i class="fas fa-save"></i> {{ __('Update Profile') }}
                            </button>
                            <a href="/" class="btn btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Employee Linking -->
            @if(!$user->employee)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-link"></i> {{ __('Link to Employee Record') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            {{ __('Link your account to an employee record to access full HRMS features including payroll, contracts, and documents.') }}
                        </p>
                        
                        <form method="POST" action="{{ route('profile.link-employee') }}">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="employee_id" class="form-label">{{ __('Select Employee') }}</label>
                                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                                            id="employee_id" 
                                            name="employee_id" 
                                            required>
                                        <option value="">{{ __('Choose employee record...') }}</option>
                                        @foreach(App\Models\Employee::whereDoesntHave('user')->active()->ordered()->get() as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->code }} - {{ $employee->display_name }}
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
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-link"></i> {{ __('Link Account') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Account Summary -->
            <div class="card mb-4">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i> {{ __('Account Summary') }}
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="w-50">{{ __('Name') }}:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Member Since') }}:</th>
                            <td>{{ $user->created_at->format('M Y') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Last Login') }}:</th>
                            <td>{{ $user->updated_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Employee Link') }}:</th>
                            <td>
                                @if($user->employee)
                                    <span class="badge bg-success">{{ __('Linked') }}</span>
                                @else
                                    <span class="badge bg-warning">{{ __('Not Linked') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Employee Information -->
            @if($user->employee)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-id-badge"></i> {{ __('Employee Information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="w-50">{{ __('Code') }}:</th>
                                <td><code>{{ $user->employee->code }}</code></td>
                            </tr>
                            <tr>
                                <th>{{ __('Department') }}:</th>
                                <td>{{ $user->employee->department?->name ?? __('N/A') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Position') }}:</th>
                                <td>{{ $user->employee->position?->name ?? __('N/A') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Status') }}:</th>
                                <td>
                                    <span class="badge bg-{{ $user->employee->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->employee->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Hire Date') }}:</th>
                                <td>{{ $user->employee->hire_date?->format('Y-m-d') ?? __('N/A') }}</td>
                            </tr>
                        </table>
                        
                        <div class="d-grid">
                            <a href="{{ route('employees.show', $user->employee) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye"></i> {{ __('View Employee Record') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Roles -->
            @if($user->roles->count() > 0)
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-user-tag"></i> {{ __('System Roles') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->roles as $role)
                            <span class="badge bg-info me-1 mb-1">{{ $role->name }}</span>
                        @endforeach
                        
                        @if($user->roles->isEmpty())
                            <p class="text-muted mb-0 small">{{ __('No roles assigned yet') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> {{ __('Quick Actions') }}
                    </h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="/" class="btn btn-outline-secondary">
                        <i class="fas fa-home"></i> {{ __('Dashboard') }}
                    </a>
                    @if($user->employee)
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-users"></i> {{ __('View Employees') }}
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            function validatePasswordMatch() {
                if (password.value && passwordConfirm.value) {
                    if (password.value !== passwordConfirm.value) {
                        passwordConfirm.setCustomValidity('Passwords do not match');
                    } else {
                        passwordConfirm.setCustomValidity('');
                    }
                }
            }
            
            password.addEventListener('input', validatePasswordMatch);
            passwordConfirm.addEventListener('input', validatePasswordMatch);
        });
    </script>
@endpush