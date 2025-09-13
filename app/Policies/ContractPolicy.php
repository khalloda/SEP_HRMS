<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractPolicy
{
    /**
     * Determine whether the user can view any contracts.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator',
            'Accounting_Manager',
            'Accountant',
            'IT_Admin'
        ]);
    }

    /**
     * Determine whether the user can view the contract.
     */
    public function view(User $user, Contract $contract): bool
    {
        // HR Admin Manager and IT Admin can view all contracts
        if ($user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            return true;
        }

        // HR Coordinator can view all contracts
        if ($user->hasRole('HR_Coordinator')) {
            return true;
        }

        // Accounting can view all contracts
        if ($user->hasAnyRole(['Accounting_Manager', 'Accountant'])) {
            return true;
        }

        // Employees can only view their own contracts
        return $user->employee_id && $contract->employee_id === $user->employee_id;
    }

    /**
     * Determine whether the user can create contracts.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can update the contract.
     */
    public function update(User $user, Contract $contract): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can delete the contract.
     */
    public function delete(User $user, Contract $contract): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can export contracts.
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
     * Determine whether the user can perform bulk operations.
     */
    public function bulkUpdate(User $user): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can terminate contracts.
     */
    public function terminate(User $user, Contract $contract): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can renew contracts.
     */
    public function renew(User $user, Contract $contract): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can restore the contract.
     */
    public function restore(User $user, Contract $contract): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can permanently delete the contract.
     */
    public function forceDelete(User $user, Contract $contract): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }
}