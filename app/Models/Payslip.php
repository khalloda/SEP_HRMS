<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payslip extends Model
{
    use LogsActivity;

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'salary_structure_id',
        'employee_code',
        'employee_name',
        'employee_arabic_name',
        'department_name',
        'position_name',
        'pay_period_start',
        'pay_period_end',
        'pay_date',
        'currency',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'basic_salary',
        'status',
        'generated_at',
        'pdf_generated_at',
        'pdf_path',
        'notes',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'pay_date' => 'date',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'generated_at' => 'datetime',
        'pdf_generated_at' => 'datetime',
    ];

    /**
     * Payslip statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_CALCULATED = 'calculated';
    const STATUS_GENERATED = 'generated';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';

    const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_CALCULATED => 'Calculated',
        self::STATUS_GENERATED => 'Generated',
        self::STATUS_SENT => 'Sent',
        self::STATUS_VIEWED => 'Viewed',
    ];

    /**
     * Get the payroll run that owns this payslip.
     */
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Get the employee that owns this payslip.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the salary structure used for this payslip.
     */
    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    /**
     * Get the payslip lines for this payslip.
     */
    public function payslipLines(): HasMany
    {
        return $this->hasMany(PayslipLine::class);
    }

    /**
     * Get earnings lines only.
     */
    public function earningsLines(): HasMany
    {
        return $this->hasMany(PayslipLine::class)->where('component_type', 'earning');
    }

    /**
     * Get deductions lines only.
     */
    public function deductionsLines(): HasMany
    {
        return $this->hasMany(PayslipLine::class)->where('component_type', 'deduction');
    }

    /**
     * Get info lines only.
     */
    public function infoLines(): HasMany
    {
        return $this->hasMany(PayslipLine::class)->where('component_type', 'info');
    }

    /**
     * Scope: Filter by employee.
     */
    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope: Filter by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
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
        return $query->orderBy('pay_period_end', 'desc')
                     ->orderBy('employee_name', 'asc');
    }

    /**
     * Scope: Only generated payslips.
     */
    public function scopeGenerated(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_GENERATED);
    }

    /**
     * Check if the payslip is in draft status.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if the payslip is calculated.
     */
    public function isCalculated(): bool
    {
        return $this->status === self::STATUS_CALCULATED;
    }

    /**
     * Check if the payslip is generated.
     */
    public function isGenerated(): bool
    {
        return $this->status === self::STATUS_GENERATED;
    }

    /**
     * Check if the payslip has been sent.
     */
    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    /**
     * Check if the payslip has been viewed.
     */
    public function isViewed(): bool
    {
        return $this->status === self::STATUS_VIEWED;
    }

    /**
     * Check if PDF is available.
     */
    public function hasPdf(): bool
    {
        return !empty($this->pdf_path) && \Storage::disk('private')->exists($this->pdf_path);
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
            self::STATUS_CALCULATED => 'bg-primary',
            self::STATUS_GENERATED => 'bg-success',
            self::STATUS_SENT => 'bg-info',
            self::STATUS_VIEWED => 'bg-warning',
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
     * Get the display name for the employee.
     */
    public function getEmployeeDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'ar' && !empty($this->employee_arabic_name)) {
            return $this->employee_arabic_name;
        }
        return $this->employee_name;
    }

    /**
     * Get total earnings from lines.
     */
    public function getTotalEarningsAttribute(): float
    {
        return $this->earningsLines()->sum('amount');
    }

    /**
     * Calculate gross pay from earnings.
     */
    public function calculateGrossPay(): float
    {
        return $this->earningsLines()
            ->where('include_in_gross', true)
            ->sum('amount');
    }

    /**
     * Calculate total deductions.
     */
    public function calculateTotalDeductions(): float
    {
        return $this->deductionsLines()->sum('amount');
    }

    /**
     * Calculate net pay.
     */
    public function calculateNetPay(): float
    {
        return $this->calculateGrossPay() - $this->calculateTotalDeductions();
    }

    /**
     * Update calculated totals.
     */
    public function updateTotals(): void
    {
        $this->update([
            'gross_pay' => $this->calculateGrossPay(),
            'total_deductions' => $this->calculateTotalDeductions(),
            'net_pay' => $this->calculateNetPay(),
        ]);
    }

    /**
     * Mark as generated.
     */
    public function markAsGenerated(): void
    {
        $this->update([
            'status' => self::STATUS_GENERATED,
            'generated_at' => now(),
        ]);

        activity('payslip')
            ->performedOn($this)
            ->log('Payslip generated');
    }

    /**
     * Mark PDF as generated.
     */
    public function markPdfGenerated(string $path): void
    {
        $this->update([
            'pdf_path' => $path,
            'pdf_generated_at' => now(),
        ]);

        activity('payslip')
            ->performedOn($this)
            ->withProperties(['pdf_path' => $path])
            ->log('Payslip PDF generated');
    }

    /**
     * Mark as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => self::STATUS_SENT,
        ]);

        activity('payslip')
            ->performedOn($this)
            ->log('Payslip sent to employee');
    }

    /**
     * Mark as viewed by employee.
     */
    public function markAsViewed(): void
    {
        if ($this->status !== self::STATUS_VIEWED) {
            $this->update([
                'status' => self::STATUS_VIEWED,
            ]);

            activity('payslip')
                ->performedOn($this)
                ->log('Payslip viewed by employee');
        }
    }

    /**
     * Get the component value for a specific component code.
     */
    public function getComponentValue(string $componentCode): ?float
    {
        $line = $this->payslipLines()
            ->where('component_code', $componentCode)
            ->first();

        return $line ? $line->amount : null;
    }

    /**
     * Check if user can view this payslip based on role and employee.
     */
    public function canViewBy(User $user): bool
    {
        // HR Admin and Accounting Manager can view all payslips
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // HR Coordinator can view payslips but not net/gross amounts
        if ($user->hasRole('HR_Coordinator')) {
            return true;
        }

        // Accountant can view payslips but not net/gross amounts
        if ($user->hasRole('Accountant')) {
            return true;
        }

        // Employees can view their own payslips if salary visibility is enabled
        if ($user->employee &&
            $user->employee->id === $this->employee_id &&
            $user->employee->salary_visibility_flag) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can view net/gross amounts on this payslip.
     */
    public function canViewNetGrossBy(User $user): bool
    {
        // Only HR Admin and Accounting Manager can view net/gross
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // Employee can view their own if visibility flag is enabled
        if ($user->employee &&
            $user->employee->id === $this->employee_id &&
            $user->employee->salary_visibility_flag) {
            return true;
        }

        return false;
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'employee_code', 'employee_name', 'pay_period_start', 'pay_period_end',
                'gross_pay', 'total_deductions', 'net_pay', 'status'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Payslip {$eventName}")
            ->useLogName('payslip');
    }
}