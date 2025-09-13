<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SalaryComponent extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
        'comp_type',
        'calc_mode',
        'taxable',
        'visible_to_roles',
        'priority_order',
    ];

    protected $casts = [
        'visible_to_roles' => 'array',
        'taxable' => 'boolean',
        'priority_order' => 'integer',
    ];

    /**
     * Component types.
     */
    const TYPES = [
        'earning' => 'Earning',
        'deduction' => 'Deduction',
        'info' => 'Information Only'
    ];

    /**
     * Calculation modes.
     */
    const CALC_MODES = [
        'fixed' => 'Fixed Amount',
        'formula' => 'Formula Based',
        'variable_net_based' => 'Variable (Net Based)'
    ];

    /**
     * Get salary structures that use this component.
     */
    public function salaryStructures(): BelongsToMany
    {
        return $this->belongsToMany(SalaryStructure::class, 'salary_structure_components')
            ->withPivot(['amount_or_formula', 'effective_from', 'effective_to'])
            ->withTimestamps();
    }

    /**
     * Scope: Filter by component type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('comp_type', $type);
    }

    /**
     * Scope: Only earnings.
     */
    public function scopeEarnings(Builder $query): Builder
    {
        return $query->where('comp_type', 'earning');
    }

    /**
     * Scope: Only deductions.
     */
    public function scopeDeductions(Builder $query): Builder
    {
        return $query->where('comp_type', 'deduction');
    }

    /**
     * Scope: Only information components.
     */
    public function scopeInfoOnly(Builder $query): Builder
    {
        return $query->where('comp_type', 'info');
    }

    /**
     * Scope: Only taxable components.
     */
    public function scopeTaxable(Builder $query): Builder
    {
        return $query->where('taxable', true);
    }

    /**
     * Scope: Only non-taxable components.
     */
    public function scopeNonTaxable(Builder $query): Builder
    {
        return $query->where('taxable', false);
    }

    /**
     * Scope: Order by priority.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('priority_order');
    }

    /**
     * Scope: Search components by name or code.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name_en', 'like', "%{$term}%")
              ->orWhere('name_ar', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%");
        });
    }

    /**
     * Get the display name based on current locale.
     */
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get the component type display name.
     */
    public function getTypeDisplayNameAttribute(): string
    {
        return self::TYPES[$this->comp_type] ?? $this->comp_type;
    }

    /**
     * Get the calculation mode display name.
     */
    public function getCalcModeDisplayNameAttribute(): string
    {
        return self::CALC_MODES[$this->calc_mode] ?? $this->calc_mode;
    }

    /**
     * Check if the current user can view this component based on role visibility.
     */
    public function canViewForUser($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        // If no role restrictions, everyone can see it
        if (empty($this->visible_to_roles)) {
            return true;
        }

        // Check if user has any of the allowed roles
        foreach ($this->visible_to_roles as $roleName) {
            if ($user->hasRole($roleName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get components visible to the current user.
     */
    public function scopeVisibleToUser(Builder $query, $user = null): Builder
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return $query->whereNull('id'); // Return empty result
        }

        $userRoles = $user->getRoleNames()->toArray();

        return $query->where(function ($q) use ($userRoles) {
            $q->whereNull('visible_to_roles')
              ->orWhere('visible_to_roles', '[]')
              ->orWhere(function ($subQ) use ($userRoles) {
                  foreach ($userRoles as $role) {
                      $subQ->orWhereJsonContains('visible_to_roles', $role);
                  }
              });
        });
    }

    /**
     * Get predefined salary components.
     */
    public static function getPredefinedComponents(): array
    {
        return [
            [
                'code' => 'BASIC_SALARY',
                'name_en' => 'Basic Salary',
                'name_ar' => 'الراتب الأساسي',
                'comp_type' => 'earning',
                'calc_mode' => 'fixed',
                'taxable' => true,
                'priority_order' => 1,
            ],
            [
                'code' => 'HOUSING_ALLOWANCE',
                'name_en' => 'Housing Allowance',
                'name_ar' => 'بدل السكن',
                'comp_type' => 'earning',
                'calc_mode' => 'fixed',
                'taxable' => true,
                'priority_order' => 2,
            ],
            [
                'code' => 'TRANSPORT_ALLOWANCE',
                'name_en' => 'Transportation Allowance',
                'name_ar' => 'بدل المواصلات',
                'comp_type' => 'earning',
                'calc_mode' => 'fixed',
                'taxable' => true,
                'priority_order' => 3,
            ],
            [
                'code' => 'OVERTIME',
                'name_en' => 'Overtime',
                'name_ar' => 'العمل الإضافي',
                'comp_type' => 'earning',
                'calc_mode' => 'variable_net_based',
                'taxable' => true,
                'priority_order' => 4,
            ],
            [
                'code' => 'BONUS',
                'name_en' => 'Bonus',
                'name_ar' => 'المكافأة',
                'comp_type' => 'earning',
                'calc_mode' => 'variable_net_based',
                'taxable' => true,
                'priority_order' => 5,
            ],
            [
                'code' => 'SOCIAL_INSURANCE',
                'name_en' => 'Social Insurance Deduction',
                'name_ar' => 'خصم التأمين الاجتماعي',
                'comp_type' => 'deduction',
                'calc_mode' => 'formula',
                'taxable' => false,
                'priority_order' => 101,
            ],
            [
                'code' => 'INCOME_TAX',
                'name_en' => 'Income Tax',
                'name_ar' => 'ضريبة الدخل',
                'comp_type' => 'deduction',
                'calc_mode' => 'formula',
                'taxable' => false,
                'priority_order' => 102,
            ],
            [
                'code' => 'ADVANCE_DEDUCTION',
                'name_en' => 'Advance Salary Deduction',
                'name_ar' => 'خصم السلفة',
                'comp_type' => 'deduction',
                'calc_mode' => 'variable_net_based',
                'taxable' => false,
                'priority_order' => 103,
            ],
            [
                'code' => 'LOAN_DEDUCTION',
                'name_en' => 'Loan Deduction',
                'name_ar' => 'قسط القرض',
                'comp_type' => 'deduction',
                'calc_mode' => 'fixed',
                'taxable' => false,
                'priority_order' => 104,
            ],
            [
                'code' => 'GROSS_SALARY',
                'name_en' => 'Gross Salary',
                'name_ar' => 'إجمالي الراتب',
                'comp_type' => 'info',
                'calc_mode' => 'formula',
                'taxable' => false,
                'visible_to_roles' => ['HR_Admin_Manager', 'Accounting_Manager'],
                'priority_order' => 201,
            ],
            [
                'code' => 'NET_SALARY',
                'name_en' => 'Net Salary',
                'name_ar' => 'صافي الراتب',
                'comp_type' => 'info',
                'calc_mode' => 'formula',
                'taxable' => false,
                'visible_to_roles' => ['HR_Admin_Manager', 'Accounting_Manager'],
                'priority_order' => 202,
            ],
        ];
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name_en', 'name_ar', 'comp_type', 'calc_mode', 'taxable', 'priority_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Salary component {$eventName}");
    }
}