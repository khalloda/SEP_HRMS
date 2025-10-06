@extends('layouts.app')

@section('title', $salaryComponent->display_name)

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ $salaryComponent->display_name }}</h1>
        <p class="text-muted mb-0">{{ __('hrms.salary_component.view_usage_help') }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('salary-components.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('common.back') }}
        </a>
        @can('update', $salaryComponent)
        <a href="{{ route('salary-components.edit', $salaryComponent) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> {{ __('common.edit') }}
        </a>
        @endcan
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('hrms.salary_component.details') }}</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">{{ __('hrms.salary_component.code') }}</dt>
                    <dd class="col-7"><span class="badge bg-secondary">{{ $salaryComponent->code }}</span></dd>

                    <dt class="col-5">{{ __('hrms.salary_component.type') }}</dt>
                    <dd class="col-7">{{ __(\App\Models\SalaryComponent::TYPES[$salaryComponent->comp_type]) }}</dd>

                    <dt class="col-5">{{ __('hrms.salary_component.calculation') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->calc_mode_display_name }}</dd>

                    <dt class="col-5">{{ __('hrms.salary_component.taxable') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->taxable ? __('common.yes') : __('common.no') }}</dd>

                    <dt class="col-5">{{ __('hrms.salary_component.priority') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->priority_order }}</dd>

                    <dt class="col-5">{{ __('hrms.salary_component.visible_to') }}</dt>
                    <dd class="col-7">
                        @if(empty($salaryComponent->visible_to_roles))
                        <span class="text-success">{{ __('hrms.salary_component.all_roles') }}</span>
                        @else
                        {{ implode(', ', $salaryComponent->visible_to_roles) }}
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('hrms.salary_component.recent_usage') }}</h5>
                <span class="badge bg-secondary">{{ $salaryStructures->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($salaryStructures->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('hrms.salary_component.employee') }}</th>
                                <th>{{ __('hrms.salary_component.effective_from') }}</th>
                                <th>{{ __('hrms.salary_component.effective_to') }}</th>
                                <th>{{ __('hrms.salary_component.priority') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryStructures as $structure)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', $structure->employee) }}">
                                        {{ $structure->employee?->full_name ?? __('common.n_a') }}
                                    </a>
                                </td>
                                <td>{{ optional($structure->effective_from)->format('Y-m-d') }}</td>
                                <td>{{ optional($structure->effective_to)->format('Y-m-d') ?? '—' }}</td>
                                <td>{{ $structure->pivot->priority_order ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    {{ __('hrms.salary_component.no_usage') }}
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('hrms.salary_component.recent_activity') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($activities->isNotEmpty())
                <ul class="list-group list-group-flush">
                    @foreach($activities as $activity)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ ucfirst($activity->description ?? __('hrms.salary_component.updated')) }}</div>
                            <small class="text-muted">{{ optional($activity->causer)->name ?? __('hrms.salary_component.system') }}</small>
                        </div>
                        <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-4 text-center text-muted">
                    {{ __('hrms.salary_component.no_recent_activity') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
<div>
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
</div>