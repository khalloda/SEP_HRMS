<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PayslipLine extends Model
{
    protected $fillable = [
        'payslip_id',
        'salary_component_id',
        'component_code',
        'component_name_en',
        'component_name_ar',
        'component_type',
        'calculation_mode',
        'amount',
        'formula_used',
        'priority_order',
        'include_in_gross',
        'taxable',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'priority_order' => 'integer',
        'include_in_gross' => 'boolean',
        'taxable' => 'boolean',
    ];

    /**
     * Get the payslip that owns this line.
     */
    public function payslip(): BelongsTo
    {
        return $this->belongsTo(Payslip::class);
    }

    /**
     * Get the salary component.
     */
    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }

    /**
     * Scope: Filter by component type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('component_type', $type);
    }

    /**
     * Scope: Only earnings.
     */
    public function scopeEarnings(Builder $query): Builder
    {
        return $query->where('component_type', 'earning');
    }

    /**
     * Scope: Only deductions.
     */
    public function scopeDeductions(Builder $query): Builder
    {
        return $query->where('component_type', 'deduction');
    }

    /**
     * Scope: Only info components.
     */
    public function scopeInfoOnly(Builder $query): Builder
    {
        return $query->where('component_type', 'info');
    }

    /**
     * Scope: Only taxable components.
     */
    public function scopeTaxable(Builder $query): Builder
    {
        return $query->where('taxable', true);
    }

    /**
     * Scope: Order by priority.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('priority_order');
    }

    /**
     * Get the component display name based on locale.
     */
    public function getComponentDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->component_name_ar : $this->component_name_en;
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Check if this is an earning component.
     */
    public function isEarning(): bool
    {
        return $this->component_type === 'earning';
    }

    /**
     * Check if this is a deduction component.
     */
    public function isDeduction(): bool
    {
        return $this->component_type === 'deduction';
    }

    /**
     * Check if this is an info component.
     */
    public function isInfo(): bool
    {
        return $this->component_type === 'info';
    }

    /**
     * Check if this component is included in gross calculation.
     */
    public function isIncludedInGross(): bool
    {
        return $this->include_in_gross;
    }

    /**
     * Check if this component is taxable.
     */
    public function isTaxable(): bool
    {
        return $this->taxable;
    }
}