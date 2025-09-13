<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Services\AuditTrailService;

class SalaryStructure extends Model
{
    use LogsActivity;

    protected $fillable = [
        'employee_id',
        'currency',
        'effective_from',
        'effective_to',
        'notes',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    protected $dates = [
        'effective_from',
        'effective_to',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::created(function ($structure) {
            // Log salary structure creation
            app(AuditTrailService::class)->logSalaryStructureChange(
                $structure,
                $structure->getAttributes(),
                'created'
            );
        });

        static::updated(function ($structure) {
            // Log salary structure updates
            if ($structure->wasChanged()) {
                $changes = [];
                foreach ($structure->getChanges() as $key => $value) {
                    $changes[$key] = [
                        'old' => $structure->getOriginal($key),
                        'new' => $value
                    ];
                }

                app(AuditTrailService::class)->logSalaryStructureChange($structure, $changes, 'updated');
            }
        });

        static::deleting(function ($structure) {
            // Log salary structure deletion
            app(AuditTrailService::class)->logSalaryStructureChange(
                $structure,
                ['structure_id' => $structure->id, 'employee_id' => $structure->employee_id],
                'deleted'
            );
        });
    }

    /**
     * Get the employee that owns this salary structure.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the salary components for this structure.
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(SalaryComponent::class, 'salary_structure_components', 'structure_id', 'component_id')
            ->withPivot(['value_numeric', 'formula_expr', 'depends_on', 'priority_order'])
            ->withTimestamps()
            ->orderByPivot('priority_order');
    }

    /**
     * Get the salary structure components (pivot records).
     */
    public function structureComponents()
    {
        return $this->hasMany(SalaryStructureComponent::class, 'structure_id');
    }

    /**
     * Scope: Filter by employee.
     */
    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope: Only active structures.
     */
    public function scopeActive(Builder $query, Carbon $date = null): Builder
    {
        $date = $date ?: now();
        
        return $query->where('effective_from', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', $date);
            });
    }

    /**
     * Scope: Only current structures (active as of today).
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $this->scopeActive($query, now());
    }

    /**
     * Scope: Only expired structures.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('effective_to')
            ->where('effective_to', '<', now());
    }

    /**
     * Scope: Only future structures.
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('effective_from', '>', now());
    }

    /**
     * Check if this structure is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        $now = now();
        
        return $this->effective_from <= $now &&
               (is_null($this->effective_to) || $this->effective_to >= $now);
    }

    /**
     * Check if this structure is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return !is_null($this->effective_to) && $this->effective_to < now();
    }

    /**
     * Check if this structure is future.
     */
    public function getIsFutureAttribute(): bool
    {
        return $this->effective_from > now();
    }

    /**
     * Get the status of this structure.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_active) {
            return 'active';
        } elseif ($this->is_expired) {
            return 'expired';
        } else {
            return 'future';
        }
    }

    /**
     * Get the effective period as a readable string.
     */
    public function getEffectivePeriodAttribute(): string
    {
        $from = $this->effective_from->format('Y-m-d');
        $to = $this->effective_to ? $this->effective_to->format('Y-m-d') : 'Ongoing';
        
        return "{$from} - {$to}";
    }

    /**
     * Get earnings components only.
     */
    public function getEarningsAttribute()
    {
        return $this->components()->where('comp_type', 'earning')->get();
    }

    /**
     * Get deductions components only.
     */
    public function getDeductionsAttribute()
    {
        return $this->components()->where('comp_type', 'deduction')->get();
    }

    /**
     * Get info components only.
     */
    public function getInfoComponentsAttribute()
    {
        return $this->components()->where('comp_type', 'info')->get();
    }

    /**
     * Calculate total earnings based on current structure.
     */
    public function calculateTotalEarnings(): float
    {
        $total = 0;
        
        foreach ($this->earnings as $component) {
            $value = $this->calculateComponentValue($component);
            if ($value !== null) {
                $total += $value;
            }
        }
        
        return $total;
    }

    /**
     * Calculate total deductions based on current structure.
     */
    public function calculateTotalDeductions(): float
    {
        $total = 0;
        
        foreach ($this->deductions as $component) {
            $value = $this->calculateComponentValue($component);
            if ($value !== null) {
                $total += $value;
            }
        }
        
        return $total;
    }

    /**
     * Calculate gross salary.
     */
    public function calculateGrossSalary(): float
    {
        return $this->calculateTotalEarnings();
    }

    /**
     * Calculate net salary.
     */
    public function calculateNetSalary(): float
    {
        return $this->calculateGrossSalary() - $this->calculateTotalDeductions();
    }

    /**
     * Calculate the value of a specific component.
     */
    public function calculateComponentValue(SalaryComponent $component): ?float
    {
        $pivot = $component->pivot;
        
        switch ($component->calc_mode) {
            case 'fixed':
                return $pivot->value_numeric;
                
            case 'formula':
                // For now, return null - formula evaluation would be implemented later
                return null;
                
            case 'variable_net_based':
                // For now, return null - variable calculation would be implemented later
                return null;
                
            default:
                return null;
        }
    }

    /**
     * Get components visible to the current user.
     */
    public function getVisibleComponentsAttribute()
    {
        $user = auth()->user();
        
        return $this->components->filter(function ($component) use ($user) {
            return $component->canViewForUser($user);
        });
    }

    /**
     * Clone this structure for a new effective period.
     */
    public function cloneForNewPeriod(Carbon $newEffectiveFrom, Carbon $newEffectiveTo = null): SalaryStructure
    {
        // End the current structure
        $this->update(['effective_to' => $newEffectiveFrom->subDay()]);
        
        // Create new structure
        $newStructure = $this->replicate();
        $newStructure->effective_from = $newEffectiveFrom;
        $newStructure->effective_to = $newEffectiveTo;
        $newStructure->save();
        
        // Copy components
        foreach ($this->structureComponents as $component) {
            $newComponent = $component->replicate();
            $newComponent->structure_id = $newStructure->id;
            $newComponent->save();
        }
        
        return $newStructure;
    }

    /**
     * Get activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_id', 'currency', 'effective_from', 'effective_to', 'notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Salary structure {$eventName}");
    }
}