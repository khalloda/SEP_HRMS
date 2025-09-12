<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Position extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'category',
        'grade_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'grade_order' => 'integer',
    ];

    /**
     * Get the localized name attribute.
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get employees with this position.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get active employees with this position.
     */
    public function activeEmployees()
    {
        return $this->hasMany(Employee::class)->where('status', 'active');
    }

    /**
     * Check if this is a lawyer position.
     */
    public function isLawyer(): bool
    {
        return $this->category === 'lawyer';
    }

    /**
     * Check if this is an admin position.
     */
    public function isAdmin(): bool
    {
        return $this->category === 'admin';
    }

    /**
     * Check if employees in this position are eligible for overtime.
     */
    public function isOvertimeEligible(): bool
    {
        // Only Messengers and Office Boys are eligible for overtime
        return in_array($this->name_en, ['Messenger', 'Office Boy']);
    }

    /**
     * Get the position's hierarchy level description.
     */
    public function getHierarchyLevelAttribute()
    {
        if ($this->isLawyer()) {
            $levels = [
                1 => 'Senior Management',
                2 => 'Senior Management', 
                3 => 'Management',
                4 => 'Management',
                5 => 'Senior Level',
                6 => 'Mid Level',
                7 => 'Junior Level',
                8 => 'Entry Level',
            ];
        } else {
            $levels = [
                1 => 'Department Head',
                2 => 'Department Head',
                3 => 'Senior Staff',
                4 => 'Senior Staff',
                5 => 'Support Staff',
                6 => 'Support Staff',
                7 => 'Support Staff',
            ];
        }

        return $levels[$this->grade_order] ?? 'Staff';
    }

    /**
     * Scope to search positions by name or category.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name_en', 'LIKE', "%{$term}%")
              ->orWhere('name_ar', 'LIKE', "%{$term}%")
              ->orWhere('category', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order by hierarchy (grade order).
     */
    public function scopeByHierarchy($query)
    {
        return $query->orderBy('category')->orderBy('grade_order');
    }

    /**
     * Get positions ordered by name.
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
            ->logOnly(['name_en', 'name_ar', 'category', 'grade_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get position statistics.
     */
    public function getStats()
    {
        return [
            'total_employees' => $this->employees()->count(),
            'active_employees' => $this->activeEmployees()->count(),
            'hierarchy_level' => $this->hierarchy_level,
            'overtime_eligible' => $this->isOvertimeEligible(),
        ];
    }
}