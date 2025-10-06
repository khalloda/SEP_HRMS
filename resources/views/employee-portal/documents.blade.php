@extends('layouts.app')

@section('title', __('hrms.my_documents'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="h3 brand-dark-green mb-1">{{ __('hrms.my_documents') }}</h2>
        <p class="text-muted mb-0">{{ __('hrms.view_download_personal_documents') }}</p>
    </div>
    <div>
        <a href="{{ route('employee-portal.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('common.back_to_dashboard') }}
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-2 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="brand-gold mb-2">
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['total'] }}</h4>
                <p class="card-text text-muted small">{{ __('common.total') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['expiring_soon'] }}</h4>
                <p class="card-text text-muted small">{{ __('common.expiring_soon') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-danger mb-2">
                    <i class="fas fa-times-circle fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['expired'] }}</h4>
                <p class="card-text text-muted small">{{ __('common.expired') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-2 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="fas fa-calendar fa-2x"></i>
                </div>
                <h4 class="card-title">{{ $documentStats['this_month'] }}</h4>
                <p class="card-text text-muted small">{{ __('common.this_month') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header card-header-custom">
                <h6 class="mb-0">{{ __('common.documents_by_type') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($documentStats['by_type'] as $type => $count)
                    @php
                    $typeInfo = \App\Models\Document::TYPES[$type] ?? ['name_en' => $type, 'icon' => 'fas fa-file'];
                    $typeName = app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'];
                    @endphp
                    <div class="col-12 mb-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="{{ $typeInfo['icon'] }} text-primary me-2"></i>
                                <span class="small">{{ Str::limit($typeName, 15) }}</span>
                            </div>
                            <span class="badge bg-secondary">{{ $count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HR Letters Section (if available) -->
@if($hrLetters && $hrLetters->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-file-alt"></i> {{ __('common.my_hr_letters') }}
        </h5>
        <span class="badge bg-light text-dark">{{ $hrLetters->count() }}</span>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($hrLetters as $letter)
            <div class="col-md-6 mb-3">
                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt text-success fa-2x me-3"></i>
                        <div>
                            <div class="fw-bold">{{ Str::limit($letter->subject, 40) }}</div>
                            <small class="text-muted">
                                {{ $letter->letterTemplate->name }} •
                                {{ $letter->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                    <a href="{{ route('letters.download-pdf', $letter) }}"
                        class="btn btn-sm btn-outline-success"
                        title="{{ __('common.download_letter') }}">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Search and Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0">{{ __('common.search_filter_documents') }}</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employee-portal.documents') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('common.search') }}</label>
                    <input type="text" name="search" class="form-control"
                        value="{{ request('search') }}"
                        placeholder="{{ __('common.search_documents') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('common.document_type') }}</label>
                    <select name="type" class="form-select">
                        <option value="">{{ __('common.all_types') }}</option>
                        @foreach($documentTypes as $typeKey => $typeInfo)
                        @php
                        $typeName = app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'];
                        @endphp
                        <option value="{{ $typeKey }}" {{ request('type') === $typeKey ? 'selected' : '' }}>
                            {{ $typeName }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('common.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>{{ __('common.valid') }}</option>
                        <option value="expiring_soon" {{ request('status') === 'expiring_soon' ? 'selected' : '' }}>{{ __('common.expiring_soon') }}</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('common.expired') }}</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('employee-portal.documents') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Documents List -->
<div class="card border-0 shadow-sm">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">{{ __('hrms.my_documents') }}</h5>
            <small class="text-white ms-3">{{ $documents->total() }} {{ __('common.documents_label') }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-light"
                id="bulkDownloadBtn" style="display: none;"
                onclick="bulkDownload()">
                <i class="fas fa-download"></i> {{ __('common.download_selected') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-light"
                data-bs-toggle="modal" data-bs-target="#requestUpdateModal">
                <i class="fas fa-edit"></i> {{ __('common.request_update') }}
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($documents->count() > 0)
        <form id="bulkDownloadForm" action="{{ route('employee-portal.documents.bulk-download') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>{{ __('common.document') }}</th>
                            <th>{{ __('common.type') }}</th>
                            <th>{{ __('common.upload_date') }}</th>
                            <th>{{ __('common.expiry') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input document-checkbox"
                                    name="document_ids[]" value="{{ $document->id }}">
                            </td>
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
                                <span class="text-muted">{{ __('common.no_expiry') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($document->is_expired)
                                <span class="badge bg-danger">{{ __('common.expired') }}</span>
                                @elseif($document->is_expiring_soon)
                                <span class="badge bg-warning">{{ __('common.expiring_soon') }}</span>
                                @else
                                <span class="badge bg-success">{{ __('common.valid') }}</span>
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
                                        title="{{ __('hrms.view_versions') }}">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </form>

        <!-- Pagination -->
        <div class="d-flex justify-content-center p-3">
            {{ $documents->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('common.no_documents_found') }}</h5>
            <p class="text-muted">{{ __('common.empty_documents_hint') }}</p>
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
                <h5 class="modal-title">{{ __('common.document_versions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">{{ $document->original_name }}</h6>
                <div class="list-group">
                    @foreach($document->versions as $version)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __('common.version') }} {{ $version->version_no }}</strong>
                            @if($version->is_current)
                            <span class="badge bg-primary ms-2">{{ __('common.current') }}</span>
                            @endif
                            <br><small class="text-muted">{{ $version->created_at->format('M j, Y \a\t g:i A') }}</small>
                        </div>
                        <div>
                            <span class="badge bg-secondary">{{ $version->file_size }}</span>
                            @can('view', $document)
                            <a href="{{ route('documents.download', ['document' => $document, 'version' => $version->id]) }}"
                                class="btn btn-sm btn-outline-primary ms-2" title="{{ __('common.download') }}">
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

<!-- Request Document Update Modal -->
<div class="modal fade" id="requestUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('employee-portal.documents.request-update') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('common.request_document_update') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">{{ __('common.document_type') }}</label>
                        <select name="document_type" class="form-select" required>
                            <option value="">{{ __('common.select_document_type') }}</option>
                            @foreach($documentTypes as $typeKey => $typeInfo)
                            @php
                            $typeName = app()->getLocale() === 'ar' ? ($typeInfo['name_ar'] ?? $typeInfo['name_en']) : $typeInfo['name_en'];
                            @endphp
                            <option value="{{ $typeKey }}">{{ $typeName }}</option>
                            @endforeach
                            <option value="other">{{ __('common.other') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">{{ __('common.reason_for_update') }}</label>
                        <select name="reason" class="form-select" required>
                            <option value="">{{ __('common.select_reason') }}</option>
                            <option value="expired">{{ __('hrms.expired') }}</option>
                            <option value="incorrect_info">{{ __('hrms.incorrect_information') }}</option>
                            <option value="new_version">{{ __('hrms.new_version_available') }}</option>
                            <option value="missing_document">{{ __('hrms.missing_document') }}</option>
                            <option value="other">{{ __('common.other') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('common.additional_notes') }}</label>
                        <textarea name="notes" class="form-control" rows="4"
                            placeholder="{{ __('common.please_provide_details') }}"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ __('common.request_review_notice') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('common.submit_request') }}</button>
                </div>
            </form>
        </div>
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

    .required::after {
        content: " *";
        color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const documentCheckboxes = document.querySelectorAll('.document-checkbox');
        const bulkDownloadBtn = document.getElementById('bulkDownloadBtn');

        // Handle "Select All" checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                documentCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkDownloadBtn();
            });
        }

        // Handle individual checkboxes
        documentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllCheckbox();
                toggleBulkDownloadBtn();
            });
        });

        function updateSelectAllCheckbox() {
            const checkedCount = document.querySelectorAll('.document-checkbox:checked').length;
            const totalCount = documentCheckboxes.length;

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === totalCount;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        }

        function toggleBulkDownloadBtn() {
            const checkedCount = document.querySelectorAll('.document-checkbox:checked').length;
            if (bulkDownloadBtn) {
                bulkDownloadBtn.style.display = checkedCount > 0 ? 'block' : 'none';
            }
        }

        // Initial state
        updateSelectAllCheckbox();
        toggleBulkDownloadBtn();
    });

    function bulkDownload() {
        const checkedBoxes = document.querySelectorAll('.document-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('{{ __("hrms.please_select_at_least_one_document") }}');
            return;
        }

        if (confirm('{{ __("hrms.download_selected_documents_confirm", ["count" => ":count"]) }}'.replace(':count', checkedBoxes.length))) {
            document.getElementById('bulkDownloadForm').submit();
        }
    }
</script>
@endpush