<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'terms_json',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'terms_json' => 'array',
    ];

    /**
     * Contract types enum values.
     */
    public const TYPES = [
        'permanent' => 'Permanent',
        'fixed_term' => 'Fixed Term',
        'probation' => 'Probationary',
        'internship' => 'Internship',
        'consultancy' => 'Consultancy',
    ];

    /**
     * Contract status enum values.
     */
    public const STATUSES = [
        'active' => 'Active',
        'expired' => 'Expired',
        'terminated' => 'Terminated',
        'pending' => 'Pending',
    ];

    /**
     * Get the employee that owns the contract.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get contract documents.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Check if contract is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if contract is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Check if contract is terminated.
     */
    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }

    /**
     * Check if contract is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if contract requires end date.
     */
    public function requiresEndDate(): bool
    {
        return in_array($this->type, ['fixed_term', 'probation', 'internship', 'consultancy']);
    }

    /**
     * Check if contract is renewable.
     */
    public function isRenewable(): bool
    {
        return in_array($this->type, ['fixed_term', 'probation']);
    }

    /**
     * Get contract duration in months.
     */
    public function getDurationInMonthsAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        
        return $this->start_date->diffInMonths($this->end_date);
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->end_date || $this->isExpired() || $this->isTerminated()) {
            return null;
        }

        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Check if contract is expiring soon.
     */
    public function isExpiringSoon($days = 30): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;
        
        return $daysUntilExpiry !== null && $daysUntilExpiry >= 0 && $daysUntilExpiry <= $days;
    }

    /**
     * Check if contract is expiring critically soon.
     */
    public function isExpiringCritically($days = 15): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;
        
        return $daysUntilExpiry !== null && $daysUntilExpiry >= 0 && $daysUntilExpiry <= $days;
    }

    /**
     * Check if contract is expiring urgently.
     */
    public function isExpiringUrgently($days = 7): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;
        
        return $daysUntilExpiry !== null && $daysUntilExpiry >= 0 && $daysUntilExpiry <= $days;
    }

    /**
     * Get the expiry urgency level.
     */
    public function getExpiryUrgencyLevelAttribute()
    {
        if ($this->isExpiringUrgently()) {
            return 'urgent';
        } elseif ($this->isExpiringCritically()) {
            return 'critical';
        } elseif ($this->isExpiringSoon()) {
            return 'soon';
        }
        
        return 'normal';
    }

    /**
     * Get localized contract type name.
     */
    public function getTypeNameAttribute()
    {
        return __('hrms.contract_types.' . $this->type, self::TYPES[$this->type] ?? $this->type);
    }

    /**
     * Get localized contract status name.
     */
    public function getStatusNameAttribute()
    {
        return __('hrms.contract_status.' . $this->status, self::STATUSES[$this->status] ?? $this->status);
    }

    /**
     * Get default terms for contract type.
     */
    public function getDefaultTerms(): array
    {
        $commonTerms = [
            'confidentiality' => true,
            'non_compete' => false,
            'probation_period' => null,
            'notice_period_days' => 30,
            'working_hours' => 8,
            'annual_leave_days' => 21,
            'sick_leave_days' => 15,
        ];

        $typeSpecificTerms = match($this->type) {
            'permanent' => [
                'end_of_service_eligible' => true,
                'health_insurance' => true,
                'annual_leave_days' => 21,
                'notice_period_days' => 30,
            ],
            'fixed_term' => [
                'end_of_service_eligible' => true,
                'health_insurance' => true,
                'renewal_option' => true,
                'notice_period_days' => 30,
            ],
            'probation' => [
                'probation_period' => 90,
                'health_insurance' => false,
                'annual_leave_days' => 0,
                'notice_period_days' => 7,
                'evaluation_required' => true,
            ],
            'internship' => [
                'stipend_amount' => 0,
                'health_insurance' => false,
                'annual_leave_days' => 0,
                'notice_period_days' => 3,
                'evaluation_required' => true,
                'certificate_provided' => true,
            ],
            'consultancy' => [
                'hourly_rate' => true,
                'health_insurance' => false,
                'annual_leave_days' => 0,
                'notice_period_days' => 15,
                'invoice_required' => true,
            ],
            default => []
        };

        return array_merge($commonTerms, $typeSpecificTerms);
    }

    /**
     * Scope to filter by contract type.
     */
    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active contracts.
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get contracts expiring soon.
     */
    public function scopeExpiringSoon(Builder $query, $days = 30)
    {
        return $query->active()
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now()->toDateString())
            ->where('end_date', '<=', now()->addDays($days)->toDateString());
    }

    /**
     * Scope to get contracts expiring critically.
     */
    public function scopeExpiringCritically(Builder $query, $days = 15)
    {
        return $query->active()
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now()->toDateString())
            ->where('end_date', '<=', now()->addDays($days)->toDateString());
    }

    /**
     * Scope to get contracts expiring urgently.
     */
    public function scopeExpiringUrgently(Builder $query, $days = 7)
    {
        return $query->active()
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now()->toDateString())
            ->where('end_date', '<=', now()->addDays($days)->toDateString());
    }

    /**
     * Scope to get expired contracts.
     */
    public function scopeExpired(Builder $query)
    {
        return $query->active()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString());
    }

    /**
     * Scope to get renewable contracts.
     */
    public function scopeRenewable(Builder $query)
    {
        return $query->whereIn('type', ['fixed_term', 'probation']);
    }

    /**
     * Scope to include employee information.
     */
    public function scopeWithEmployee(Builder $query)
    {
        return $query->with(['employee.department', 'employee.position']);
    }

    /**
     * Scope to order by expiry date.
     */
    public function scopeOrderByExpiry(Builder $query, $direction = 'asc')
    {
        return $query->orderByRaw("CASE WHEN end_date IS NULL THEN 1 ELSE 0 END, end_date {$direction}");
    }

    /**
     * Automatically expire contracts that have passed their end date.
     */
    public static function expireOverdueContracts()
    {
        $expiredCount = static::active()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);

        if ($expiredCount > 0) {
            activity('contract')
                ->log("Automatically expired {$expiredCount} contracts");
        }

        return $expiredCount;
    }

    /**
     * Get contracts requiring attention.
     */
    public static function getContractsRequiringAttention()
    {
        return [
            'expiring_urgently' => static::expiringUrgently()->withEmployee()->get(),
            'expiring_critically' => static::expiringCritically()->withEmployee()->get(),
            'expiring_soon' => static::expiringSoon()->withEmployee()->get(),
            'expired' => static::expired()->withEmployee()->limit(10)->get(),
        ];
    }

    /**
     * Renew the contract.
     */
    public function renew(array $data)
    {
        if (!$this->isRenewable() || !$this->isActive()) {
            return false;
        }

        return static::create([
            'employee_id' => $this->employee_id,
            'type' => $data['type'] ?? $this->type,
            'start_date' => $data['start_date'] ?? $this->end_date->addDay(),
            'end_date' => $data['end_date'] ?? null,
            'terms_json' => $data['terms_json'] ?? $this->terms_json,
            'status' => 'active',
        ]);
    }

    /**
     * Terminate the contract.
     */
    public function terminate($reason = null)
    {
        $this->update(['status' => 'terminated']);

        activity('contract')
            ->performedOn($this)
            ->withProperties(['reason' => $reason])
            ->log('Contract terminated');

        return true;
    }

    /**
     * Activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'start_date', 'end_date', 'status', 'terms_json'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Contract {$eventName}")
            ->useLogName('contract');
    }

    /**
     * Get contract statistics.
     */
    public function getStats()
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee?->display_name,
            'employee_code' => $this->employee?->code,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'duration_months' => $this->duration_in_months,
            'days_until_expiry' => $this->days_until_expiry,
            'expiry_urgency_level' => $this->expiry_urgency_level,
            'requires_end_date' => $this->requiresEndDate(),
            'is_renewable' => $this->isRenewable(),
            'is_expiring_soon' => $this->isExpiringSoon(),
            'is_expiring_critically' => $this->isExpiringCritically(),
            'is_expiring_urgently' => $this->isExpiringUrgently(),
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($contract) {
            // Set default terms if not provided
            if (empty($contract->terms_json)) {
                $contract->terms_json = $contract->getDefaultTerms();
            }
        });

        static::updating(function ($contract) {
            // Auto-expire if end_date is reached
            if ($contract->isDirty('end_date') && 
                $contract->end_date && 
                $contract->end_date < now()->toDateString() &&
                $contract->status === 'active') {
                $contract->status = 'expired';
            }
        });
    }
}