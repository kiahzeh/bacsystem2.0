<?php

namespace App\Policies;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list (filtered by department)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseRequest $purchaseRequest): bool
    {
        // Admin can view all PRs
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only view PRs from their department
        return $user->department_id === $purchaseRequest->department_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only admin can create PRs
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseRequest $purchaseRequest): bool
    {
        return $user->isAdmin(); // Only admin can update PRs
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseRequest $purchaseRequest): bool
    {
        return $user->isAdmin(); // Only admin can delete PRs
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchaseRequest $purchaseRequest): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchaseRequest $purchaseRequest): bool
    {
        //
    }
}
