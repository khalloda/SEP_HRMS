@extends('layouts.app')

@section('title', __('Attendance Summary Report'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 brand-dark-green mb-1">{{ __('Attendance Summary Report') }}</h2>
        <p class="text-muted mb-0">{{ __('Monthly attendance overview with productivity metrics and export options') }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="POST" action="{{ route('reports.saved.store') }}" class="d-inline">
          @csrf
          <input type="hidden" name="name" value="Attendance Summary">
          <input type="hidden" name="report_key" value="reports.attendance.summary">
          <input type="hidden" name="params" value='@json(request()->query())'>
          <input type="hidden" name="format" value="xlsx">
          <button class="btn btn-outline-primary" type="submit">
            <i class="fas fa-bookmark"></i> {{ __('Save Report') }}
          </button>
        </form>
        <a class="btn btn-outline-secondary" href="{{ route('reports.saved.index') }}">
            <i class="fas fa-list"></i> {{ __('Saved Reports') }}
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_employees']) }}</h4>
                <p class="card-text text-muted">{{ __('Employees Tracked') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_hours'], 2) }}</h4>
                <p class="card-text text-muted">{{ __('Total Hours') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="fas fa-bolt fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_overtime'], 2) }}</h4>
                <p class="card-text text-muted">{{ __('Overtime Hours') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['average_hours'], 2) }}</h4>
                <p class="card-text text-muted">{{ __('Average Hours / Employee') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="month" class="form-label">{{ __('Month') }}</label>
                <input type="month" id="month" name="month" class="form-control"
                       value="{{ $filters['month'] ?? now()->format('Y-m') }}">
            </div>

            <div class="col-md-4">
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

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-brand-primary">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('reports.attendance.summary') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                    </button>
                </div>
            </div>
        </form>

        @if(config('reports.use_new_exports'))
        <form id="attendance-summary-export-form" method="POST" action="{{ route('reports.exports.store', 'attendance-summary') }}" class="d-none">
            @csrf
            <input type="hidden" name="export_format" value="">
            <input type="hidden" name="month" value="{{ $filters['month'] ?? now()->format('Y-m') }}">
            <input type="hidden" name="department_id" value="{{ $filters['department_id'] ?? '' }}">
        </form>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Attendance Summary') }}</h5>
        <span class="badge bg-light text-dark">{{ number_format($attendance->count()) }} {{ __('employees') }}</span>
    </div>
    <div class="card-body p-0">
        @if($attendance->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Total Hours') }}</th>
                            <th>{{ __('Overtime Hours') }}</th>
                            <th>{{ __('Present Days') }}</th>
                            <th>{{ __('Late Days') }}</th>
                            <th>{{ __('Absent Days') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $record)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ optional($record['employee'])->display_name }}</strong>
                                    <br><small class="text-muted">{{ optional($record['employee'])->code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary">{{ optional(optional($record['employee'])->department)->name_en ?? '-' }}</span>
                            </td>
                            <td>{{ optional(optional($record['employee'])->position)->name_en ?? '-' }}</td>
                            <td>{{ number_format($record['total_hours'], 2) }}</td>
                            <td>{{ number_format($record['overtime_hours'], 2) }}</td>
                            <td>{{ $record['present_days'] }}</td>
                            <td>{{ $record['late_days'] }}</td>
                            <td>{{ $record['absent_days'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No attendance records found') }}</h5>
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
.brand-gold {
    color: var(--color-gold);
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
    const form = document.getElementById('attendance-summary-export-form');
    if (!form) {
        return;
    }

    form.querySelector('input[name="export_format"]').value = format;
    form.submit();
    @else
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export_format', format);
    window.location.href = currentUrl.toString();
    @endif
}
</script>
@endpush
