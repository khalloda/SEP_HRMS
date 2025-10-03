@extends('layouts.app')

@section('title', __('Employee Salary Structures'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Salary Structures for :name', ['name' => $employee->display_name]) }}</h1>
            <p class="text-muted mb-0">{{ __('Review historical and active salary structures for this employee.') }}</p>
        </div>
        <a href="{{ route('employees.salary-structures.create', $employee) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            <span class="ms-1">{{ __('Add Salary Structure') }}</span>
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Effective From') }}</th>
                            <th>{{ __('Effective To') }}</th>
                            <th>{{ __('Notes') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($structures as $structure)
                            <tr>
                                <td>{{ $structure->currency }}</td>
                                <td>{{ optional($structure->effective_from)->format('Y-m-d') }}</td>
                                <td>{{ optional($structure->effective_to)->format('Y-m-d') ?? '—' }}</td>
                                <td class="text-wrap" style="max-width: 220px;">{{ $structure->notes ?? '—' }}</td>
                                <td>
                                    @if($structure->is_expired)
                                        <span class="badge bg-secondary">{{ __('Expired') }}</span>
                                    @elseif($structure->isFuture())
                                        <span class="badge bg-info text-dark">{{ __('Scheduled') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('employees.salary-structures.show', [$employee, $structure]) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $structure)
                                            <a href="{{ route('employees.salary-structures.edit', [$employee, $structure]) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ __('No salary structures have been defined for this employee yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
