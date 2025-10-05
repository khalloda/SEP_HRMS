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
        'component_name',
        'component_type',
        'calculation_mode',
        'formula_used',
        'formula',
        'rate',
        'amount',
        'priority_order',
        'priority',
        'include_in_gross',
        'taxable',
        'is_taxable',
        'calculation_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'priority_order' => 'integer',
        'priority' => 'integer',
        'include_in_gross' => 'boolean',
        'taxable' => 'boolean',
        'is_taxable' => 'boolean',
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
        return $query->where(function (Builder $q) {
            $q->where('taxable', true)
              ->orWhere('is_taxable', true);
        });
    }

    /**
     * Scope: Order by priority.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw('COALESCE(priority_order, priority, 0)');
    }

    /**
     * Get the component display name based on locale.
     */
    public function getComponentDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar' && ! empty($this->component_name_ar)) {
            return $this->component_name_ar;
        }

        if (! empty($this->component_name_en)) {
            return $this->component_name_en;
        }

        return $this->component_name ?? '';
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Normalise priority order retrieval.
     */
    public function getPriorityOrderAttribute($value): int
    {
        if (! is_null($value)) {
            return (int) $value;
        }

        return (int) ($this->attributes['priority'] ?? 0);
    }

    /**
     * Ensure priority order changes mirror legacy column for backwards compatibility.
     */
    public function setPriorityOrderAttribute($value): void
    {
        $this->attributes['priority_order'] = $value;
        $this->attributes['priority'] = $value;
    }

    /**
     * Taxable attribute with legacy fallback.
     */
    public function getTaxableAttribute($value): bool
    {
        if (! is_null($value)) {
            return (bool) $value;
        }

        return (bool) ($this->attributes['is_taxable'] ?? false);
    }

    public function setTaxableAttribute($value): void
    {
        $boolValue = (bool) $value;
        $this->attributes['taxable'] = $boolValue;
        $this->attributes['is_taxable'] = $boolValue;
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
        if (! is_null($this->include_in_gross)) {
            return (bool) $this->include_in_gross;
        }

        return $this->isEarning();
    }

    /**
     * Check if this component is taxable.
     */
    public function isTaxable(): bool
    {
        return $this->taxable;
    }
}
