@extends('layouts.app')

@section('title', __('Employee Directory Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Employee Directory Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Complete employee listing with filtering and export options') }}</p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="department_id" class="form-label">{{ __('Department') }}</label>
                <select name="department_id" id="department_id" class="form-select">
                    <option value="">{{ __('All Departments') }}</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                                {{ ($filters['department_id'] ?? '') == $department->id ? 'selected' : '' }}>
                            {{ $department->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="position_id" class="form-label">{{ __('Position') }}</label>
                <select name="position_id" id="position_id" class="form-select">
                    <option value="">{{ __('All Positions') }}</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}"
                                {{ ($filters['position_id'] ?? '') == $position->id ? 'selected' : '' }}>
                            {{ $position->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="employment_status" class="form-label">{{ __('Employment Status') }}</label>
                <select name="employment_status" id="employment_status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ ($filters['employment_status'] ?? '') == 'active' ? 'selected' : '' }}>
                        {{ __('Active') }}
                    </option>
                    <option value="inactive" {{ ($filters['employment_status'] ?? '') == 'inactive' ? 'selected' : '' }}>
                        {{ __('Inactive') }}
                    </option>
                    <option value="terminated" {{ ($filters['employment_status'] ?? '') == 'terminated' ? 'selected' : '' }}>
                        {{ __('Terminated') }}
                    </option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-brand-primary me-2">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('reports.employee.list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
            </div>
        </form>

        <!-- Save & Export Options -->
        <hr class="my-3">
        <div class="row align-items-center g-2">
            <div class="col-12 col-lg-6 d-flex gap-2">
                <form method="POST" action="{{ route('reports.saved.store') }}" class="d-inline">
                  @csrf
                  <input type="hidden" name="name" value="Employee List">
                  <input type="hidden" name="report_key" value="reports.employee.list">
                  <input type="hidden" name="params" value='@json(request()->query())'>
                  <input type="hidden" name="format" value="xlsx">
                  <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-bookmark"></i> {{ __('Save Report') }}
                  </button>
                </form>
                <a class="btn btn-outline-secondary" href="{{ route('reports.saved.index') }}">
                  <i class="fas fa-list"></i> {{ __('Saved Reports') }}
                </a>
            </div>
            <div class="col-12 col-lg-6">
                <h6 class="mb-2">{{ __('Export Options') }}</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                    </button>
                </div>
                @if(config('reports.use_new_exports'))
                    <form id="employee-list-export-form" class="d-none" method="POST" action="{{ route('reports.exports.store', 'employee-list') }}">
                        @csrf
                        <input type="hidden" name="export_format" value="">
                        <input type="hidden" name="department_id" value="{{ $filters['department_id'] ?? '' }}">
                        <input type="hidden" name="position_id" value="{{ $filters['position_id'] ?? '' }}">
                        <input type="hidden" name="employment_status" value="{{ $filters['employment_status'] ?? '' }}">
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Results -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Employee Directory') }}</h5>
        <span class="badge bg-light text-dark">{{ number_format($employees->count()) }} {{ __('employees') }}</span>
    </div>
    <div class="card-body p-0">
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Hire Date') }}</th>
                            <th>{{ __('Manager') }}</th>
                            <th>{{ __('Contact') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($employee->photo)
                                        <img src="{{ $employee->photo_url }}" alt="{{ $employee->display_name }}"
                                             class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2"
                                             style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $employee->display_name }}</strong>
                                        <br><small class="text-muted">{{ $employee->code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary">{{ $employee->department->name_en }}</span>
                            </td>
                            <td>{{ $employee->position->name_en }}</td>
                            <td>
                                @if($employee->employment_status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @elseif($employee->employment_status === 'inactive')
                                    <span class="badge bg-warning">{{ __('Inactive') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Terminated') }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $employee->hire_date->format('M j, Y') }}
                                <br><small class="text-muted">{{ $employee->hire_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($employee->manager)
                                    {{ $employee->manager->display_name }}
                                    <br><small class="text-muted">{{ $employee->manager->position->name_en }}</small>
                                @else
                                    <span class="text-muted">{{ __('No Manager') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($employee->work_email)
                                    <a href="mailto:{{ $employee->work_email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </a>
                                @endif
                                @if($employee->phone)
                                    <a href="tel:{{ $employee->phone }}" class="text-decoration-none ms-2">
                                        <i class="fas fa-phone text-success"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No employees found') }}</h5>
                <p class="text-muted">{{ __('Try adjusting your filters to see more results.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header-custom {
    background-color: var(--color-gold);
    color: white;
    font-weight: 600;
}

.table-header-custom {
    background-color: var(--color-dark-green);
    color: white;
}

.brand-dark-green {
    color: var(--color-dark-green);
}

.btn-brand-primary {
    background-color: var(--color-gold);
    border-color: var(--color-gold);
    color: white;
}

.btn-brand-primary:hover {
    background-color: var(--color-light-gold);
    border-color: var(--color-light-gold);
    color: white;
}

.badge.bg-outline-primary {
    color: var(--color-gold);
    border: 1px solid var(--color-gold);
    background-color: transparent;
}
</style>
@endpush

@push('scripts')
<script>
function exportReport(format) {
    @if(config('reports.use_new_exports'))
    const form = document.getElementById('employee-list-export-form');
    if (!form) {
        return;
    }

    form.querySelector('input[name="export_format"]').value = format;
    form.submit();
    @else
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('export_format', format);
    window.location.href = currentUrl.toString();
    @endif
}
</script>
@endpush
