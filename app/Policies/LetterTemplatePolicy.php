<?php

namespace App\Policies;

use App\Models\LetterTemplate;
use App\Models\User;

class LetterTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LetterTemplate $letterTemplate): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
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
    public function update(User $user, LetterTemplate $letterTemplate): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager',
            'HR_Coordinator'
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LetterTemplate $letterTemplate): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LetterTemplate $letterTemplate): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LetterTemplate $letterTemplate): bool
    {
        return $user->hasRole('HR_Admin_Manager');
    }
}