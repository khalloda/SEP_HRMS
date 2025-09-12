<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'guard_name',
    ];

    /**
     * Get the display name for the role.
     */
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Check if this is an HR management role.
     */
    public function isHRRole(): bool
    {
        return in_array($this->name, ['HR_Admin_Manager', 'HR_Coordinator']);
    }

    /**
     * Check if this is an accounting role.
     */
    public function isAccountingRole(): bool
    {
        return in_array($this->name, ['Accounting_Manager', 'Accountant']);
    }

    /**
     * Check if this role can view net/gross salaries.
     */
    public function canViewNetGross(): bool
    {
        return in_array($this->name, ['HR_Admin_Manager', 'Accounting_Manager']);
    }

    /**
     * Check if this is an employee self-service role.
     */
    public function isEmployeeRole(): bool
    {
        return $this->name === 'Employee';
    }

    /**
     * Get the role description based on locale.
     */
    public function getDescriptionAttribute()
    {
        $descriptions = [
            'HR_Admin_Manager' => [
                'en' => 'Full access to all HR and administrative functions',
                'ar' => 'وصول كامل لجميع وظائف الموارد البشرية والإدارية'
            ],
            'Accounting_Manager' => [
                'en' => 'Full payroll access including salary details',
                'ar' => 'وصول كامل للرواتب بما في ذلك تفاصيل الراتب'
            ],
            'HR_Coordinator' => [
                'en' => 'HR operations without salary details access',
                'ar' => 'عمليات الموارد البشرية بدون الوصول لتفاصيل الراتب'
            ],
            'Accountant' => [
                'en' => 'Financial operations without salary details access',
                'ar' => 'العمليات المالية بدون الوصول لتفاصيل الراتب'
            ],
            'Employee' => [
                'en' => 'Self-service access to own information only',
                'ar' => 'وصول للخدمة الذاتية للمعلومات الشخصية فقط'
            ],
            'IT_Admin' => [
                'en' => 'System administration without HR content access',
                'ar' => 'إدارة النظام بدون الوصول لمحتوى الموارد البشرية'
            ],
        ];

        $locale = app()->getLocale();
        return $descriptions[$this->name][$locale] ?? $this->display_name;
    }
}