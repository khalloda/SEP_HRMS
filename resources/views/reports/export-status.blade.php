@extends('layouts.app')

@section('title', __('Report Export Status'))

@section('content')
<div class="card border-0 shadow-sm" id="report-export-status"
     data-status-url="{{ route('reports.exports.status', $state['correlation_id']) }}"
     data-download-url="{{ route('reports.exports.download', $state['correlation_id']) }}"
     data-initial='@json($state)'>
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Report Export Progress') }}</h5>
        <span class="badge bg-secondary">{{ $state['report_name'] ?? $state['report_slug'] }}</span>
    </div>
    <div class="card-body">
        <p class="text-muted">{{ __('Correlation ID') }}: <code id="export-correlation">{{ $state['correlation_id'] }}</code></p>
        <div class="progress mb-3" style="height: 22px;">
            <div class="progress-bar" id="report-progress" role="progressbar" style="width: {{ $state['progress'] ?? 0 }}%;">
                {{ $state['progress'] ?? 0 }}%
            </div>
        </div>
        <ul class="list-unstyled small" id="export-details">
            <li>{{ __('Status') }}: <strong id="report-status" data-status="{{ strtolower($state['status'] ?? 'queued') }}">{{ ucfirst($state['status'] ?? 'queued') }}</strong></li>
            <li>{{ __('Rows Processed') }}: <span id="report-processed">{{ $state['processed_rows'] ?? 0 }}</span> / <span id="report-total">{{ $state['total_rows'] ?? '-' }}</span></li>
            <li id="report-message" class="text-danger" style="display: {{ !empty($state['message']) ? 'block' : 'none' }};">
                {{ $state['message'] ?? '' }}
            </li>
        </ul>
        <a href="{{ route('reports.exports.download', $state['correlation_id']) }}"
           class="btn btn-brand-secondary" id="report-download" style="display: {{ ($state['status'] ?? null) === 'completed' ? 'inline-flex' : 'none' }};">
            <i class="fas fa-download"></i> {{ __('Download File') }}
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const container = document.getElementById('report-export-status');
    if (!container) return;

    const statusUrl = container.dataset.statusUrl;
    const downloadUrl = container.dataset.downloadUrl;
    const state = JSON.parse(container.dataset.initial);

    const els = {
        bar: document.getElementById('report-progress'),
        status: document.getElementById('report-status'),
        processed: document.getElementById('report-processed'),
        total: document.getElementById('report-total'),
        message: document.getElementById('report-message'),
        download: document.getElementById('report-download'),
    };

    applyState(state);
    const interval = setInterval(async () => {
        try {
            const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('Failed to fetch status');
            const data = await response.json();
            applyState(data);
            if (data.status === 'completed' || data.status === 'failed') {
                clearInterval(interval);
            }
        } catch (error) {
            clearInterval(interval);
        }
    }, 3000);

    function applyState(data) {
        const statusValue = (data.status ?? 'queued').toLowerCase();

        els.bar.style.width = `${data.progress ?? 0}%`;
        els.bar.textContent = `${data.progress ?? 0}%`;
        els.status.textContent = statusValue.charAt(0).toUpperCase() + statusValue.slice(1);
        els.status.dataset.status = statusValue;
        els.processed.textContent = data.processed_rows ?? 0;
        els.total.textContent = data.total_rows ?? '-';
        if (data.status === 'completed') {
            els.download.style.display = 'inline-flex';
            els.download.href = data.download_url ?? downloadUrl;
        } else {
            els.download.style.display = 'none';
        }

        if (data.status === 'failed' && data.message) {
            els.message.style.display = 'block';
            els.message.textContent = data.message;
        } else {
            els.message.style.display = 'none';
            els.message.textContent = '';
        }
    }
})();
</script>
@endpush


