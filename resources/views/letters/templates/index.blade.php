@extends('layouts.app')

@section('title', __('Letter Templates'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0 brand-dark-green">{{ __('Letter Templates') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage HR letter templates') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @can('create', App\Models\LetterTemplate::class)
            <a href="{{ route('letters.templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('Create Template') }}
            </a>
            <form method="POST" action="{{ route('letters.templates.seed') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary"
                        onclick="return confirm('{{ __('This will create default letter templates. Continue?') }}')">
                    <i class="fas fa-seedling"></i> {{ __('Seed Defaults') }}
                </button>
            </form>
            @endcan
        </div>
    </div>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('letters.templates.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">{{ __('Search') }}</label>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}"
                       placeholder="{{ __('Search templates...') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Type') }}</label>
                <select name="type" class="form-select">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Category') }}</label>
                <select name="category" class="form-select">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Language') }}</label>
                <select name="language" class="form-select">
                    <option value="">{{ __('All Languages') }}</option>
                    <option value="en" {{ request('language') === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ request('language') === 'ar' ? 'selected' : '' }}>العربية</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i> {{ __('Filter') }}
                </button>
                <a href="{{ route('letters.templates.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> {{ __('Clear') }}
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Templates List -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-gold text-white">
        <h5 class="mb-0">
            <i class="fas fa-file-alt"></i> {{ __('Templates') }}
            <span class="badge bg-light text-dark ms-2">{{ $templates->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($templates->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Template Name') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Usage') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $template->name }}</div>
                                <small class="text-muted">{{ Str::limit($template->subject, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $template->type_label }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $template->category_label }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $template->language === 'ar' ? 'bg-success' : 'bg-info' }}">
                                    {{ $template->language === 'ar' ? 'العربية' : 'English' }}
                                </span>
                            </td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $template->generated_letters_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    {{ $template->created_at->format('d/m/Y') }}<br>
                                    <small>{{ __('by') }} {{ $template->createdBy->name }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @can('view', $template)
                                    <a href="{{ route('letters.templates.show', $template) }}"
                                       class="btn btn-outline-primary" title="{{ __('View') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    @can('update', $template)
                                    <a href="{{ route('letters.templates.edit', $template) }}"
                                       class="btn btn-outline-secondary" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $template)
                                    <form method="POST" action="{{ route('letters.templates.destroy', $template) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this template?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="{{ __('Delete') }}">
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
            @if($templates->hasPages())
                <div class="card-footer">
                    {{ $templates->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No letter templates found') }}</h5>
                <p class="text-muted">{{ __('Create your first template or use the default seed templates.') }}</p>
                @can('create', App\Models\LetterTemplate::class)
                <div class="mt-3">
                    <a href="{{ route('letters.templates.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus"></i> {{ __('Create Template') }}
                    </a>
                    <form method="POST" action="{{ route('letters.templates.seed') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-seedling"></i> {{ __('Seed Defaults') }}
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.badge {
    font-size: 0.75rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endpush