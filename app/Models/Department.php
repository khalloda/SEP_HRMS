<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Department extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name_en',
        'name_ar',
    ];

    /**
     * Get the localized name attribute.
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get employees in this department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get active employees in this department.
     */
    public function activeEmployees()
    {
        return $this->hasMany(Employee::class)->where('status', 'active');
    }

    /**
     * Get the department's employee count.
     */
    public function getEmployeeCountAttribute()
    {
        return $this->employees()->count();
    }

    /**
     * Get the department's active employee count.
     */
    public function getActiveEmployeeCountAttribute()
    {
        return $this->activeEmployees()->count();
    }

    /**
     * Scope to search departments by name or code.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('code', 'LIKE', "%{$term}%")
              ->orWhere('name_en', 'LIKE', "%{$term}%")
              ->orWhere('name_ar', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Get departments ordered by name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name_en');
    }

    /**
     * Activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name_en', 'name_ar'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get department statistics.
     */
    public function getStats()
    {
        return [
            'total_employees' => $this->employees()->count(),
            'active_employees' => $this->activeEmployees()->count(),
            'lawyers' => $this->employees()
                ->join('positions', 'employees.position_id', '=', 'positions.id')
                ->where('positions.category', 'lawyer')
                ->where('employees.status', 'active')
                ->count(),
            'admin_staff' => $this->employees()
                ->join('positions', 'employees.position_id', '=', 'positions.id')
                ->where('positions.category', 'admin')
                ->where('employees.status', 'active')
                ->count(),
        ];
    }
}