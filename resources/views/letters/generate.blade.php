@extends('layouts.app')

@section('title', __('Generate Letter'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0 brand-dark-green">{{ __('Generate Letter') }}</h1>
            <p class="text-muted mb-0">{{ __('Create a new HR letter from template') }}</p>
        </div>
        <a href="{{ route('letters.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back to Letters') }}
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Letter Generation Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gold text-white">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt"></i> {{ __('Letter Details') }}
                </h5>
            </div>
            <div class="card-body">
                <form id="letterForm">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('Employee') }}</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">{{ __('Select Employee') }}</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                        data-name="{{ $employee->display_name }}"
                                        data-code="{{ $employee->code }}"
                                        data-position="{{ $employee->position->name_en ?? '' }}"
                                        data-department="{{ $employee->department->name_en ?? '' }}">
                                    {{ $employee->display_name }} ({{ $employee->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">{{ __('Letter Template') }}</label>
                            <select name="template_id" class="form-select" required>
                                <option value="">{{ __('Select Template') }}</option>
                                @foreach($templates as $category => $categoryTemplates)
                                    <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                                        @foreach($categoryTemplates as $template)
                                        <option value="{{ $template->id }}"
                                                data-type="{{ $template->type }}"
                                                data-language="{{ $template->language }}"
                                                data-variables="{{ json_encode($template->getAllAvailableVariables()) }}">
                                            {{ $template->name }} ({{ $template->language === 'ar' ? 'العربية' : 'English' }})
                                        </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Additional Variables Section -->
                    <div id="additionalVariables" class="mb-4" style="display: none;">
                        <h6 class="fw-bold mb-3">{{ __('Additional Variables') }}</h6>
                        <div id="variablesContainer" class="row">
                            <!-- Dynamic variables will be added here -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" id="previewBtn" class="btn btn-outline-primary" disabled>
                            <i class="fas fa-eye"></i> {{ __('Preview') }}
                        </button>
                        <div>
                            <button type="button" id="saveDraftBtn" class="btn btn-outline-secondary me-2" disabled>
                                <i class="fas fa-save"></i> {{ __('Save Draft') }}
                            </button>
                            <button type="button" id="generateBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-file-alt"></i> {{ __('Generate & Submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Employee Info Panel -->
        <div id="employeeInfo" class="card border-0 shadow-sm mb-4" style="display: none;">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">{{ __('Employee Information') }}</h6>
            </div>
            <div class="card-body">
                <div id="employeeDetails">
                    <!-- Employee details will be populated here -->
                </div>
            </div>
        </div>

        <!-- Template Info Panel -->
        <div id="templateInfo" class="card border-0 shadow-sm mb-4" style="display: none;">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">{{ __('Template Information') }}</h6>
            </div>
            <div class="card-body">
                <div id="templateDetails">
                    <!-- Template details will be populated here -->
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">{{ __('Help') }}</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">{{ __('Available Variables:') }}</p>
                <ul class="small text-muted mb-0">
                    <li>@{{ employee_name }} - {{ __('Employee full name') }}</li>
                    <li>@{{ employee_code }} - {{ __('Employee code') }}</li>
                    <li>@{{ position }} - {{ __('Position title') }}</li>
                    <li>@{{ department }} - {{ __('Department name') }}</li>
                    <li>@{{ hire_date }} - {{ __('Date of hire') }}</li>
                    <li>@{{ current_date }} - {{ __('Current date') }}</li>
                    <li>@{{ company_name }} - {{ __('Company name') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Letter Preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewSubject" class="mb-3">
                    <strong>{{ __('Subject:') }}</strong> <span id="subjectText"></span>
                </div>
                <div id="previewContent" style="border: 1px solid #ddd; padding: 20px; min-height: 400px; background: white;">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="generateFromPreview" class="btn btn-primary">
                    {{ __('Generate Letter') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.querySelector('select[name="employee_id"]');
    const templateSelect = document.querySelector('select[name="template_id"]');
    const employeeInfo = document.getElementById('employeeInfo');
    const templateInfo = document.getElementById('templateInfo');
    const additionalVariables = document.getElementById('additionalVariables');
    const variablesContainer = document.getElementById('variablesContainer');
    const previewBtn = document.getElementById('previewBtn');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const generateBtn = document.getElementById('generateBtn');

    // Employee selection handler
    employeeSelect.addEventListener('change', function() {
        const option = this.selectedOptions[0];
        if (option.value) {
            showEmployeeInfo(option);
            checkFormValidity();
        } else {
            employeeInfo.style.display = 'none';
        }
    });

    // Template selection handler
    templateSelect.addEventListener('change', function() {
        const option = this.selectedOptions[0];
        if (option.value) {
            showTemplateInfo(option);
            showAdditionalVariables(option);
            checkFormValidity();
        } else {
            templateInfo.style.display = 'none';
            additionalVariables.style.display = 'none';
        }
    });

    function showEmployeeInfo(option) {
        const details = document.getElementById('employeeDetails');
        details.innerHTML = `
            <p class="mb-2"><strong>Name:</strong> ${option.dataset.name}</p>
            <p class="mb-2"><strong>Code:</strong> ${option.dataset.code}</p>
            <p class="mb-2"><strong>Position:</strong> ${option.dataset.position}</p>
            <p class="mb-0"><strong>Department:</strong> ${option.dataset.department}</p>
        `;
        employeeInfo.style.display = 'block';
    }

    function showTemplateInfo(option) {
        const details = document.getElementById('templateDetails');
        details.innerHTML = `
            <p class="mb-2"><strong>Type:</strong> ${option.dataset.type}</p>
            <p class="mb-0"><strong>Language:</strong> ${option.dataset.language === 'ar' ? 'العربية' : 'English'}</p>
        `;
        templateInfo.style.display = 'block';
    }

    function showAdditionalVariables(option) {
        try {
            const variables = JSON.parse(option.dataset.variables);
            const defaultVars = ['employee_name', 'employee_code', 'position', 'department', 'hire_date', 'salary', 'manager_name', 'company_name', 'current_date', 'arabic_date'];

            // Find custom variables (not in default list)
            const customVars = Object.keys(variables).filter(key => !defaultVars.includes(key));

            if (customVars.length > 0) {
                let html = '';
                customVars.forEach((varName, index) => {
                    html += `
                        <div class="col-md-6 mb-3">
                            <label class="form-label">${variables[varName]}</label>
                            <input type="text" name="additional_data[${varName}]" class="form-control"
                                   placeholder="Enter ${variables[varName].toLowerCase()}">
                        </div>
                    `;
                });
                variablesContainer.innerHTML = html;
                additionalVariables.style.display = 'block';
            } else {
                additionalVariables.style.display = 'none';
            }
        } catch (e) {
            console.error('Error parsing variables:', e);
            additionalVariables.style.display = 'none';
        }
    }

    function checkFormValidity() {
        const isValid = employeeSelect.value && templateSelect.value;
        previewBtn.disabled = !isValid;
        saveDraftBtn.disabled = !isValid;
        generateBtn.disabled = !isValid;
    }

    // Preview button handler
    previewBtn.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('letterForm'));

        fetch('{{ route("letters.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('subjectText').textContent = data.subject;
            document.getElementById('previewContent').innerHTML = data.content;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error loading preview") }}');
        });
    });

    // Generate buttons handler
    function generateLetter(status = 'pending_approval') {
        const formData = new FormData(document.getElementById('letterForm'));
        formData.append('status', status);

        fetch('{{ route("letters.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.href = '{{ route("letters.index") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error generating letter") }}');
        });
    }

    saveDraftBtn.addEventListener('click', () => generateLetter('draft'));
    generateBtn.addEventListener('click', () => generateLetter('pending_approval'));
    document.getElementById('generateFromPreview').addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
        generateLetter('pending_approval');
    });
});
</script>
@endpush

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}

#previewContent {
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}

.card-header {
    font-weight: 600;
}

.form-label {
    font-weight: 500;
}
</style>
@endpush
