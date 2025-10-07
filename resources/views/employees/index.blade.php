@extends('layouts.app')

@section('title', __('hrms.employees'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0 brand-dark-green">{{ __('hrms.employees') }}</h1>

    <div class="d-flex gap-2">
        {{-- Export temporarily disabled --}}
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" disabled>
                <i class="fas fa-download"></i> {{ __('hrms.employee.export') }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Excel ({{ __('common.coming_soon') }})</a></li>
                <li><a class="dropdown-item" href="#">PDF ({{ __('common.coming_soon') }})</a></li>
                <li><a class="dropdown-item" href="#">CSV ({{ __('common.coming_soon') }})</a></li>
            </ul>
        </div>

        <a href="{{ route('employees.create') }}" class="btn btn-brand-primary">
            <i class="fas fa-plus"></i> {{ __('hrms.add_employee') }}
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Search and Filter Form -->
<div class="card mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">
            <i class="fas fa-search"></i> {{ __('hrms.search') }} & {{ __('hrms.filter') }}
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-3">
            <!-- Search -->
            <div class="col-md-4">
                <label for="search" class="form-label">{{ __('hrms.search_employees') }}</label>
                <input type="text"
                    class="form-control"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('hrms.search_employees') }}">
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <label for="status" class="form-label">{{ __('hrms.status.status') }}</label>
                <select name="status" id="status" class="form-select">
                    <option value="">{{ __('common.status') }}</option>
                    @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Department Filter -->
            <div class="col-md-2">
                <label for="department_id" class="form-label">{{ __('hrms.department') }}</label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">{{ __('common.all_types') }}</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Position Filter -->
            <div class="col-md-2">
                <label for="position_id" class="form-label">{{ __('hrms.position') }}</label>
                <select name="position_id" id="position_id" class="form-select">
                    <option value="">{{ __('common.all_types') }}</option>
                    @foreach($positions as $position)
                    <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                        {{ $position->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Employment Type Filter -->
            <div class="col-md-2">
                <label for="employment_type_id" class="form-label">{{ __('hrms.employment_type') }}</label>
                <select name="employment_type_id" id="employment_type_id" class="form-select">
                    <option value="">{{ __('common.all_types') ?? __('common.all') }}</option>
                    @foreach($employmentTypes as $type)
                    <option value="{{ $type->id }}" {{ request('employment_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Manager Filter -->
            <div class="col-md-3">
                <label for="manager_id" class="form-label">{{ __('hrms.manager') }}</label>
                <select name="manager_id" id="manager_id" class="form-select">
                    <option value="">{{ __('hrms.all_managers') ?? __('common.all') }}</option>
                    @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" {{ request('manager_id') == $manager->id ? 'selected' : '' }}>
                        {{ $manager->first_name }} {{ $manager->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filters -->
            <div class="col-md-2">
                <label for="hire_date_from" class="form-label">{{ __('hrms.hire_date') }} {{ __('common.from') }}</label>
                <input type="date"
                    class="form-control"
                    id="hire_date_from"
                    name="hire_date_from"
                    value="{{ request('hire_date_from') }}">
            </div>

            <div class="col-md-2">
                <label for="hire_date_to" class="form-label">{{ __('hrms.hire_date') }} {{ __('common.to') }}</label>
                <input type="date"
                    class="form-control"
                    id="hire_date_to"
                    name="hire_date_to"
                    value="{{ request('hire_date_to') }}">
            </div>

            <!-- Sort Options -->
            <div class="col-md-2">
                <label for="sort_by" class="form-label">{{ __('common.sort_by') }}</label>
                <select name="sort_by" id="sort_by" class="form-select">
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                    <option value="code" {{ request('sort_by') === 'code' ? 'selected' : '' }}>{{ __('hrms.employee_code') }}</option>
                    <option value="hire_date" {{ request('sort_by') === 'hire_date' ? 'selected' : '' }}>{{ __('hrms.hire_date') }}</option>
                </select>
            </div>

            <div class="col-md-1">
                <label for="sort_dir" class="form-label">{{ __('common.direction') }}</label>
                <select name="sort_dir" id="sort_dir" class="form-select">
                    <option value="asc" {{ request('sort_dir') === 'asc' ? 'selected' : '' }}>{{ __('common.ascending') }}</option>
                    <option value="desc" {{ request('sort_dir') === 'desc' ? 'selected' : '' }}>{{ __('common.descending') }}</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-brand-primary me-2">
                    <i class="fas fa-search"></i> {{ __('hrms.search') }}
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('hrms.clear') }}
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Results Summary -->
<div class="row mb-3">
    <div class="col-md-6">
        <p class="mb-0 text-muted">
            {{ __('Showing :from to :to of :total employees', [
                    'from' => $employees->firstItem() ?? 0,
                    'to' => $employees->lastItem() ?? 0,
                    'total' => $employees->total()
                ]) }}
        </p>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}"
                class="btn btn-outline-secondary {{ request('per_page', 15) == 15 ? 'active' : '' }}">{{ trans_choice('common.results_per_page', 15, ['count' => 15]) }}</a>
            <a href="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}"
                class="btn btn-outline-secondary {{ request('per_page') == 25 ? 'active' : '' }}">{{ trans_choice('common.results_per_page', 25, ['count' => 25]) }}</a>
            <a href="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}"
                class="btn btn-outline-secondary {{ request('per_page') == 50 ? 'active' : '' }}">{{ trans_choice('common.results_per_page', 50, ['count' => 50]) }}</a>
        </div>
    </div>
</div>

<!-- Employees Table -->
<div class="card">
    <div class="card-body p-0">
        @if($employees->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header-custom">
                    <tr>
                        <th>{{ __('hrms.employee_code') }}</th>
                        <th>{{ __('hrms.employee.full_name') }}</th>
                        <th>{{ __('hrms.department') }}</th>
                        <th>{{ __('hrms.position') }}</th>
                        <th>{{ __('hrms.hire_date') }}</th>
                        <th>{{ __('hrms.status.status') }}</th>
                        <th class="text-center">{{ __('hrms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr>
                        <td>
                            <code class="text-dark">{{ $employee->code }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- Employee Photo Thumbnail -->
                                <img src="{{ $employee->photo_url }}"
                                    alt="{{ $employee->display_name }}"
                                    class="rounded-circle me-3 border border-2 border-brand-gold"
                                    style="width: 40px; height: 40px; object-fit: cover;">

                                <div>
                                    <strong>{{ $employee->display_name }}</strong>
                                    @if($employee->email)
                                    <br><small class="text-muted">{{ $employee->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $employee->department?->name ?? __('common.n_a') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $employee->position?->name ?? __('common.n_a') }}</span>
                        </td>
                        <td>
                            {{ $employee->hire_date?->format('Y-m-d') ?? __('common.n_a') }}
                            @if($employee->hire_date)
                            <br><small class="text-muted">{{ $employee->hire_date->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td>
                            @switch($employee->status)
                            @case('active')
                            <span class="badge bg-success">{{ __('hrms.active') }}</span>
                            @break
                            @case('inactive')
                            <span class="badge bg-warning">{{ __('hrms.inactive') }}</span>
                            @break
                            @case('terminated')
                            <span class="badge bg-danger">{{ __('hrms.terminated') }}</span>
                            @break
                            @case('on_leave')
                            <span class="badge bg-info">{{ __('hrms.on_leave') }}</span>
                            @break
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('employees.show', $employee) }}"
                                    class="btn btn-outline-primary"
                                    title="{{ __('hrms.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('employees.edit', $employee) }}"
                                    class="btn btn-outline-warning"
                                    title="{{ __('hrms.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-light">
            {{ $employees->links('pagination::bootstrap-4') }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('hrms.no_results') }}</h5>
            <p class="text-muted">{{ __('No employees found matching your criteria.') }}</p>

            <a href="{{ route('employees.create') }}" class="btn btn-brand-primary">
                <i class="fas fa-plus"></i> {{ __('hrms.add_employee') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="status"], select[name="department_id"], select[name="position_id"], select[name="employment_type_id"], select[name="manager_id"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush