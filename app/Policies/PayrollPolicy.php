<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PayrollRun;
use App\Models\Payslip;

class PayrollPolicy
{
    /**
     * Determine whether the user can view any payroll runs.
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
     * Determine whether the user can view the payroll run.
     */
    public function view(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager',
            'HR_Coordinator',
            'Accountant'
        ]);
    }

    /**
     * Determine whether the user can create payroll runs.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can update the payroll run.
     */
    public function update(User $user, PayrollRun $payrollRun): bool
    {
        // Only HR Admin and Accounting Manager can update payroll runs
        // And only if they're in draft or calculated status
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]) && $payrollRun->canBeEdited();
    }

    /**
     * Determine whether the user can delete the payroll run.
     */
    public function delete(User $user, PayrollRun $payrollRun): bool
    {
        // Only HR Admin can cancel payroll runs
        return $user->hasRole('HR_Admin_Manager') && $payrollRun->canBeCancelled();
    }

    /**
     * Determine whether the user can calculate payroll.
     */
    public function calculate(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]) && $payrollRun->canBeCalculated();
    }

    /**
     * Determine whether the user can lock payroll runs.
     */
    public function lock(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]) && $payrollRun->canBeLocked();
    }

    /**
     * Determine whether the user can unlock payroll runs.
     */
    public function unlock(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]) && $payrollRun->canBeUnlocked();
    }

    /**
     * Determine whether the user can approve payroll runs.
     */
    public function approve(User $user, PayrollRun $payrollRun): bool
    {
        // Only HR Admin Manager can approve payroll runs
        // Accounting Manager cannot approve (separation of duties)
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can post payroll runs.
     */
    public function post(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]) && $payrollRun->canBePosted();
    }

    /**
     * Determine whether the user can export payroll data.
     */
    public function export(User $user, PayrollRun $payrollRun): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can view payroll statistics.
     */
    public function statistics(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can view a specific payslip.
     */
    public function viewPayslip(User $user, Payslip $payslip): bool
    {
        return $payslip->canViewBy($user);
    }

    /**
     * Determine whether the user can view net/gross amounts on payslips.
     */
    public function viewNetGross(User $user, Payslip $payslip): bool
    {
        return $payslip->canViewNetGrossBy($user);
    }

    /**
     * Determine whether the user can send payslips via email.
     */
    public function sendEmail(User $user, Payslip $payslip): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can export payslip data.
     */
    public function exportPayslips(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager'
        ]);
    }

    /**
     * Determine whether the user can view payslip statistics.
     */
    public function statisticsPayslips(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'Accounting_Manager',
            'HR_Coordinator'
        ]);
    }
}