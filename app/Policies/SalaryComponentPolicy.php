<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SalaryComponent;

class SalaryComponentPolicy
{
    /**
     * Determine if the user can view any salary components.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager',
            'HR_Coordinator',
            'Accountant'
        ]);
    }

    /**
     * Determine if the user can view the salary component.
     */
    public function view(User $user, SalaryComponent $salaryComponent): bool
    {
        // Admin roles can view all components
        if ($user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager'])) {
            return true;
        }

        // HR coordinators can view all except Net/Gross components
        if ($user->hasRole('HR_Coordinator')) {
            return !in_array($salaryComponent->code, ['GROSS_SALARY', 'NET_SALARY']);
        }

        // Accountants can view all except Net/Gross components
        if ($user->hasRole('Accountant')) {
            return !in_array($salaryComponent->code, ['GROSS_SALARY', 'NET_SALARY']);
        }

        // Check component's role visibility restrictions
        return $salaryComponent->canViewForUser($user);
    }

    /**
     * Determine if the user can create salary components.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine if the user can update the salary component.
     */
    public function update(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine if the user can delete the salary component.
     */
    public function delete(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine if the user can restore the salary component.
     */
    public function restore(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine if the user can permanently delete the salary component.
     */
    public function forceDelete(User $user, SalaryComponent $salaryComponent): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }
}