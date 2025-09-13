<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LetterTemplate extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'type',
        'subject',
        'content',
        'variables',
        'is_active',
        'language',
        'category',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Letter types
    const TYPES = [
        'employment_certificate' => 'Employment Certificate',
        'salary_certificate' => 'Salary Certificate',
        'experience_certificate' => 'Experience Certificate',
        'promotion_letter' => 'Promotion Letter',
        'transfer_letter' => 'Transfer Letter',
        'warning_letter' => 'Warning Letter',
        'termination_letter' => 'Termination Letter',
        'resignation_acceptance' => 'Resignation Acceptance',
        'probation_completion' => 'Probation Completion',
        'contract_renewal' => 'Contract Renewal',
        'leave_approval' => 'Leave Approval',
        'custom' => 'Custom Letter'
    ];

    // Categories
    const CATEGORIES = [
        'certificates' => 'Certificates',
        'employment_actions' => 'Employment Actions',
        'disciplinary' => 'Disciplinary Actions',
        'administrative' => 'Administrative'
    ];

    // Default variables available for all letters
    const DEFAULT_VARIABLES = [
        'employee_name' => 'Employee Full Name',
        'employee_code' => 'Employee Code',
        'position' => 'Position Title',
        'department' => 'Department Name',
        'hire_date' => 'Date of Hire',
        'salary' => 'Current Salary',
        'manager_name' => 'Manager Name',
        'company_name' => 'Company Name',
        'current_date' => 'Current Date',
        'arabic_date' => 'Arabic Date'
    ];

    /**
     * Relationships
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function generatedLetters()
    {
        return $this->hasMany(GeneratedLetter::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Helper methods
     */
    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getCategoryLabelAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getAllAvailableVariables()
    {
        $defaultVars = self::DEFAULT_VARIABLES;
        $customVars = $this->variables ?? [];

        return array_merge($defaultVars, $customVars);
    }

    public function processContent($employee, $additionalData = [])
    {
        $content = $this->content;

        // Replace default variables
        $replacements = $this->getVariableReplacements($employee, $additionalData);

        foreach ($replacements as $variable => $value) {
            $content = str_replace('{{' . $variable . '}}', $value, $content);
        }

        return $content;
    }

    private function getVariableReplacements($employee, $additionalData = [])
    {
        $replacements = [
            'employee_name' => $employee->display_name,
            'employee_code' => $employee->code,
            'position' => $employee->position->name_en ?? '',
            'department' => $employee->department->name_en ?? '',
            'hire_date' => $employee->hire_date ? $employee->hire_date->format('d/m/Y') : '',
            'salary' => $employee->currentSalary ? number_format($employee->currentSalary->basic_salary) : '',
            'manager_name' => $employee->manager->display_name ?? '',
            'company_name' => 'Sarie Eldin & Partners Legal Advisors',
            'current_date' => now()->format('d/m/Y'),
            'arabic_date' => now()->locale('ar')->isoFormat('D MMMM YYYY')
        ];

        // Merge additional data
        return array_merge($replacements, $additionalData);
    }

    /**
     * Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'type', 'subject', 'is_active', 'language'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Letter template {$eventName}");
    }
}