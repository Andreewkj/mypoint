<?php

namespace App\Policies;

use App\Models\PointMarking;
use App\Models\User;

class PointMarkingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PointMarking $pointMarking): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PointMarking $pointMarking): bool
    {
        if ($user->isAdmin() && $pointMarking->user->company->id === $user->company_id) {
            return true;
        }

        if (($user->isEmployee() && $pointMarking->user->id === $user->id) && $pointMarking->status !== PointMarking::STATUS_PENDING) {
            return true;
        }

        if ($user->isMaster()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PointMarking $pointMarking): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PointMarking $pointMarking): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PointMarking $pointMarking): bool
    {
        return true;
    }
}
