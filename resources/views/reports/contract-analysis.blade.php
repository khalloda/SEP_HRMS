@extends('layouts.app')

@section('title', __('Contract Analysis Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Contract Analysis Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Comprehensive analysis of contract distribution and trends') }}</p>
        </div>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Reports') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<!-- Export Options -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-download"></i> {{ __('Export Options') }}</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('reports.contract.analysis', array_merge($filters, ['export_format' => 'excel'])) }}"
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> {{ __('Export to Excel') }}
                </a>
                <a href="{{ route('reports.contract.analysis', array_merge($filters, ['export_format' => 'pdf'])) }}"
                   class="btn btn-danger ms-2">
                    <i class="fas fa-file-pdf"></i> {{ __('Export to PDF') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overview Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="brand-gold mb-2">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ $analysis['by_status']->sum() }}</h4>
                <p class="card-text text-muted small">{{ __('Total Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ $analysis['by_status']->get('active', 0) }}</h4>
                <p class="card-text text-muted small">{{ __('Active Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-warning mb-2">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ $analysis['by_status']->get('pending', 0) }}</h4>
                <p class="card-text text-muted small">{{ __('Pending Contracts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-danger mb-2">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <h4 class="card-title mb-1">{{ $analysis['by_status']->get('expired', 0) + $analysis['by_status']->get('terminated', 0) }}</h4>
                <p class="card-text text-muted small">{{ __('Inactive Contracts') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Contract Analysis Charts -->
<div class="row mb-4">
    <!-- Contract Types Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> {{ __('Contracts by Type') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="contractTypesChart"></canvas>
                </div>

                <!-- Legend Table -->
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('Contract Type') }}</th>
                                <th class="text-end">{{ __('Count') }}</th>
                                <th class="text-end">{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = $analysis['by_type']->sum(); @endphp
                            @foreach($analysis['by_type'] as $type => $count)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ __(ucfirst(str_replace('_', ' ', $type))) }}</span>
                                </td>
                                <td class="text-end">{{ $count }}</td>
                                <td class="text-end">{{ $total > 0 ? round(($count / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Status Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-chart-doughnut"></i> {{ __('Contracts by Status') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                    <canvas id="contractStatusChart"></canvas>
                </div>

                <!-- Status Legend -->
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Count') }}</th>
                                <th class="text-end">{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = $analysis['by_status']->sum(); @endphp
                            @foreach($analysis['by_status'] as $status => $count)
                            <tr>
                                <td>
                                    @php
                                        $badgeClass = match($status) {
                                            'active' => 'bg-success',
                                            'pending' => 'bg-warning',
                                            'expired' => 'bg-danger',
                                            'terminated' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ __(ucfirst($status)) }}</span>
                                </td>
                                <td class="text-end">{{ $count }}</td>
                                <td class="text-end">{{ $total > 0 ? round(($count / $total) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Analysis -->
<div class="row mb-4">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-building"></i> {{ __('Contracts by Department') }}</h5>
            </div>
            <div class="card-body">
                @if($analysis['by_department']->isNotEmpty())
                <div class="row">
                    @foreach($analysis['by_department'] as $department => $count)
                    <div class="col-md-4 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title brand-dark-green">{{ $count }}</h5>
                                <p class="card-text text-muted small">{{ $department ?: __('No Department') }}</p>
                                @php $percentage = $analysis['by_department']->sum() > 0 ? round(($count / $analysis['by_department']->sum()) * 100, 1) : 0; @endphp
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">{{ $percentage }}%</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No Department Data Available') }}</h5>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Renewal Trends -->
@if($analysis['renewal_trend']->isNotEmpty())
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-chart-line"></i> {{ __('Contract Renewal Trends (Next 12 Months)') }}</h5>
    </div>
    <div class="card-body">
        <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
            <canvas id="renewalTrendChart"></canvas>
        </div>

        <!-- Trend Summary -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Month') }}</th>
                                <th class="text-end">{{ __('Contracts Expiring') }}</th>
                                <th>{{ __('Renewal Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analysis['renewal_trend'] as $month => $count)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td class="text-end">
                                    <span class="badge bg-info">{{ $count }}</span>
                                </td>
                                <td>
                                    @php
                                        $urgency = \Carbon\Carbon::createFromFormat('Y-m', $month)->diffInMonths(now());
                                    @endphp
                                    @if($urgency <= 1)
                                        <span class="badge bg-danger">{{ __('Urgent Action Required') }}</span>
                                    @elseif($urgency <= 3)
                                        <span class="badge bg-warning">{{ __('Plan Renewals') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('Future Planning') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contract Types Chart
    const typeCtx = document.getElementById('contractTypesChart').getContext('2d');
    const typeData = @json($analysis['by_type']);

    new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(typeData).map(key => key.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: Object.values(typeData),
                backgroundColor: [
                    '#c6a44a', // Gold
                    '#2e4029', // Dark Green
                    '#17a2b8', // Info
                    '#ffc107', // Warning
                    '#dc3545', // Danger
                    '#6c757d'  // Secondary
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            }
        }
    });

    // Contract Status Chart
    const statusCtx = document.getElementById('contractStatusChart').getContext('2d');
    const statusData = @json($analysis['by_status']);

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(key => key.toUpperCase()),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#28a745', // Success (Active)
                    '#ffc107', // Warning (Pending)
                    '#dc3545', // Danger (Expired)
                    '#343a40', // Dark (Terminated)
                    '#6c757d'  // Secondary (Other)
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            }
        }
    });

    // Renewal Trend Chart
    @if($analysis['renewal_trend']->isNotEmpty())
    const trendCtx = document.getElementById('renewalTrendChart').getContext('2d');
    const trendData = @json($analysis['renewal_trend']);

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: Object.keys(trendData).map(month => {
                const date = new Date(month + '-01');
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: '{{ __("Contracts Expiring") }}',
                data: Object.values(trendData),
                borderColor: '#c6a44a',
                backgroundColor: 'rgba(198, 164, 74, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            }
        }
    });
    @endif
});
</script>
@endpush

@push('styles')
<style>
.brand-dark-green {
    color: var(--color-dark-green);
}

.brand-gold {
    color: var(--color-gold);
}

.card-title {
    font-size: 1.8rem;
    font-weight: bold;
}

.progress {
    border-radius: 10px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: var(--color-dark-green);
}

.badge {
    font-size: 0.75rem;
}

.card {
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.chart-container {
    overflow: hidden;
    max-width: 100%;
    max-height: 100%;
}

.chart-container canvas {
    max-width: 100% !important;
    max-height: 100% !important;
}
</style>
@endpush