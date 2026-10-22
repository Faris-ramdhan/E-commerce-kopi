<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('Administrator')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view orders');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view orders');
    }

    public function create(User $user): bool
    {
        return $user->can('process orders');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('update order status') || $user->can('process orders');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('Administrator');
    }
}
