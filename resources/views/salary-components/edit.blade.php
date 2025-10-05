@extends('layouts.app')

@section('title', __('Edit Salary Component'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ __('Edit Salary Component') }}</h1>
        <p class="text-muted mb-0">{{ __('Update earning, deduction, or informational component details.') }}</p>
    </div>
    <a href="{{ route('salary-components.show', $salaryComponent) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> {{ __('Back to component') }}
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $salaryComponent->display_name }}</h5>
        <span class="badge bg-secondary">{{ $salaryComponent->code }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('salary-components.update', $salaryComponent) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            @include('salary-components._form', ['salaryComponent' => $salaryComponent])

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('salary-components.show', $salaryComponent) }}" class="btn btn-outline-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> {{ __('Update Component') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>