@extends('layouts.app')

@section('title', __('Payroll Runs'))

@section('content')
<div class="container-fluid">
    @include('payroll.partials.status-banners')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ __('Payroll Runs') }}</h1>
            <p class="text-muted mb-0">{{ __('Track each payroll cycle from draft through posting.') }}</p>
        </div>
        @can('create', App\Models\PayrollRun::class)
            <a href="{{ route('payroll.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span class="ms-1">{{ __('Create Payroll Run') }}</span>
            </a>
        @endcan
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <form class="row gy-2 gx-3 align-items-end" method="GET" action="{{ route('payroll.index') }}">
                <div class="col-md-3">
                    <label class="form-label" for="status">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">{{ __('All statuses') }}</option>
                        <?php foreach($statusOptions as $key => $label): ?>
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ __($label) }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="year">{{ __('Year') }}</label>
                    <select id="year" name="year" class="form-select">
                        <option value="">{{ __('Any') }}</option>
                        <?php foreach($yearOptions as $year): ?>
                            <option value="{{ $year }}" {{ (int) request('year') === (int) $year ? 'selected' : '' }}>{{ $year }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="month">{{ __('Month') }}</label>
                    <select id="month" name="month" class="form-select">
                        <option value="">{{ __('Any') }}</option>
                        <?php foreach($monthOptions as $value => $label): ?>
                            <option value="{{ $value }}" {{ (int) request('month') === (int) $value ? 'selected' : '' }}>{{ __($label) }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="search">{{ __('Search') }}</label>
                    <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="{{ __('Title or description') }}">
                </div>
                <div class="col-md-2 text-md-end">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter"></i>
                        <span class="ms-1">{{ __('Filter') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Period') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Total Employees') }}</th>
                            <th class="text-end">{{ __('Total Gross') }}</th>
                            <th class="text-end">{{ __('Total Net') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($payrollRuns->count()): ?>
                            <?php foreach($payrollRuns as $run): ?>
                                <tr>
                                    <td>
                                        <a href="{{ route('payroll.show', $run) }}" class="fw-semibold">{{ $run->title }}</a>
                                        <?php if($run->description): ?>
                                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit($run->description, 120) }}</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        {{ optional($run->pay_period_start)->format('d M Y') }} – {{ optional($run->pay_period_end)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-uppercase">{{ __($run->status) }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($run->total_employees) }}</td>
                                    <td class="text-end">{{ number_format($run->total_gross, 2) }} {{ $run->currency }}</td>
                                    <td class="text-end">{{ number_format($run->total_net, 2) }} {{ $run->currency }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('payroll.show', $run) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-file-invoice-dollar fa-2x mb-3"></i>
                                    <p class="mb-0">{{ __('No payroll runs found. Try adjusting the filters or create a new run.') }}</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        @if($payrollRuns->hasPages())
            <div class="card-footer">
                {{ $payrollRuns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
