@extends('layouts.app')

@section('title', __('hrms.documents'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ __('hrms.documents') }}</h1>
        <p class="text-muted mb-0">{{ __('hrms.document.manage_employee_documents') }}</p>
    </div>
    @can('create', App\Models\Document::class)
    <a href="{{ route('documents.create') }}" class="btn btn-brand-primary">
        <i class="fas fa-plus"></i> {{ __('hrms.document.add_document') }}
    </a>
    @endcan
</div>
@endsection

@section('content')
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('documents.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">{{ __('common.search') }}</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="{{ __('common.search_documents') }}">
            </div>

            <div class="col-md-2">
                <label for="type" class="form-label">{{ __('common.type') }}</label>
                <select class="form-select" id="type" name="type">
                    <option value="">{{ __('common.all_types') }}</option>
                    @foreach($documentTypes as $type)
                    <option value="{{ $type['value'] }}" {{ request('type') == $type['value'] ? 'selected' : '' }}>
                        {{ $type['label'] }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="employee_id" class="form-label">{{ __('common.employee') }}</label>
                <select class="form-select" id="employee_id" name="employee_id">
                    <option value="">{{ __('common.all_employees') }}</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->code }} - {{ $employee->display_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="visibility" class="form-label">{{ __('common.visibility') }}</label>
                <select class="form-select" id="visibility" name="visibility">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>
                        {{ __('common.private') }}
                    </option>
                    <option value="shared" {{ request('visibility') == 'shared' ? 'selected' : '' }}>
                        {{ __('common.shared') }}
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="expired" class="form-label">{{ __('common.status') }}</label>
                <select class="form-select" id="expired" name="expired">
                    <option value="">{{ __('common.all') }}</option>
                    <option value="0" {{ request('expired') === '0' ? 'selected' : '' }}>
                        {{ __('common.valid') }}
                    </option>
                    <option value="1" {{ request('expired') === '1' ? 'selected' : '' }}>
                        {{ __('common.expired') }}
                    </option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-brand-primary">
                    <i class="fas fa-search"></i> {{ __('common.search') }}
                </button>
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('common.clear') }}
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-start border-4 border-brand-gold">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('common.total_documents') }}</h5>
                        <h2 class="mb-0 brand-dark-green">{{ number_format($documents->total()) }}</h2>
                    </div>
                    <div class="text-brand-gold">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('common.expiring_soon') }}</h5>
                        <h2 class="mb-0 text-warning">
                            {{ \App\Models\Document::expiringSoon(30)->count() }}
                        </h2>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('common.expired') }}</h5>
                        <h2 class="mb-0 text-danger">
                            {{ \App\Models\Document::expired()->count() }}
                        </h2>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-4 border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-0">{{ __('common.this_month') }}</h5>
                        <h2 class="mb-0 text-success">
                            {{ \App\Models\Document::whereMonth('created_at', now()->month)->count() }}
                        </h2>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documents Table -->
<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('hrms.documents') }}</h5>
    </div>
    <div class="card-body p-0">
        @if($documents->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header-custom">
                    <tr>
                        <th>{{ __('common.document') }}</th>
                        <th>{{ __('common.type') }}</th>
                        <th>{{ __('common.employee') }}</th>
                        <th>{{ __('common.size') }}</th>
                        <th>{{ __('common.visibility') }}</th>
                        <th>{{ __('common.expires') }}</th>
                        <th>{{ __('common.created') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="{{ $document->type_icon }} text-brand-gold me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ $document->original_name }}</div>
                                    @if($document->tags->count())
                                    <div class="mt-1">
                                        @foreach($document->tags as $tag)
                                        <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-brand-dark-green">{{ $document->type_display_name }}</span>
                        </td>
                        <td>
                            @if($document->employee)
                            <a href="{{ route('employees.show', $document->employee) }}" class="text-decoration-none">
                                {{ $document->employee->code }} - {{ $document->employee->display_name }}
                            </a>
                            @elseif($document->contract)
                            <span class="text-muted">{{ $document->contract->employee->display_name ?? __('common.n_a') }}</span>
                            @else
                            <span class="text-muted">{{ __('common.system') }}</span>
                            @endif
                        </td>
                        <td>{{ $document->file_size }}</td>
                        <td>
                            @if($document->visibility === 'private')
                            <span class="badge bg-warning">
                                <i class="fas fa-lock"></i> {{ __('Private') }}
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-share"></i> {{ __('Shared') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($document->expires_at)
                            @if($document->is_expired)
                            <span class="badge bg-danger">
                                <i class="fas fa-times"></i> {{ $document->expires_at->format('Y-m-d') }}
                            </span>
                            @elseif($document->is_expiring_soon)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock"></i> {{ $document->expires_at->format('Y-m-d') }}
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> {{ $document->expires_at->format('Y-m-d') }}
                            </span>
                            @endif
                            @else
                            <span class="text-muted">{{ __('common.no_expiry') }}</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $document->created_at->format('Y-m-d H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('view', $document)
                                <a href="{{ route('documents.show', $document) }}"
                                    class="btn btn-outline-brand-primary" title="{{ __('common.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan

                                @can('download', $document)
                                <a href="{{ route('documents.download', $document) }}"
                                    class="btn btn-outline-success" title="{{ __('common.download') }}">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endcan

                                @can('update', $document)
                                <a href="{{ route('documents.edit', $document) }}"
                                    class="btn btn-outline-warning" title="{{ __('common.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan

                                @can('delete', $document)
                                <form method="POST" action="{{ route('documents.destroy', $document) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('{{ __('hrms.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('common.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3">
            <div class="text-muted">
                {{ __('common.showing_to_of_results', ['from' => $documents->firstItem(), 'to' => $documents->lastItem(), 'total' => $documents->total()]) }}
            </div>
            {{ $documents->links() }}
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('common.no_documents_found') }}</h5>
            <p class="text-muted">{{ __('common.try_adjust_search') }}</p>
            @can('create', App\Models\Document::class)
            <a href="{{ route('documents.create') }}" class="btn btn-brand-primary">
                <i class="fas fa-plus"></i> {{ __('hrms.document.add_document') }}
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-outline-brand-primary {
        color: var(--color-gold);
        border-color: var(--color-gold);
    }

    .btn-outline-brand-primary:hover {
        background-color: var(--color-gold);
        border-color: var(--color-gold);
        color: white;
    }
</style>
@endpush