<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TransferStock;

class TransferStockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransferStock $model): bool
    {
        return $user->can('view_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransferStock $model): bool
    {
        return $user->can('update_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransferStock $model): bool
    {
        return $user->can('delete_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransferStock $model): bool
    {
        return $user->can('restore_panel::transfer::stocks');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransferStock $model): bool
    {
        return $user->can('force_delete_panel::transfer::stocks');
    }
}
