<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'arabic_name',
        'email',
        'phone',
        'hire_date',
        'status',
        'department_id',
        'position_id',
        'employment_type_id',
        'manager_id',
        'national_id',
        'salary_visibility_flag',
        'photo_path',
        'photo_original_name',
        'photo_size',
        'photo_mime_type',
        'photo_uploaded_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hire_date' => 'date',
        'salary_visibility_flag' => 'boolean',
        'national_id' => 'encrypted',
        'photo_uploaded_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'national_id',
    ];

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the display name based on locale.
     */
    public function getDisplayNameAttribute()
    {
        if (app()->getLocale() === 'ar' && $this->arabic_name) {
            return $this->arabic_name;
        }
        return $this->full_name;
    }

    /**
     * Get the employee's department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the employee's position.
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the employee's employment type.
     */
    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    /**
     * Get the employee's manager.
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the employees who report to this employee.
     */
    public function directReports()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Get the user account associated with this employee.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the employee's contracts.
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get documents for this employee.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the employee's active contract.
     */
    public function activeContract()
    {
        return $this->hasOne(Contract::class)->where('status', 'active');
    }


    /**
     * Get the employee's payslips.
     */
    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    /**
     * Get the employee's attendance logs.
     */
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'employee_code', 'code');
    }

    /**
     * Get all salary structures for this employee.
     */
    public function salaryStructures()
    {
        return $this->hasMany(SalaryStructure::class);
    }

    /**
     * Get the current active salary structure for this employee.
     */
    public function currentSalaryStructure()
    {
        return $this->hasOne(SalaryStructure::class)
            ->current()
            ->with('components')
            ->latest('effective_from');
    }

    /**
     * Get the most recent salary structure (active or not).
     */
    public function latestSalaryStructure()
    {
        return $this->hasOne(SalaryStructure::class)
            ->with('components')
            ->latest('effective_from');
    }

    /**
     * Check if employee is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if employee is on leave.
     */
    public function isOnLeave(): bool
    {
        return $this->status === 'on_leave';
    }

    /**
     * Check if employee is terminated.
     */
    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }

    /**
     * Check if employee is eligible for overtime based on position.
     */
    public function isOvertimeEligible(): bool
    {
        return $this->position && $this->position->isOvertimeEligible();
    }

    /**
     * Get employee hierarchy level.
     */
    public function getHierarchyLevelAttribute()
    {
        return $this->position ? $this->position->hierarchy_level : 'Staff';
    }

    /**
     * Check if this employee can be managed by the given user.
     */
    public function canBeViewedBy(User $user): bool
    {
        // HR roles can view all employees
        if ($user->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator'])) {
            return true;
        }

        // Accounting roles can view all employees (for payroll purposes)
        if ($user->hasAnyRole(['Accounting_Manager', 'Accountant'])) {
            return true;
        }

        // Managers can view their direct reports
        if ($user->employee && $this->manager_id === $user->employee->id) {
            return true;
        }

        // Users can view their own employee record
        if ($user->employee && $user->employee->id === $this->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if salary information can be viewed by the given user.
     */
    public function canViewSalaryBy(User $user): bool
    {
        // Only specific roles can view salary information
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // User can view their own salary if visibility flag is enabled
        if ($user->employee && $user->employee->id === $this->id && $this->salary_visibility_flag) {
            return true;
        }

        return false;
    }

    /**
     * Scope to search employees using fulltext search.
     */
    public function scopeSearch(Builder $query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->whereRaw(
            "MATCH(first_name, last_name, arabic_name, email, code) AGAINST(? IN NATURAL LANGUAGE MODE)",
            [$term]
        );
    }

    /**
     * Scope to search employees by basic text match (fallback).
     */
    public function scopeBasicSearch(Builder $query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('code', 'LIKE', "%{$term}%")
              ->orWhere('first_name', 'LIKE', "%{$term}%")
              ->orWhere('last_name', 'LIKE', "%{$term}%")
              ->orWhere('arabic_name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by department.
     */
    public function scopeByDepartment(Builder $query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope to filter by position.
     */
    public function scopeByPosition(Builder $query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    /**
     * Scope to filter by employment type.
     */
    public function scopeByEmploymentType(Builder $query, $employmentTypeId)
    {
        return $query->where('employment_type_id', $employmentTypeId);
    }

    /**
     * Scope to filter by manager.
     */
    public function scopeByManager(Builder $query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Scope to get active employees.
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get employees with no manager (top level).
     */
    public function scopeTopLevel(Builder $query)
    {
        return $query->whereNull('manager_id');
    }

    /**
     * Scope to order by name.
     */
    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('first_name')->orderBy('last_name');
    }

    /**
     * Scope to include related models for efficiency.
     */
    public function scopeWithRelations(Builder $query)
    {
        return $query->with(['department', 'position', 'employmentType', 'manager']);
    }

    /**
     * Activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'code', 'first_name', 'last_name', 'arabic_name', 'email', 
                'phone', 'hire_date', 'status', 'department_id', 'position_id', 
                'employment_type_id', 'manager_id', 'salary_visibility_flag',
                'photo_original_name', 'photo_uploaded_at'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Employee {$eventName}")
            ->useLogName('employee');
    }

    /**
     * Get employee statistics and summary information.
     */
    public function getStats()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'full_name' => $this->full_name,
            'display_name' => $this->display_name,
            'status' => $this->status,
            'department' => $this->department?->name,
            'position' => $this->position?->name,
            'employment_type' => $this->employmentType?->name,
            'manager' => $this->manager?->full_name,
            'hire_date' => $this->hire_date?->format('Y-m-d'),
            'years_of_service' => $this->hire_date ? $this->hire_date->diffInYears(now()) : null,
            'direct_reports_count' => $this->directReports()->count(),
            'is_overtime_eligible' => $this->isOvertimeEligible(),
            'hierarchy_level' => $this->hierarchy_level,
            'has_user_account' => $this->user !== null,
            'contracts_count' => $this->contracts()->count(),
            'active_contract' => $this->activeContract !== null,
            'documents_count' => $this->documents()->count(),
        ];
    }

    /**
     * Get the employee's photo URL.
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo_path) {
            return $this->getDefaultPhotoUrl();
        }

        return route('employee.photo', ['employee' => $this->id]);
    }

    /**
     * Get the default photo URL (avatar placeholder).
     */
    public function getDefaultPhotoUrl()
    {
        $name = urlencode($this->full_name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=c6a44a&color=2e4029&font-size=0.6&bold=true";
    }

    /**
     * Check if employee has a photo uploaded.
     */
    public function hasPhoto(): bool
    {
        return !empty($this->photo_path) && \Storage::disk('private')->exists($this->photo_path);
    }

    /**
     * Get photo file size in human readable format.
     */
    public function getPhotoSizeFormatted()
    {
        if (!$this->photo_size) {
            return null;
        }

        $bytes = $this->photo_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Delete the employee's photo file.
     */
    public function deletePhoto(): bool
    {
        if ($this->photo_path && \Storage::disk('private')->exists($this->photo_path)) {
            $deleted = \Storage::disk('private')->delete($this->photo_path);
            
            if ($deleted) {
                $this->update([
                    'photo_path' => null,
                    'photo_original_name' => null,
                    'photo_size' => null,
                    'photo_mime_type' => null,
                    'photo_uploaded_at' => null,
                ]);
                
                return true;
            }
        }
        
        return false;
    }

    /**
     * Update photo information after upload.
     */
    public function updatePhoto($path, $originalName, $size, $mimeType): bool
    {
        return $this->update([
            'photo_path' => $path,
            'photo_original_name' => $originalName,
            'photo_size' => $size,
            'photo_mime_type' => $mimeType,
            'photo_uploaded_at' => now(),
        ]);
    }

    /**
     * Check if employee has an active salary structure.
     */
    public function hasActiveSalaryStructure(): bool
    {
        return $this->currentSalaryStructure !== null;
    }

    /**
     * Get current gross salary amount.
     */
    public function getCurrentGrossSalary(): ?float
    {
        $structure = $this->currentSalaryStructure;
        return $structure ? $structure->calculateGrossSalary() : null;
    }

    /**
     * Get current net salary amount.
     */
    public function getCurrentNetSalary(): ?float
    {
        $structure = $this->currentSalaryStructure;
        return $structure ? $structure->calculateNetSalary() : null;
    }

    /**
     * Get basic salary component value from current structure.
     */
    public function getBasicSalary(): ?float
    {
        $structure = $this->currentSalaryStructure;
        if (!$structure) {
            return null;
        }

        $basicComponent = $structure->components()
            ->where('code', 'BASIC_SALARY')
            ->first();

        if (!$basicComponent) {
            return null;
        }

        return $structure->calculateComponentValue($basicComponent);
    }

    /**
     * Create a new salary structure for this employee.
     */
    public function createSalaryStructure(array $data): SalaryStructure
    {
        // End current structure if exists
        $currentStructure = $this->currentSalaryStructure;
        if ($currentStructure) {
            $currentStructure->update([
                'effective_to' => $data['effective_from']->subDay()
            ]);
        }

        return $this->salaryStructures()->create($data);
    }

    /**
     * Check if user can view this employee's salary information.
     */
    public function canViewSalaryInformation(User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return $this->canViewSalaryBy($user);
    }

    /**
     * Generate next available employee code.
     */
    public static function generateNextCode($prefix = 'EMP')
    {
        $lastEmployee = static::where('code', 'LIKE', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastEmployee) {
            return $prefix . '001';
        }

        $lastNumber = (int) substr($lastEmployee->code, strlen($prefix));
        $nextNumber = $lastNumber + 1;

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($employee) {
            if (empty($employee->code)) {
                $employee->code = static::generateNextCode();
            }
        });
    }
}