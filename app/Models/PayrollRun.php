<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PayrollRun extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'pay_period_start',
        'pay_period_end',
        'pay_date',
        'status',
        'currency',
        'total_employees',
        'total_gross',
        'total_net',
        'total_deductions',
        'created_by',
        'locked_by',
        'locked_at',
        'posted_by',
        'posted_at',
        'approval_required',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'pay_date' => 'date',
        'locked_at' => 'datetime',
        'posted_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_gross' => 'decimal:2',
        'total_net' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_employees' => 'integer',
        'approval_required' => 'boolean',
    ];

    /**
     * Payroll run statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_CALCULATING = 'calculating';
    const STATUS_CALCULATED = 'calculated';
    const STATUS_LOCKED = 'locked';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_POSTED = 'posted';
    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_CALCULATING => 'Calculating',
        self::STATUS_CALCULATED => 'Calculated',
        self::STATUS_LOCKED => 'Locked',
        self::STATUS_PENDING_APPROVAL => 'Pending Approval',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_POSTED => 'Posted',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    /**
     * Get the user who created this payroll run.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who locked this payroll run.
     */
    public function locker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Get the user who posted this payroll run.
     */
    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Get the user who approved this payroll run.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the payslips for this payroll run.
     */
    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Only draft runs.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope: Only locked runs.
     */
    public function scopeLocked(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LOCKED);
    }

    /**
     * Scope: Only posted runs.
     */
    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_POSTED);
    }

    /**
     * Scope: Only runs needing approval.
     */
    public function scopePendingApproval(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL);
    }

    /**
     * Scope: Filter by pay period.
     */
    public function scopeByPayPeriod(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->where('pay_period_start', '>=', $start)
                     ->where('pay_period_end', '<=', $end);
    }

    /**
     * Scope: Filter by pay date range.
     */
    public function scopeByPayDate(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('pay_date', [$start, $end]);
    }

    /**
     * Scope: Order by most recent.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('pay_period_end', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Check if the payroll run is in draft status.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the payroll run is calculating.
     */
    public function isCalculating(): bool
    {
        return $this->status === self::STATUS_CALCULATING;
    }

    /**
     * Check if the payroll run is calculated.
     */
    public function isCalculated(): bool
    {
        return $this->status === self::STATUS_CALCULATED;
    }

    /**
     * Check if the payroll run is locked.
     */
    public function isLocked(): bool
    {
        return in_array($this->status, [
            self::STATUS_LOCKED,
            self::STATUS_PENDING_APPROVAL,
            self::STATUS_APPROVED,
            self::STATUS_POSTED
        ]);
    }

    /**
     * Check if the payroll run is pending approval.
     */
    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING_APPROVAL;
    }

    /**
     * Check if the payroll run is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the payroll run is posted.
     */
    public function isPosted(): bool
    {
        return $this->status === self::STATUS_POSTED;
    }

    /**
     * Check if the payroll run is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if the payroll run can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_CALCULATED]);
    }

    /**
     * Check if the payroll run can be calculated.
     */
    public function canBeCalculated(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the payroll run can be locked.
     */
    public function canBeLocked(): bool
    {
        return $this->status === self::STATUS_CALCULATED;
    }

    /**
     * Check if the payroll run can be unlocked.
     */
    public function canBeUnlocked(): bool
    {
        return $this->status === self::STATUS_LOCKED && !$this->approval_required;
    }

    /**
     * Check if the payroll run can be posted.
     */
    public function canBePosted(): bool
    {
        return in_array($this->status, [self::STATUS_LOCKED, self::STATUS_APPROVED]);
    }

    /**
     * Check if the payroll run can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return !in_array($this->status, [self::STATUS_POSTED, self::STATUS_CANCELLED]);
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'bg-secondary',
            self::STATUS_CALCULATING => 'bg-info',
            self::STATUS_CALCULATED => 'bg-primary',
            self::STATUS_LOCKED => 'bg-warning',
            self::STATUS_PENDING_APPROVAL => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_POSTED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get the pay period as a formatted string.
     */
    public function getPayPeriodAttribute(): string
    {
        return $this->pay_period_start->format('M j') . ' - ' . $this->pay_period_end->format('M j, Y');
    }

    /**
     * Get the pay period month-year.
     */
    public function getPayPeriodMonthYearAttribute(): string
    {
        return $this->pay_period_end->format('F Y');
    }

    /**
     * Calculate and update the totals from payslips.
     */
    public function updateTotals(): void
    {
        $totals = $this->payslips()
            ->selectRaw('
                COUNT(*) as employee_count,
                SUM(gross_pay) as total_gross,
                SUM(net_pay) as total_net,
                SUM(total_deductions) as total_deductions
            ')
            ->first();

        $this->update([
            'total_employees' => $totals->employee_count ?? 0,
            'total_gross' => $totals->total_gross ?? 0,
            'total_net' => $totals->total_net ?? 0,
            'total_deductions' => $totals->total_deductions ?? 0,
        ]);
    }

    /**
     * Lock the payroll run.
     */
    public function lock(User $user): bool
    {
        if (!$this->canBeLocked()) {
            return false;
        }

        $status = $this->approval_required ? self::STATUS_PENDING_APPROVAL : self::STATUS_LOCKED;

        $this->update([
            'status' => $status,
            'locked_by' => $user->id,
            'locked_at' => now(),
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->log('Payroll run locked');

        return true;
    }

    /**
     * Unlock the payroll run.
     */
    public function unlock(User $user): bool
    {
        if (!$this->canBeUnlocked()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CALCULATED,
            'locked_by' => null,
            'locked_at' => null,
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->log('Payroll run unlocked');

        return true;
    }

    /**
     * Approve the payroll run.
     */
    public function approve(User $user): bool
    {
        if (!$this->isPendingApproval()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->log('Payroll run approved');

        return true;
    }

    /**
     * Reject the payroll run (unlock it).
     */
    public function reject(User $user, string $reason = null): bool
    {
        if (!$this->isPendingApproval()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CALCULATED,
            'locked_by' => null,
            'locked_at' => null,
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['rejection_reason' => $reason])
            ->log('Payroll run rejected');

        return true;
    }

    /**
     * Post the payroll run.
     */
    public function post(User $user): bool
    {
        if (!$this->canBePosted()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_POSTED,
            'posted_by' => $user->id,
            'posted_at' => now(),
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->log('Payroll run posted');

        return true;
    }

    /**
     * Cancel the payroll run.
     */
    public function cancel(User $user, string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);

        activity('payroll_run')
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['cancellation_reason' => $reason])
            ->log('Payroll run cancelled');

        return true;
    }

    /**
     * Generate a title for the payroll run based on pay period.
     */
    public static function generateTitle(Carbon $periodStart, Carbon $periodEnd): string
    {
        if ($periodStart->year === $periodEnd->year) {
            if ($periodStart->month === $periodEnd->month) {
                return "Payroll - {$periodEnd->format('F Y')}";
            } else {
                return "Payroll - {$periodStart->format('M')} - {$periodEnd->format('M Y')}";
            }
        } else {
            return "Payroll - {$periodStart->format('M Y')} - {$periodEnd->format('M Y')}";
        }
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title', 'status', 'pay_period_start', 'pay_period_end', 'pay_date',
                'total_employees', 'total_gross', 'total_net', 'approval_required'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Payroll run {$eventName}")
            ->useLogName('payroll_run');
    }
}