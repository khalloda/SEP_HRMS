<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
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
     * Get the display name for the permission.
     */
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Get permission category (e.g., 'employee', 'payroll', 'contract').
     */
    public function getCategoryAttribute()
    {
        return explode('.', $this->name)[0] ?? 'general';
    }

    /**
     * Get permission action (e.g., 'view', 'edit', 'delete').
     */
    public function getActionAttribute()
    {
        $parts = explode('.', $this->name);
        return $parts[1] ?? 'unknown';
    }

    /**
     * Check if this is a sensitive permission that requires audit logging.
     */
    public function isSensitive(): bool
    {
        $sensitivePermissions = [
            'payroll.view_net',
            'payroll.edit',
            'employee.delete',
            'contract.terminate',
            'document.delete',
        ];

        return in_array($this->name, $sensitivePermissions);
    }

    /**
     * Get grouped permissions by category.
     */
    public static function getGroupedPermissions()
    {
        return static::all()->groupBy('category');
    }

    /**
     * Get permission description based on locale.
     */
    public function getDescriptionAttribute()
    {
        $descriptions = [
            'employee.view' => [
                'en' => 'View employee information and profiles',
                'ar' => 'عرض معلومات الموظفين والملفات الشخصية'
            ],
            'employee.edit' => [
                'en' => 'Create and modify employee records',
                'ar' => 'إنشاء وتعديل سجلات الموظفين'
            ],
            'employee.delete' => [
                'en' => 'Delete employee records (sensitive)',
                'ar' => 'حذف سجلات الموظفين (حساس)'
            ],
            'contract.view' => [
                'en' => 'View employment contracts',
                'ar' => 'عرض عقود العمل'
            ],
            'contract.edit' => [
                'en' => 'Create and modify contracts',
                'ar' => 'إنشاء وتعديل العقود'
            ],
            'contract.terminate' => [
                'en' => 'Terminate employment contracts',
                'ar' => 'إنهاء عقود العمل'
            ],
            'payroll.view' => [
                'en' => 'View payroll components (without net/gross)',
                'ar' => 'عرض مكونات الرواتب (بدون الصافي/الإجمالي)'
            ],
            'payroll.view_net' => [
                'en' => 'View complete salary details including net/gross',
                'ar' => 'عرض تفاصيل الراتب الكاملة بما في ذلك الصافي/الإجمالي'
            ],
            'payroll.edit' => [
                'en' => 'Modify payroll structures and run payroll',
                'ar' => 'تعديل هياكل الرواتب وتشغيل الرواتب'
            ],
            'attendance.view' => [
                'en' => 'View attendance records and reports',
                'ar' => 'عرض سجلات الحضور والتقارير'
            ],
            'attendance.edit' => [
                'en' => 'Modify attendance records manually',
                'ar' => 'تعديل سجلات الحضور يدوياً'
            ],
            'document.view' => [
                'en' => 'View employee documents and files',
                'ar' => 'عرض مستندات وملفات الموظفين'
            ],
            'document.upload' => [
                'en' => 'Upload and manage documents',
                'ar' => 'رفع وإدارة المستندات'
            ],
        ];

        $locale = app()->getLocale();
        return $descriptions[$this->name][$locale] ?? $this->display_name;
    }
}