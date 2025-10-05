<?php

namespace App\Policies;

use App\Models\GeneratedLetter;
use App\Models\User;

class GeneratedLetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator',
            'Employee'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeneratedLetter $generatedLetter): bool
    {
        // HR roles can view all letters
        if ($user->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator'])) {
            return true;
        }

        // Employees can only view their own letters
        if ($user->hasRole('Employee')) {
            return $user->employee && $user->employee->id === $generatedLetter->employee_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeneratedLetter $generatedLetter): bool
    {
        // Only allow updates to draft letters
        if ($generatedLetter->status !== GeneratedLetter::STATUS_DRAFT) {
            return false;
        }

        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeneratedLetter $generatedLetter): bool
    {
        // Only allow deletion of draft letters
        if ($generatedLetter->status !== GeneratedLetter::STATUS_DRAFT) {
            return false;
        }

        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can approve/reject letters.
     */
    public function approve(User $user, GeneratedLetter $generatedLetter): bool
    {
        // Only HR Admin Manager can approve letters
        if (!$user->hasRole('HR_Admin_Manager')) {
            return false;
        }

        // Can only approve pending letters
        return $generatedLetter->status === GeneratedLetter::STATUS_PENDING_APPROVAL;
    }

    /**
     * Determine whether the user can download the letter.
     */
    public function download(User $user, GeneratedLetter $generatedLetter): bool
    {
        // Must be approved or sent to download
        if (!in_array($generatedLetter->status, [GeneratedLetter::STATUS_APPROVED, GeneratedLetter::STATUS_SENT])) {
            return false;
        }

        // HR roles can download all approved letters
        if ($user->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator'])) {
            return true;
        }

        // Employees can only download their own approved letters
        if ($user->hasRole('Employee')) {
            return $user->employee && $user->employee->id === $generatedLetter->employee_id;
        }

        return false;
    }
}