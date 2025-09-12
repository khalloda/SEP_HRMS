<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any employees.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator', 
            'Accounting_Manager',
            'Accountant'
        ]);
    }

    /**
     * Determine whether the user can view the employee.
     */
    public function view(User $user, Employee $employee): bool
    {
        return $employee->canBeViewedBy($user);
    }

    /**
     * Determine whether the user can create employees.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can update the employee.
     */
    public function update(User $user, Employee $employee): bool
    {
        // HR roles can update all employees
        if ($user->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator'])) {
            return true;
        }

        // Users can update their own employee record (limited fields)
        if ($user->employee && $user->employee->id === $employee->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the employee.
     */
    public function delete(User $user, Employee $employee): bool
    {
        // Only HR Admin Manager can delete employees
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can terminate employees.
     */
    public function terminate(User $user, Employee $employee): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can reactivate terminated employees.
     */
    public function reactivate(User $user, Employee $employee): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can export employee data.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can view employee statistics.
     */
    public function statistics(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can view salary information.
     */
    public function viewSalary(User $user, Employee $employee): bool
    {
        return $employee->canViewSalaryBy($user);
    }
}