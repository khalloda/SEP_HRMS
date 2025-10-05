<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class AuditTrailPolicy
{
    /**
     * Determine whether the user can view any audit trail entries.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin']);
    }

    /**
     * Determine whether the user can view the audit trail entry.
     */
    public function view(User $user): bool
    {
        return $user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin', 'HR_Coordinator']);
    }

    /**
     * Determine whether the user can export audit trail data.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin']);
    }
}