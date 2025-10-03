@extends('layouts.app')

@section('title', __('Salary Structure Details'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Salary Structure for :name', ['name' => $employee->display_name]) }}</h1>
            <p class="text-muted mb-0">{{ __('Review the breakdown of earnings, deductions, and info components for this structure.') }}</p>
        </div>
        <a href="{{ route('employees.salary-structures.index', $employee) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
            <span class="ms-1">{{ __('Back to structures') }}</span>
        </a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Structure Summary') }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">{{ __('Currency') }}</dt>
                        <dd class="col-7">{{ $salaryStructure->currency }}</dd>

                        <dt class="col-5 text-muted">{{ __('Effective From') }}</dt>
                        <dd class="col-7">{{ optional($salaryStructure->effective_from)->format('Y-m-d') }}</dd>

                        <dt class="col-5 text-muted">{{ __('Effective To') }}</dt>
                        <dd class="col-7">{{ optional($salaryStructure->effective_to)->format('Y-m-d') ?? '—' }}</dd>

                        <dt class="col-5 text-muted">{{ __('Status') }}</dt>
                        <dd class="col-7">
                            @if($salaryStructure->is_expired)
                                <span class="badge bg-secondary">{{ __('Expired') }}</span>
                            @elseif($salaryStructure->is_future)
                                <span class="badge bg-info text-dark">{{ __('Scheduled') }}</span>
                            @else
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @endif
                        </dd>

                        <dt class="col-5 text-muted">{{ __('Notes') }}</dt>
                        <dd class="col-7">{{ $salaryStructure->notes ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Components') }}</h5>
                </div>
                <div class="card-body">
                    @php
                    $grouped = $salaryStructure->structureComponents->groupBy(fn ($component) => $component->component->comp_type ?? 'other');
                    @endphp

                    @forelse($grouped as $type => $items)
                    <div class="mb-4">
                        <h6 class="fw-semibold text-uppercase small text-muted">{{ ucfirst($type) }}</h6>
                        <div class="list-group">
                            @foreach($items as $item)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $item->component->name ?? __('Unknown Component') }}</strong>
                                        <div class="text-muted small">{{ $item->component->code ?? '—' }}</div>
                                        @if($item->formula_expr)
                                        <div class="small fst-italic text-primary">{{ __('Formula: :expr', ['expr' => $item->formula_expr]) }}</div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @if($item->value_numeric !== null)
                                        <div class="fw-semibold">{{ number_format((float) $item->value_numeric, 2) }}</div>
                                        @else
                                        <div class="text-muted">—</div>
                                        @endif
                                        <div class="text-muted small">{{ __('Priority: :priority', ['priority' => $item->priority_order]) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p class="text-muted mb-0">{{ __('No components are defined for this structure.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection