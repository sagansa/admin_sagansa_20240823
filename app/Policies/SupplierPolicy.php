<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;

class SupplierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_panel::supplier');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supplier $model): bool
    {
        return $user->can('view_panel::supplier');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_panel::supplier');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Supplier $model): bool
    {
        return $user->can('update_panel::supplier');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Supplier $model): bool
    {
        return $user->can('delete_panel::supplier');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Supplier $model): bool
    {
        return $user->can('restore_panel::supplier');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Supplier $model): bool
    {
        return $user->can('force_delete_panel::supplier');
    }
}
