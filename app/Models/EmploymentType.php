<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmploymentType extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
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
     * Get employees with this employment type.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get active employees with this employment type.
     */
    public function activeEmployees()
    {
        return $this->hasMany(Employee::class)->where('status', 'active');
    }

    /**
     * Check if this is a permanent employment type.
     */
    public function isPermanent(): bool
    {
        return $this->name_en === 'Permanent';
    }

    /**
     * Check if this is a temporary employment type.
     */
    public function isTemporary(): bool
    {
        return in_array($this->name_en, ['Fixed-term', 'Probationary', 'Internship']);
    }

    /**
     * Check if this employment type requires end date.
     */
    public function requiresEndDate(): bool
    {
        return in_array($this->name_en, ['Fixed-term', 'Probationary', 'Internship']);
    }

    /**
     * Get default contract duration in months for this employment type.
     */
    public function getDefaultDurationAttribute()
    {
        $durations = [
            'Permanent' => null, // No end date
            'Fixed-term' => 12,  // 1 year
            'Probationary' => 3, // 3 months
            'Internship' => 6,   // 6 months
            'Consultancy' => 12, // 1 year
        ];

        return $durations[$this->name_en] ?? null;
    }

    /**
     * Get benefit eligibility for this employment type.
     */
    public function getBenefitEligibilityAttribute()
    {
        return [
            'health_insurance' => in_array($this->name_en, ['Permanent', 'Fixed-term']),
            'annual_leave' => in_array($this->name_en, ['Permanent', 'Fixed-term']),
            'sick_leave' => in_array($this->name_en, ['Permanent', 'Fixed-term', 'Probationary']),
            'end_of_service' => in_array($this->name_en, ['Permanent', 'Fixed-term']),
            'overtime_pay' => !in_array($this->name_en, ['Consultancy']),
        ];
    }

    /**
     * Scope to search employment types by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name_en', 'LIKE', "%{$term}%")
              ->orWhere('name_ar', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Get employment types ordered by name.
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
            ->logOnly(['name_en', 'name_ar'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get employment type statistics.
     */
    public function getStats()
    {
        return [
            'total_employees' => $this->employees()->count(),
            'active_employees' => $this->activeEmployees()->count(),
            'requires_end_date' => $this->requiresEndDate(),
            'default_duration_months' => $this->default_duration,
            'benefit_eligibility' => $this->benefit_eligibility,
        ];
    }
}