@extends('layouts.app')

@section('title', __('Add Salary Component'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ __('Add Salary Component') }}</h1>
        <p class="text-muted mb-0">{{ __('Define a new earning, deduction, or informational element for payroll calculations.') }}</p>
    </div>
    <a href="{{ route('salary-components.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> {{ __('Back to list') }}
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Component Details') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('salary-components.store') }}" class="needs-validation" novalidate>
            @csrf

            @include('salary-components._form')

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('salary-components.index') }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-brand-primary">
                    <i class="fas fa-save"></i> {{ __('Create Component') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<div>
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
</div>