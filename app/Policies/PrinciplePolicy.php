<?php

namespace App\Policies;

use App\Models\Principle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Principle Policy
 * 
 * Handles authorization for Principle model operations.
 * Follows Laravel's policy conventions for resource authorization.
 */
class PrinciplePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any principles.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow all authenticated users to view principles list
        return true;
    }

    /**
     * Determine whether the user can view the principle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Principle  $principle
     * @return bool
     */
    public function view(User $user, Principle $principle): bool
    {
        // Allow all authenticated users to view a specific principle
        return true;
    }

    /**
     * Determine whether the user can create principles.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow all authenticated users to create principles
        // In production, you might want to restrict this to admin roles
        return true;
    }

    /**
     * Determine whether the user can update the principle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Principle  $principle
     * @return bool
     */
    public function update(User $user, Principle $principle): bool
    {
        // Allow all authenticated users to update principles
        // In production, you might want to restrict this to admin roles
        return true;
    }

    /**
     * Determine whether the user can delete the principle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Principle  $principle
     * @return bool
     */
    public function delete(User $user, Principle $principle): bool
    {
        // Allow all authenticated users to delete principles
        // In production, you might want to restrict this to admin roles
        return true;
    }

    /**
     * Determine whether the user can restore the principle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Principle  $principle
     * @return bool
     */
    public function restore(User $user, Principle $principle): bool
    {
        // Allow all authenticated users to restore soft-deleted principles
        return true;
    }

    /**
     * Determine whether the user can permanently delete the principle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Principle  $principle
     * @return bool
     */
    public function forceDelete(User $user, Principle $principle): bool
    {
        // Allow all authenticated users to force delete principles
        // In production, you might want to restrict this to super admin roles
        return true;
    }

    /**
     * Determine whether the user can reorder principles.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        // Allow all authenticated users to reorder principles
        return true;
    }
}
