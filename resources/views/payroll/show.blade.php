@extends('layouts.app')

@section('title', $payrollRun->title)

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h1 class="h3 mb-1">{{ $payrollRun->title }}</h1>
            <p class="text-muted mb-0">
                {{ optional($payrollRun->pay_period_start)->format('d M Y') }} –
                {{ optional($payrollRun->pay_period_end)->format('d M Y') }} ·
                {{ __('Pay Date') }}: {{ optional($payrollRun->pay_date)->format('d M Y') ?? __('TBD') }}
            </p>
            @if($payrollRun->description)
                <p class="mt-2 mb-0">{{ $payrollRun->description }}</p>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary text-uppercase px-3 py-2">{{ __($payrollRun->status) }}</span>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
                <span class="ms-1">{{ __('Back to list') }}</span>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Totals') }}</h6>
                    <p class="mb-1"><strong>{{ number_format($payrollRun->total_employees) }}</strong> {{ __('Employees') }}</p>
                    <p class="mb-1"><strong>{{ number_format($payrollRun->total_gross, 2) }}</strong> {{ $payrollRun->currency }} {{ __('Gross') }}</p>
                    <p class="mb-0"><strong>{{ number_format($payrollRun->total_net, 2) }}</strong> {{ $payrollRun->currency }} {{ __('Net') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Lifecycle') }}</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li>{{ __('Created by') }}: {{ $payrollRun->creator?->name ?? __('Unknown') }}</li>
                        <li>{{ __('Locked by') }}: {{ $payrollRun->locker?->name ?? __('—') }}</li>
                        <li>{{ __('Approved by') }}: {{ $payrollRun->approver?->name ?? __('—') }}</li>
                        <li>{{ __('Posted by') }}: {{ $payrollRun->poster?->name ?? __('—') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold small">{{ __('Actions') }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @can('calculate', $payrollRun)
                            <form method="POST" action="{{ route('payroll.calculate', ['payrollRun' => $payrollRun->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-calculator"></i>
                                    <span class="ms-1">{{ __('Calculate') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('lock', $payrollRun)
                            <form method="POST" action="{{ route('payroll.lock', ['payrollRun' => $payrollRun->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-lock"></i>
                                    <span class="ms-1">{{ __('Lock') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('unlock', $payrollRun)
                            <form method="POST" action="{{ route('payroll.unlock', ['payrollRun' => $payrollRun->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-unlock"></i>
                                    <span class="ms-1">{{ __('Unlock') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('approve', $payrollRun)
                            <form method="POST" action="{{ route('payroll.approve', ['payrollRun' => $payrollRun->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-check"></i>
                                    <span class="ms-1">{{ __('Approve') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('post', $payrollRun)
                            <form method="POST" action="{{ route('payroll.post', ['payrollRun' => $payrollRun->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-clipboard-check"></i>
                                    <span class="ms-1">{{ __('Post') }}</span>
                                </button>
                            </form>
                        @endcan
                        @can('delete', $payrollRun)
                            <form method="POST" action="{{ route('payroll.cancel', ['payrollRun' => $payrollRun->id]) }}" onsubmit="return confirm('{{ __('Are you sure you want to cancel this payroll run?') }}');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-ban"></i>
                                    <span class="ms-1">{{ __('Cancel') }}</span>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($validationIssues))
        <div class="alert alert-warning">
            <h6 class="fw-semibold mb-2">{{ __('Validation Issues Detected') }}</h6>
            <ul class="mb-0">
                @foreach($validationIssues as $issue)
                    <li>{{ $issue }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($summary)
        @php
            $departmentSummary = collect($summary['by_department'] ?? [])->toArray();
            $earningsBreakdown = collect($summary['earnings_breakdown'] ?? [])->toArray();
            $deductionsBreakdown = collect($summary['deductions_breakdown'] ?? [])->toArray();
        @endphp
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Calculation Summary') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-muted text-uppercase small">{{ __('Employees Processed') }}</h6>
                            <p class="fs-4 fw-semibold mb-0">{{ number_format($summary['total_employees'] ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-muted text-uppercase small">{{ __('Total Gross') }}</h6>
                            <p class="fs-4 fw-semibold mb-0">{{ number_format($summary['total_gross'] ?? 0, 2) }} {{ $payrollRun->currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-muted text-uppercase small">{{ __('Total Net') }}</h6>
                            <p class="fs-4 fw-semibold mb-0">{{ number_format($summary['total_net'] ?? 0, 2) }} {{ $payrollRun->currency }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="text-muted text-uppercase small">{{ __('Total Deductions') }}</h6>
                            <p class="fs-4 fw-semibold mb-0">{{ number_format($summary['total_deductions'] ?? 0, 2) }} {{ $payrollRun->currency }}</p>
                        </div>
                    </div>
                </div>

                @if($departmentSummary)
                    <h6 class="fw-semibold mt-3">{{ __('By Department') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Department') }}</th>
                                    <th class="text-end">{{ __('Employees') }}</th>
                                    <th class="text-end">{{ __('Gross') }}</th>
                                    <th class="text-end">{{ __('Net') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentSummary as $department => $data)
                                    <tr>
                                        <td>{{ $department ?: __('Unassigned') }}</td>
                                        <td class="text-end">{{ number_format($data['count'] ?? 0) }}</td>
                                        <td class="text-end">{{ number_format($data['gross'] ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($data['net'] ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <h6 class="fw-semibold">{{ __('Earnings Breakdown') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Component') }}</th>
                                        <th class="text-end">{{ __('Total') }}</th>
                                        <th class="text-end">{{ __('Count') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($earningsBreakdown as $code => $data)
                                        <tr>
                                            <td>{{ $data['name'] ?? $code }}</td>
                                            <td class="text-end">{{ number_format($data['total'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ number_format($data['count'] ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">{{ __('No earnings lines recorded.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold">{{ __('Deductions Breakdown') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Component') }}</th>
                                        <th class="text-end">{{ __('Total') }}</th>
                                        <th class="text-end">{{ __('Count') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deductionsBreakdown as $code => $data)
                                        <tr>
                                            <td>{{ $data['name'] ?? $code }}</td>
                                            <td class="text-end">{{ number_format($data['total'] ?? 0, 2) }}</td>
                                            <td class="text-end">{{ number_format($data['count'] ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">{{ __('No deductions recorded.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection