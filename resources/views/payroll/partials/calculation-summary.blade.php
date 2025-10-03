@php
    $summary = $summary ?? null;
@endphp

@if(!empty($summary))
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