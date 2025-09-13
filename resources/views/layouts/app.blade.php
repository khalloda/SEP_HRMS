<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HRMS') - {{ config('app.name', 'Sarie Eldin & Partners') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --color-gold: #c6a44a;
            --color-dark-green: #2e4029;
            --color-cream: #f9f5e6;
            --color-light-gold: #d4b666;
            --color-darker-green: #1f2b1c;
        }
        
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Figtree', sans-serif" }};
            background-color: var(--color-cream);
        }
        
        .brand-gold { color: var(--color-gold); }
        .brand-dark-green { color: var(--color-dark-green); }
        .brand-cream { color: var(--color-cream); }
        
        .bg-brand-gold { background-color: var(--color-gold); }
        .bg-brand-dark-green { background-color: var(--color-dark-green); }
        .bg-brand-cream { background-color: var(--color-cream); }
        
        .border-brand-gold { border-color: var(--color-gold); }
        
        .btn-brand-primary {
            background-color: var(--color-gold);
            border-color: var(--color-gold);
            color: white;
        }
        
        .btn-brand-primary:hover {
            background-color: var(--color-light-gold);
            border-color: var(--color-light-gold);
        }
        
        .btn-brand-secondary {
            background-color: var(--color-dark-green);
            border-color: var(--color-dark-green);
            color: white;
        }
        
        .btn-brand-secondary:hover {
            background-color: var(--color-darker-green);
            border-color: var(--color-darker-green);
        }
        
        .navbar-brand-custom {
            background: linear-gradient(135deg, var(--color-dark-green) 0%, var(--color-gold) 100%);
            color: white;
        }
        
        .card-header-custom {
            background-color: var(--color-gold);
            color: white;
            font-weight: 600;
        }
        
        .table-header-custom {
            background-color: var(--color-dark-green);
            color: white;
        }
        
        /* RTL Support */
        [dir="rtl"] {
            text-align: right;
        }
        
        [dir="rtl"] .float-start {
            float: right !important;
        }
        
        [dir="rtl"] .float-end {
            float: left !important;
        }
        
        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        
        [dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-brand-custom shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">
                    {{ __('hrms.dashboard') }} - {{ config('app.name', 'Sarie Eldin & Partners') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('employees.index') }}">
                                {{ __('hrms.employees') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('documents.index') }}">
                                {{ __('hrms.documents') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="payrollDropdown" role="button" data-bs-toggle="dropdown">
                                {{ __('hrms.payroll') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('salary-components.index') }}">
                                    <i class="fas fa-list"></i> {{ __('Salary Components') }}
                                </a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <!-- Language Toggle -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                                {{ app()->getLocale() === 'ar' ? __('hrms.arabic') : __('hrms.english') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">
                                        {{ __('hrms.english') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">
                                        {{ __('hrms.arabic') }}
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- User Menu -->
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <h6 class="dropdown-header">
                                            {{ Auth::user()->name }}
                                            @if(Auth::user()->employee)
                                                <br><small class="text-muted">{{ Auth::user()->employee->code }}</small>
                                            @endif
                                        </h6>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user-circle"></i> {{ __('My Profile') }}
                                    </a></li>
                                    @if(Auth::user()->employee)
                                        <li><a class="dropdown-item" href="{{ route('employees.show', Auth::user()->employee) }}">
                                            <i class="fas fa-id-card"></i> {{ __('My Employee Record') }}
                                        </a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus"></i> {{ __('Register') }}
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="container py-3">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Content -->
        <main class="container my-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-brand-dark-green text-white mt-5 py-4">
            <div class="container text-center">
                <p class="mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Sarie Eldin & Partners') }}. 
                    {{ __('All rights reserved.') }}
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>