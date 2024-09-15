<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DeliveryAddress;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_panel::delivery::address');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DeliveryAddress $deliveryAddress): bool
    {
        // return $user->can('view_panel::delivery::address');

        // Cek apakah ada transaksi yang terkait dengan DeliveryAddress
        if ($deliveryAddress->salesOrders()->exists()) {
            // Jika ada transaksi, tidak bisa update
            return $user->can('update_panel::delivery::address');
        }

        // Jika tidak ada transaksi, bisa update
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_panel::delivery::address');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DeliveryAddress $deliveryAddress): bool
    {
        // return $user->can('update_panel::delivery::address');

        // Cek apakah ada transaksi yang terkait dengan DeliveryAddress
        if ($deliveryAddress->salesOrders()->exists()) {
            // Jika ada transaksi, tidak bisa update
            return false;
        }

        // Jika tidak ada transaksi, bisa update
        return $user->can('update_panel::delivery::address') && !Auth::user()->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DeliveryAddress $deliveryAddress): bool
    {
        return $user->can('delete_panel::delivery::address');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_panel::delivery::address');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, DeliveryAddress $deliveryAddress): bool
    {
        return $user->can('force_delete_panel::delivery::address');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_panel::delivery::address');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, DeliveryAddress $deliveryAddress): bool
    {
        return $user->can('restore_panel::delivery::address');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_panel::delivery::address');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, DeliveryAddress $deliveryAddress): bool
    {
        return $user->can('replicate_panel::delivery::address');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_panel::delivery::address');
    }
}
