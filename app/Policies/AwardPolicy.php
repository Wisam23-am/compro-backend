<?php

namespace App\Policies;

use App\Models\Award;
use App\Models\User;

/**
 * Award Policy
 * 
 * Handles authorization for award management.
 * Customize these methods based on your role/permission system.
 */
class AwardPolicy
{
    /**
     * Determine whether the user can view any awards.
     */
    public function viewAny(User $user): bool
    {
        // Allow all authenticated users to view awards list
        // Customize based on your role/permission requirements
        return true;
    }

    /**
     * Determine whether the user can view the award.
     */
    public function view(User $user, Award $award): bool
    {
        // Allow all authenticated users to view individual awards
        return true;
    }

    /**
     * Determine whether the user can create awards.
     */
    public function create(User $user): bool
    {
        // Allow all authenticated users to create awards
        // Customize: return $user->hasRole('admin') || $user->can('create-awards');
        return true;
    }

    /**
     * Determine whether the user can update the award.
     */
    public function update(User $user, Award $award): bool
    {
        // Allow all authenticated users to update awards
        // Customize: return $user->hasRole('admin') || $user->can('edit-awards');
        return true;
    }

    /**
     * Determine whether the user can delete the award.
     */
    public function delete(User $user, Award $award): bool
    {
        // Allow all authenticated users to delete awards
        // Customize: return $user->hasRole('admin') || $user->can('delete-awards');
        return true;
    }

    /**
     * Determine whether the user can restore the award.
     */
    public function restore(User $user, Award $award): bool
    {
        return $this->delete($user, $award);
    }

    /**
     * Determine whether the user can permanently delete the award.
     */
    public function forceDelete(User $user, Award $award): bool
    {
        return $this->delete($user, $award);
    }

    /**
     * Determine whether the user can reorder awards.
     */
    public function reorder(User $user): bool
    {
        return true;
    }
}
