<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryStructureComponent extends Model
{
    protected $fillable = [
        'structure_id',
        'component_id',
        'value_numeric',
        'formula_expr',
        'depends_on',
        'priority_order',
    ];

    protected $casts = [
        'value_numeric' => 'decimal:2',
        'depends_on' => 'array',
        'priority_order' => 'integer',
    ];

    /**
     * Get the salary structure.
     */
    public function structure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class, 'structure_id');
    }

    /**
     * Get the salary component.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class, 'component_id');
    }

    /**
     * Get the calculated value based on component type.
     */
    public function getCalculatedValueAttribute(): ?float
    {
        if (!$this->component) {
            return null;
        }

        switch ($this->component->calc_mode) {
            case 'fixed':
                return $this->value_numeric;
                
            case 'formula':
                // Formula evaluation would be implemented here
                return null;
                
            case 'variable_net_based':
                // Variable calculation would be implemented here
                return null;
                
            default:
                return null;
        }
    }

    /**
     * Get the display value (formatted).
     */
    public function getDisplayValueAttribute(): string
    {
        switch ($this->component->calc_mode) {
            case 'fixed':
                return number_format($this->value_numeric, 2);
                
            case 'formula':
                return $this->formula_expr ?: 'No formula';
                
            case 'variable_net_based':
                return 'Variable';
                
            default:
                return 'N/A';
        }
    }

    /**
     * Check if this component has dependencies.
     */
    public function hasDependencies(): bool
    {
        return !empty($this->depends_on);
    }

    /**
     * Get the dependency component codes.
     */
    public function getDependencyCodesAttribute(): array
    {
        return $this->depends_on ?: [];
    }
}