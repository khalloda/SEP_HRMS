@extends('layouts.app')

@section('title', __('hrms.document.add_document'))

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h2 mb-0 brand-dark-green">{{ __('hrms.document.add_document') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('documents.index') }}">{{ __('hrms.documents') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('common.add') }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> {{ __('common.back') }}
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">{{ __('hrms.document.document_details') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Employee Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">{{ __('hrms.select_employee') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_id') is-invalid @enderror"
                            id="employee_id" name="employee_id" onchange="loadEmployeeContracts()">
                            <option value="">{{ __('hrms.select_employee') }}</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->code }} - {{ $employee->display_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contract Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contract_id" class="form-label">{{ __('hrms.contract_details') }} ({{ __('common.optional') }})</label>
                        <select class="form-select @error('contract_id') is-invalid @enderror" id="contract_id" name="contract_id">
                            <option value="">{{ __('hrms.dashboard.no_contract') }}</option>
                            @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                {{ $contract->title }} ({{ $contract->start_date->format('Y-m-d') }} -
                                {{ $contract->end_date ? $contract->end_date->format('Y-m-d') : __('common.ongoing') }})
                            </option>
                            @endforeach
                        </select>
                        @error('contract_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Document Type -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('common.document_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                            <option value="">{{ __('common.select_type') }}</option>
                            @foreach($documentTypes as $key => $type)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? $type['name_ar'] : $type['name_en'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Visibility -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="visibility" class="form-label">{{ __('common.visibility') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('visibility') is-invalid @enderror" id="visibility" name="visibility">
                            <option value="private" {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}>
                                <i class="fas fa-lock"></i> {{ __('common.private') }}
                            </option>
                            <option value="shared" {{ old('visibility') == 'shared' ? 'selected' : '' }}>
                                <i class="fas fa-share"></i> {{ __('common.shared') }}
                            </option>
                        </select>
                        @error('visibility')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('common.visibility_hint') }}</div>
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-3">
                <label for="file" class="form-label">{{ __('common.document_file') }} <span class="text-danger">*</span></label>
                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    {{ __('common.max_file_size_and_types') }}
                </div>
            </div>

            <div class="row">
                <!-- Expiry Date -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expires_at" class="form-label">{{ __('common.expires_on') }} ({{ __('common.optional') }})</label>
                        <input type="date" class="form-control @error('expires_at') is-invalid @enderror"
                            id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                        @error('expires_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ __('common.set_expiry_hint') }}
                        </div>
                    </div>
                </div>

                <!-- Watermark Note -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="watermark_note" class="form-label">{{ __('common.watermark_note') }} ({{ __('common.optional') }})</label>
                        <input type="text" class="form-control @error('watermark_note') is-invalid @enderror"
                            id="watermark_note" name="watermark_note" value="{{ old('watermark_note') }}"
                            placeholder="{{ __('common.watermark_placeholder') }}" maxlength="120">
                        @error('watermark_note')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ __('common.watermark_hint') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="mb-4">
                <label for="tags" class="form-label">{{ __('common.tags') }} ({{ __('common.optional') }})</label>
                <div class="row">
                    @foreach($tags as $tag)
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tags[]"
                                value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tag{{ $tag->id }}">
                                {{ $tag->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($tags->isEmpty())
                <div class="form-text text-muted">
                    {{ __('common.no_tags_available') }}
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-brand-primary">
                    <i class="fas fa-save"></i> {{ __('common.upload_document') }}
                </button>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadEmployeeContracts() {
        const employeeId = document.getElementById('employee_id').value;
        const contractSelect = document.getElementById('contract_id');

        // Clear existing options except the first one
        contractSelect.innerHTML = '<option value="">{{ __("hrms.dashboard.no_contract") }}</option>';

        if (employeeId) {
            fetch(`{{ route('documents.employee-contracts') }}?employee_id=${employeeId}`)
                .then(response => response.json())
                .then(contracts => {
                    contracts.forEach(contract => {
                        const option = document.createElement('option');
                        option.value = contract.id;
                        option.textContent = `${contract.title} (${contract.period})`;
                        contractSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading contracts:', error);
                });
        }
    }

    // File input validation
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('{{ __("common.file_too_large") }}');
                e.target.value = '';
                return;
            }

            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('{{ __("common.invalid_file_type") }}');
                e.target.value = '';
                return;
            }
        }
    });

    // Set minimum date for expiry to tomorrow
    document.getElementById('expires_at').min = new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0];
</script>
@endpush