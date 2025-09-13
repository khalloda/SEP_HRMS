<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SalaryStructure;
use App\Models\Employee;

class SalaryStructurePolicy
{
    /**
     * Determine whether the user can view any salary structures.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'payroll.view',
            'employees.view'
        ]);
    }

    /**
     * Determine whether the user can view the salary structure.
     */
    public function view(User $user, SalaryStructure $salaryStructure): bool
    {
        // HR Admin and Accounting Manager can view all salary structures
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // HR Coordinator can view structures but not net/gross calculations
        if ($user->hasRole('HR_Coordinator')) {
            return true;
        }

        // Accountant can view structures but not net/gross calculations
        if ($user->hasRole('Accountant')) {
            return true;
        }

        // Employees can view their own salary structure if visibility flag is enabled
        if ($user->employee &&
            $user->employee->id === $salaryStructure->employee_id &&
            $user->employee->salary_visibility_flag) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create salary structures.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can update the salary structure.
     */
    public function update(User $user, SalaryStructure $salaryStructure): bool
    {
        // Only HR Admin and Accounting Manager can update salary structures
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can delete the salary structure.
     */
    public function delete(User $user, SalaryStructure $salaryStructure): bool
    {
        // Only HR Admin can delete/terminate salary structures
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can view net/gross salary calculations.
     */
    public function viewNetGross(User $user, SalaryStructure $salaryStructure): bool
    {
        // Only HR Admin and Accounting Manager can view net/gross
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // Employee can view their own if visibility flag is enabled
        if ($user->employee &&
            $user->employee->id === $salaryStructure->employee_id &&
            $user->employee->salary_visibility_flag) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can clone salary structures.
     */
    public function clone(User $user, SalaryStructure $salaryStructure): bool
    {
        return $this->create($user);
    }

    /**
     * Determine whether the user can terminate salary structures.
     */
    public function terminate(User $user, SalaryStructure $salaryStructure): bool
    {
        return $this->delete($user, $salaryStructure);
    }

    /**
     * Determine whether the user can export salary data.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }
}