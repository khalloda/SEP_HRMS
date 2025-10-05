@extends('layouts.app')

@section('title', __('Payroll Summary Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Payroll Summary Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Monthly payroll analysis with departmental breakdown') }}</p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_employees']) }}</h4>
                <p class="card-text text-muted">{{ __('Employees Paid') }}</p>
            </div>
        </div>
    </div>

    @can('view-net-gross-salary')
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_gross'], 0) }}</h4>
                <p class="card-text text-muted">{{ __('Total Gross Salary') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-dark-green mb-2">
                    <i class="fas fa-calculator fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_net'], 0) }}</h4>
                <p class="card-text text-muted">{{ __('Total Net Salary') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-minus-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total_deductions'], 0) }}</h4>
                <p class="card-text text-muted">{{ __('Total Deductions') }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-9">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            {{ __('Salary amounts are restricted based on your role. Contact HR or Accounting for detailed salary information.') }}
        </div>
    </div>
    @endcan
</div>

<!-- Filters and Export -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    @if(config('reports.use_new_exports'))
        <form id="payroll-summary-export-form" method="POST" action="{{ route('reports.exports.store', 'payroll-summary') }}" class="d-none">
            @csrf
            <input type="hidden" name="export_format" value="">
            <input type="hidden" name="month" value="{{ $filters['month'] ?? '' }}">
            <input type="hidden" name="department_id" value="{{ $filters['department_id'] ?? '' }}">
        </form>
    @endif
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="month" class="form-label">{{ __('Pay Month') }}</label>
                <input type="month" name="month" id="month" class="form-control"
                       value="{{ $filters['month'] ?? $month }}">
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

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-brand-primary me-2">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('reports.payroll.summary') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
            </div>
        </form>

        <hr class="my-3">
        <div class="row">
            <div class="col-12">
                <h6 class="mb-2">{{ __('Export Options') }}</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@can('view-net-gross-salary')
<!-- Department Breakdown -->
@if($summary['by_department']->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Payroll by Department') }} - {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-header-custom">
                    <tr>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Employees') }}</th>
                        <th>{{ __('Total Gross') }}</th>
                        <th>{{ __('Total Net') }}</th>
                        <th>{{ __('Average Salary') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['by_department'] as $department => $data)
                    <tr>
                        <td><strong>{{ $department }}</strong></td>
                        <td>{{ number_format($data['count']) }}</td>
                        <td>{{ number_format($data['gross'], 0) }}</td>
                        <td>{{ number_format($data['net'], 0) }}</td>
                        <td>{{ number_format($data['net'] / $data['count'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endcan

<!-- Payslip Details -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Payslip Details') }} - {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h5>
        <span class="badge bg-light text-dark">{{ number_format($payslips->count()) }} {{ __('payslips') }}</span>
    </div>
    <div class="card-body p-0">
        @if($payslips->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Position') }}</th>
                            @can('view-net-gross-salary')
                            <th>{{ __('Gross Salary') }}</th>
                            <th>{{ __('Deductions') }}</th>
                            <th>{{ __('Net Salary') }}</th>
                            @endcan
                            <th>{{ __('Pay Period') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payslips as $payslip)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ optional($payslip->employee)->display_name ?? $payslip->employee_name }}</strong>
                                    <br><small class="text-muted">{{ optional($payslip->employee)->code ?? $payslip->employee_code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary">{{ optional(optional($payslip->employee)->department)->name_en ?? $payslip->department_name ?? '-' }}</span>
                            </td>
                            <td>{{ optional(optional($payslip->employee)->position)->name_en ?? $payslip->position_name ?? '-' }}</td>
                            @can('view-net-gross-salary')
                            <td>{{ number_format($payslip->gross_salary ?? $payslip->gross_pay ?? 0, 0) }}</td>
                            <td>{{ number_format($payslip->total_deductions, 0) }}</td>
                            <td><strong>{{ number_format($payslip->net_salary ?? $payslip->net_pay ?? 0, 0) }}</strong></td>
                            @endcan
                            <td>
                                {{ optional($payslip->pay_period_start)->format('M j') ?? '-' }} - {{ optional($payslip->pay_period_end)->format('M j, Y') ?? '-' }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('payslips.show', $payslip) }}" class="btn btn-outline-primary" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('payslips.download-pdf', $payslip) }}" class="btn btn-outline-success" title="{{ __('Download PDF') }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No payslips found') }}</h5>
                <p class="text-muted">{{ __('No payroll data available for the selected period.') }}</p>
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

.card-title {
    font-size: 1.8rem;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
function exportReport(format) {
    @if(config('reports.use_new_exports'))
    const form = document.getElementById('payroll-summary-export-form');
    if (!form) {
        return;
    }

    form.querySelector('input[name=\"export_format\"]').value = format;
    form.submit();
    @else
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export_format', format);
    window.location.href = currentUrl.toString();
    @endif
}
</script>
@endpush
