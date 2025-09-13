<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ContractExpiryNotification extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'contract_id',
        'notification_type',
        'notification_date',
        'recipients',
        'status',
        'message',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'notification_date' => 'date',
        'recipients' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Notification types constants.
     */
    public const TYPES = [
        'urgent' => 'Urgent (≤7 days)',
        'critical' => 'Critical (≤15 days)',
        'soon' => 'Soon (≤30 days)',
    ];

    /**
     * Status constants.
     */
    public const STATUSES = [
        'sent' => 'Sent',
        'failed' => 'Failed',
        'pending' => 'Pending',
    ];

    /**
     * Get the contract that owns the notification.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the localized type name.
     */
    public function getTypeNameAttribute()
    {
        return __('hrms.notifications.types.' . $this->notification_type, self::TYPES[$this->notification_type] ?? $this->notification_type);
    }

    /**
     * Get the localized status name.
     */
    public function getStatusNameAttribute()
    {
        return __('hrms.notifications.statuses.' . $this->status, self::STATUSES[$this->status] ?? $this->status);
    }

    /**
     * Check if notification was sent successfully.
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if notification failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if notification is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark notification as sent.
     */
    public function markAsSent()
    {
        return $this->update(['status' => 'sent']);
    }

    /**
     * Mark notification as failed.
     */
    public function markAsFailed($reason = null)
    {
        $metadata = $this->metadata ?? [];
        if ($reason) {
            $metadata['failure_reason'] = $reason;
        }
        
        return $this->update([
            'status' => 'failed',
            'metadata' => $metadata
        ]);
    }

    /**
     * Create a notification record.
     */
    public static function createNotification(Contract $contract, string $type, array $recipients, string $message, array $metadata = [])
    {
        return static::create([
            'contract_id' => $contract->id,
            'notification_type' => $type,
            'notification_date' => now()->toDateString(),
            'recipients' => $recipients,
            'status' => 'pending',
            'message' => $message,
            'metadata' => array_merge([
                'employee_name' => $contract->employee?->display_name,
                'employee_code' => $contract->employee?->code,
                'contract_type' => $contract->type,
                'end_date' => $contract->end_date?->toDateString(),
                'days_until_expiry' => $contract->days_until_expiry,
            ], $metadata),
        ]);
    }

    /**
     * Get recent notifications.
     */
    public static function getRecentNotifications($limit = 10)
    {
        return static::with(['contract.employee'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notifications by contract.
     */
    public static function getByContract(Contract $contract)
    {
        return static::where('contract_id', $contract->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get failed notifications.
     */
    public static function getFailedNotifications()
    {
        return static::with(['contract.employee'])
            ->where('status', 'failed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if notification already exists for contract/type/date.
     */
    public static function exists(Contract $contract, string $type, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return static::where('contract_id', $contract->id)
            ->where('notification_type', $type)
            ->where('notification_date', $date)
            ->exists();
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['notification_type', 'status', 'recipients'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Contract notification {$eventName}")
            ->useLogName('notification');
    }
}
