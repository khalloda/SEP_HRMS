@extends('layouts.app')

@section('title', $salaryComponent->display_name)

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ $salaryComponent->display_name }}</h1>
        <p class="text-muted mb-0">{{ __('View component usage and activity history.') }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('salary-components.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to list') }}
        </a>
        @can('update', $salaryComponent)
        <a href="{{ route('salary-components.edit', $salaryComponent) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> {{ __('Edit') }}
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
                <h5 class="mb-0">{{ __('Component Details') }}</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5">{{ __('Code') }}</dt>
                    <dd class="col-7"><span class="badge bg-secondary">{{ $salaryComponent->code }}</span></dd>

                    <dt class="col-5">{{ __('Type') }}</dt>
                    <dd class="col-7">{{ __(\App\Models\SalaryComponent::TYPES[$salaryComponent->comp_type]) }}</dd>

                    <dt class="col-5">{{ __('Calculation') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->calc_mode_display_name }}</dd>

                    <dt class="col-5">{{ __('Taxable') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->taxable ? __('Yes') : __('No') }}</dd>

                    <dt class="col-5">{{ __('Priority') }}</dt>
                    <dd class="col-7">{{ $salaryComponent->priority_order }}</dd>

                    <dt class="col-5">{{ __('Visible To') }}</dt>
                    <dd class="col-7">
                        @if(empty($salaryComponent->visible_to_roles))
                        <span class="text-success">{{ __('All roles') }}</span>
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
                <h5 class="mb-0">{{ __('Recent Usage in Salary Structures') }}</h5>
                <span class="badge bg-secondary">{{ $salaryStructures->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($salaryStructures->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Effective From') }}</th>
                                <th>{{ __('Effective To') }}</th>
                                <th>{{ __('Priority') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryStructures as $structure)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', $structure->employee) }}">
                                        {{ $structure->employee?->full_name ?? __('N/A') }}
                                    </a>
                                </td>
                                <td>{{ optional($structure->effective_from)->format('Y-m-d') }}</td>
                                <td>{{ optional($structure->effective_to)->format('Y-m-d') ?? __('—') }}</td>
                                <td>{{ $structure->pivot->priority_order ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    {{ __('No salary structures currently use this component.') }}
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Recent Activity') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($activities->isNotEmpty())
                <ul class="list-group list-group-flush">
                    @foreach($activities as $activity)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ ucfirst($activity->description ?? __('Updated')) }}</div>
                            <small class="text-muted">{{ optional($activity->causer)->name ?? __('System') }}</small>
                        </div>
                        <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-4 text-center text-muted">
                    {{ __('No recent activity recorded for this component.') }}
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