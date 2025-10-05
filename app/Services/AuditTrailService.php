<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrailService
{
    public function logContractChange(Contract $contract, array $changes, string $action = 'updated')
    {
        $properties = [
            'changes' => $changes,
            'contract_type' => $contract->type,
            'employee_id' => $contract->employee_id,
            'previous_values' => $contract->getOriginal(),
            'new_values' => $contract->getAttributes()
        ];

        if ($action === 'renewed') {
            $properties['renewal_details'] = [
                'previous_end_date' => $contract->getOriginal('end_date'),
                'new_end_date' => $contract->end_date,
                'renewal_reason' => $changes['renewal_reason'] ?? null
            ];
        }

        if ($action === 'terminated') {
            $properties['termination_details'] = [
                'termination_date' => $contract->end_date,
                'termination_reason' => $changes['termination_reason'] ?? null,
                'notice_period' => $changes['notice_period'] ?? null
            ];
        }

        activity('contract')
            ->performedOn($contract)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log("Contract {$action}");
    }

    public function logSalaryStructureChange(SalaryStructure $structure, array $changes, string $action = 'updated')
    {
        $properties = [
            'changes' => $changes,
            'employee_id' => $structure->employee_id,
            'structure_name' => $structure->name,
            'effective_from' => $structure->effective_from,
            'previous_values' => $structure->getOriginal(),
            'new_values' => $structure->getAttributes()
        ];

        if (isset($changes['components'])) {
            $properties['component_changes'] = $changes['components'];
        }

        if ($action === 'activated') {
            $properties['activation_details'] = [
                'previous_active_structure' => $changes['previous_active'] ?? null,
                'activation_reason' => $changes['activation_reason'] ?? null
            ];
        }

        activity('salary_structure')
            ->performedOn($structure)
            ->causedBy(Auth::user())
            ->withProperties($properties)
            ->log("Salary structure {$action}");
    }

    public function logEmployeeCriticalChange(Employee $employee, array $changes, string $action = 'updated')
    {
        $criticalFields = [
            'salary_basic',
            'salary_gross',
            'national_id',
            'position_id',
            'department_id',
            'manager_id',
            'employment_status',
            'hire_date',
            'termination_date'
        ];

        $criticalChanges = array_intersect_key($changes, array_flip($criticalFields));

        if (!empty($criticalChanges)) {
            $properties = [
                'critical_changes' => $criticalChanges,
                'employee_code' => $employee->code,
                'previous_values' => array_intersect_key($employee->getOriginal(), $criticalChanges),
                'new_values' => array_intersect_key($employee->getAttributes(), $criticalChanges)
            ];

            activity('employee_critical')
                ->performedOn($employee)
                ->causedBy(Auth::user())
                ->withProperties($properties)
                ->log("Employee critical data {$action}");
        }
    }

    public function getAuditTrailForModel(Model $model, int $limit = 50)
    {
        return activity()
            ->forSubject($model)
            ->with(['causer'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentAuditTrail(int $days = 30, int $limit = 100)
    {
        return activity()
            ->where('created_at', '>=', now()->subDays($days))
            ->whereIn('log_name', ['contract', 'salary_structure', 'employee_critical'])
            ->with(['causer', 'subject'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getAuditTrailByUser($userId, int $limit = 50)
    {
        return activity()
            ->causedBy($userId)
            ->whereIn('log_name', ['contract', 'salary_structure', 'employee_critical'])
            ->with(['subject'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getContractAuditStats()
    {
        $stats = [
            'total_contract_changes' => activity('contract')->count(),
            'recent_changes' => activity('contract')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'by_action' => [],
            'by_user' => []
        ];

        $actionStats = activity('contract')
            ->selectRaw('description, COUNT(*) as count')
            ->groupBy('description')
            ->get()
            ->pluck('count', 'description')
            ->toArray();

        $stats['by_action'] = $actionStats;

        $userStats = activity('contract')
            ->with('causer')
            ->get()
            ->groupBy('causer.name')
            ->map->count()
            ->toArray();

        $stats['by_user'] = $userStats;

        return $stats;
    }

    public function getSalaryAuditStats()
    {
        $stats = [
            'total_salary_changes' => activity('salary_structure')->count(),
            'recent_changes' => activity('salary_structure')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'critical_employee_changes' => activity('employee_critical')->count(),
            'by_action' => []
        ];

        $actionStats = activity()
            ->whereIn('log_name', ['salary_structure', 'employee_critical'])
            ->selectRaw('description, COUNT(*) as count')
            ->groupBy('description')
            ->get()
            ->pluck('count', 'description')
            ->toArray();

        $stats['by_action'] = $actionStats;

        return $stats;
    }

    public function exportAuditTrail($startDate = null, $endDate = null, $logNames = null)
    {
        $query = activity();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        if ($logNames) {
            $query->whereIn('log_name', $logNames);
        }

        return $query->with(['causer', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($activity) {
                return [
                    'timestamp' => $activity->created_at->format('Y-m-d H:i:s'),
                    'user' => $activity->causer?->name ?? 'System',
                    'action' => $activity->description,
                    'subject_type' => class_basename($activity->subject_type),
                    'subject_id' => $activity->subject_id,
                    'changes' => json_encode($activity->properties['changes'] ?? []),
                    'log_name' => $activity->log_name
                ];
            });
    }
}