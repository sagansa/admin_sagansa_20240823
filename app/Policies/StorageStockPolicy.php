<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StorageStock;

class StorageStockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_panel::storage::stock');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StorageStock $model): bool
    {
        return $user->can('view_panel::storage::stock');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_panel::storage::stock');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StorageStock $model): bool
    {
        return $user->can('update_panel::storage::stock');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StorageStock $model): bool
    {
        return $user->can('delete_panel::storage::stock');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StorageStock $model): bool
    {
        return $user->can('restore_panel::storage::stock');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StorageStock $model): bool
    {
        return $user->can('force_delete_panel::storage::stock');
    }
}
