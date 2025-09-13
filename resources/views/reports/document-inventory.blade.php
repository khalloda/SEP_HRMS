@extends('layouts.app')

@section('title', __('Document Inventory Report'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('Document Inventory Report') }}</h2>
            <p class="text-muted mb-0">{{ __('Complete document tracking with expiry alerts and compliance status') }}</p>
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
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total']) }}</h4>
                <p class="card-text text-muted">{{ __('Total Documents') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['total'] - $summary['expiring_soon'] - $summary['expired']) }}</h4>
                <p class="card-text text-muted">{{ __('Valid Documents') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['expiring_soon']) }}</h4>
                <p class="card-text text-muted">{{ __('Expiring Soon') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-danger mb-2">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ number_format($summary['expired']) }}</h4>
                <p class="card-text text-muted">{{ __('Expired') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Export -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('Filters & Export') }}</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="document_type" class="form-label">{{ __('Document Type') }}</label>
                <select name="document_type" id="document_type" class="form-select">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach($documentTypes as $typeKey => $typeInfo)
                        <option value="{{ $typeKey }}" {{ ($filters['document_type'] ?? '') == $typeKey ? 'selected' : '' }}>
                            {{ app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="expiry_status" class="form-label">{{ __('Expiry Status') }}</label>
                <select name="expiry_status" id="expiry_status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="valid" {{ ($filters['expiry_status'] ?? '') == 'valid' ? 'selected' : '' }}>
                        {{ __('Valid') }}
                    </option>
                    <option value="expiring" {{ ($filters['expiry_status'] ?? '') == 'expiring' ? 'selected' : '' }}>
                        {{ __('Expiring Soon') }}
                    </option>
                    <option value="expired" {{ ($filters['expiry_status'] ?? '') == 'expired' ? 'selected' : '' }}>
                        {{ __('Expired') }}
                    </option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-brand-primary me-2">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('reports.document.inventory') }}" class="btn btn-outline-secondary">
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

<!-- Document Type Breakdown -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('Documents by Type') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($summary['by_type'] as $type => $count)
                @php
                    $typeInfo = $documentTypes[$type] ?? ['name_en' => $type, 'icon' => 'fas fa-file'];
                    $typeName = app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'];
                @endphp
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="{{ $typeInfo['icon'] }} text-primary me-2 fa-lg"></i>
                        <div>
                            <span class="fw-bold">{{ $typeName }}</span>
                            <br><small class="text-muted">{{ number_format($count) }} documents</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Documents List -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Document Inventory') }}</h5>
        <span class="badge bg-light text-dark">{{ number_format($documents->count()) }} {{ __('documents') }}</span>
    </div>
    <div class="card-body p-0">
        @if($documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Document') }}</th>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Upload Date') }}</th>
                            <th>{{ __('Expiry Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="{{ $document->type_icon }} text-primary me-2"></i>
                                    <div>
                                        <strong>{{ $document->original_name }}</strong>
                                        @if($document->tags->count() > 0)
                                            <br>
                                            @foreach($document->tags as $tag)
                                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $document->employee->display_name }}</strong>
                                    <br><small class="text-muted">{{ $document->employee->code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-outline-primary">{{ $document->employee->department->name_en }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $document->type_display_name }}</span>
                            </td>
                            <td>
                                {{ $document->created_at->format('M j, Y') }}
                                <br><small class="text-muted">{{ $document->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($document->expires_at)
                                    {{ $document->expires_at->format('M j, Y') }}
                                    <br><small class="text-muted">{{ $document->expires_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">{{ __('No Expiry') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($document->is_expired)
                                    <span class="badge bg-danger">{{ __('Expired') }}</span>
                                @elseif($document->is_expiring_soon)
                                    <span class="badge bg-warning">{{ __('Expiring Soon') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('Valid') }}</span>
                                @endif

                                @if($document->versions->count() > 1)
                                    <br><small class="text-muted">
                                        <i class="fas fa-history"></i> {{ __('v:version', ['version' => $document->version_current]) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @can('view', $document)
                                        <a href="{{ route('documents.show', $document) }}"
                                           class="btn btn-outline-primary" title="{{ __('View Details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('documents.download', $document) }}"
                                           class="btn btn-outline-success" title="{{ __('Download') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No documents found') }}</h5>
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

.card-title {
    font-size: 1.8rem;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
function exportReport(format) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('export_format', format);
    window.location.href = currentUrl.toString();
}
</script>
@endpush