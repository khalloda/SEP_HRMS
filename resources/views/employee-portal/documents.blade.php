@extends('layouts.app')

@section('title', __('My Documents'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 brand-dark-green mb-1">{{ __('My Documents') }}</h2>
            <p class="text-muted mb-0">{{ __('View and download your personal documents') }}</p>
        </div>
        <div>
            <a href="{{ route('employee-portal.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['total'] }}</h4>
                <p class="card-text text-muted">{{ __('Total Documents') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['expiring_soon'] }}</h4>
                <p class="card-text text-muted">{{ __('Expiring Soon') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">{{ __('Documents by Type') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($documentStats['by_type'] as $type => $count)
                        @php
                            $typeInfo = \App\Models\Document::TYPES[$type] ?? ['name_en' => $type, 'icon' => 'fas fa-file'];
                            $typeName = app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'];
                        @endphp
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="{{ $typeInfo['icon'] }} text-primary me-2"></i>
                                <span class="small">{{ $typeName }}: <strong>{{ $count }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documents List -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('My Documents') }}</h5>
        <div class="d-flex align-items-center">
            <small class="text-white me-3">{{ $documents->total() }} {{ __('documents') }}</small>
        </div>
    </div>
    <div class="card-body p-0">
        @if($documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th>{{ __('Document') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Upload Date') }}</th>
                            <th>{{ __('Expiry') }}</th>
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
                                <span class="badge bg-outline-primary">{{ $document->type_display_name }}</span>
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
                                    <span class="text-muted">{{ __('No expiry') }}</span>
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
                                        <a href="{{ route('documents.download', $document) }}"
                                           class="btn btn-outline-primary" title="{{ __('Download') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endcan

                                    @if($document->versions->count() > 1)
                                        <button type="button" class="btn btn-outline-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#versionsModal{{ $document->id }}"
                                                title="{{ __('View Versions') }}">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3">
                {{ $documents->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No documents found') }}</h5>
                <p class="text-muted">{{ __('Your documents will appear here once they are uploaded by HR.') }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Version Modals -->
@foreach($documents as $document)
    @if($document->versions->count() > 1)
    <div class="modal fade" id="versionsModal{{ $document->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Document Versions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3">{{ $document->original_name }}</h6>
                    <div class="list-group">
                        @foreach($document->versions as $version)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ __('Version') }} {{ $version->version_no }}</strong>
                                @if($version->is_current)
                                    <span class="badge bg-primary ms-2">{{ __('Current') }}</span>
                                @endif
                                <br><small class="text-muted">{{ $version->created_at->format('M j, Y \a\t g:i A') }}</small>
                            </div>
                            <div>
                                <span class="badge bg-secondary">{{ $version->file_size }}</span>
                                @can('view', $document)
                                    <a href="{{ route('documents.download', ['document' => $document, 'version' => $version->id]) }}"
                                       class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
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

.brand-gold {
    color: var(--color-gold);
}

.brand-dark-green {
    color: var(--color-dark-green);
}

.badge.bg-outline-primary {
    color: var(--color-gold);
    border: 1px solid var(--color-gold);
    background-color: transparent;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush