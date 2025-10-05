<!-- Document Expiry Summary -->
<div class="row text-center mb-3">
    <div class="col-6">
        <div class="text-warning">
            <div class="h4 mb-0">{{ $data['expiring_soon']->count() }}</div>
            <small>{{ __('Expiring Soon') }}</small>
        </div>
    </div>
    <div class="col-6">
        <div class="text-danger">
            <div class="h4 mb-0">{{ number_format($data['expired_count']) }}</div>
            <small>{{ __('Expired') }}</small>
        </div>
    </div>
</div>

<!-- Expiring Documents List -->
@if($data['expiring_soon']->count() > 0)
    <h6 class="text-warning mb-2">{{ __('Expiring Within 30 Days') }}</h6>
    <div class="list-group list-group-flush">
        @foreach($data['expiring_soon']->take(5) as $document)
        <div class="list-group-item px-0 py-2">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold small">{{ Str::limit($document->original_name, 25) }}</div>
                    <small class="text-muted">{{ $document->employee->display_name }}</small>
                </div>
                <div class="text-end">
                    <small class="badge bg-warning">
                        {{ now()->diffInDays($document->expires_at, false) }} {{ __('days') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($data['expiring_soon']->count() > 5)
        <div class="text-center mt-2">
            <small class="text-muted">{{ __('and :count more', ['count' => $data['expiring_soon']->count() - 5]) }}</small>
        </div>
    @endif
@else
    <div class="text-center text-muted py-3">
        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
        <p>{{ __('No documents expiring soon') }}</p>
    </div>
@endif

<!-- Quick Action -->
<div class="mt-3">
    <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-primary w-100">
        <i class="fas fa-folder-open"></i> {{ __('View All Documents') }}
    </a>
</div>