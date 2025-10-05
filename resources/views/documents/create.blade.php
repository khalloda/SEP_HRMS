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
                    <li class="breadcrumb-item active">{{ __('Add') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
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
                            <label for="employee_id" class="form-label">{{ __('Employee') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" onchange="loadEmployeeContracts()">
                                <option value="">{{ __('Select Employee') }}</option>
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
                            <label for="contract_id" class="form-label">{{ __('Contract') }} ({{ __('Optional') }})</label>
                            <select class="form-select @error('contract_id') is-invalid @enderror" id="contract_id" name="contract_id">
                                <option value="">{{ __('No Contract') }}</option>
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
                                        {{ $contract->title }} ({{ $contract->start_date->format('Y-m-d') }} - 
                                        {{ $contract->end_date ? $contract->end_date->format('Y-m-d') : 'Ongoing' }})
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
                            <label for="type" class="form-label">{{ __('Document Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                <option value="">{{ __('Select Type') }}</option>
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
                            <label for="visibility" class="form-label">{{ __('Visibility') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('visibility') is-invalid @enderror" id="visibility" name="visibility">
                                <option value="private" {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}>
                                    <i class="fas fa-lock"></i> {{ __('Private') }}
                                </option>
                                <option value="shared" {{ old('visibility') == 'shared' ? 'selected' : '' }}>
                                    <i class="fas fa-share"></i> {{ __('Shared') }}
                                </option>
                            </select>
                            @error('visibility')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                {{ __('Private documents are only visible to authorized personnel. Shared documents can be viewed by relevant departments.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="mb-3">
                    <label for="file" class="form-label">{{ __('Document File') }} <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file">
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        {{ __('Maximum file size: 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG') }}
                    </div>
                </div>

                <div class="row">
                    <!-- Expiry Date -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">{{ __('Expires On') }} ({{ __('Optional') }})</label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                {{ __('Set an expiry date for documents that need renewal (e.g., licenses, certificates)') }}
                            </div>
                        </div>
                    </div>

                    <!-- Watermark Note -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="watermark_note" class="form-label">{{ __('Watermark Note') }} ({{ __('Optional') }})</label>
                            <input type="text" class="form-control @error('watermark_note') is-invalid @enderror" 
                                   id="watermark_note" name="watermark_note" value="{{ old('watermark_note') }}" 
                                   placeholder="{{ __('e.g., Confidential - HR Use Only') }}" maxlength="120">
                            @error('watermark_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                {{ __('Optional watermark text to appear on downloaded documents') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="mb-4">
                    <label for="tags" class="form-label">{{ __('Tags') }} ({{ __('Optional') }})</label>
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
                            {{ __('No tags available. Create tags first to organize your documents.') }}
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand-primary">
                        <i class="fas fa-save"></i> {{ __('Upload Document') }}
                    </button>
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('Cancel') }}
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
    contractSelect.innerHTML = '<option value="">{{ __("No Contract") }}</option>';
    
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
            alert('{{ __("File size exceeds 10MB limit.") }}');
            e.target.value = '';
            return;
        }
        
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('{{ __("Please select a valid file type (PDF, DOC, DOCX, JPG, PNG).") }}');
            e.target.value = '';
            return;
        }
    }
});

// Set minimum date for expiry to tomorrow
document.getElementById('expires_at').min = new Date(new Date().setDate(new Date().getDate() + 1)).toISOString().split('T')[0];
</script>
@endpush