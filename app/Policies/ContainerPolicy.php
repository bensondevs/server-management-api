<?php

namespace App\Policies;

use App\Models\Container;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContainerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view containers');
    }

    public function view(User $user, Container $container)
    {
        return $user->id == $container->customer_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('administrator');
    }

    public function update(User $user, Container $container)
    {
        return $user->id == $container->customer_id;
    }

    public function delete(User $user, Container $container)
    {
        return $user->id == $container->customer_id;
    }

    public function restore(User $user, Container $container)
    {
        return $user->hasRole('administrator');
    }

    public function forceDelete(User $user, Container $container)
    {
        return $user->hasRole('administrator');
    }

    public function installSystem(User $user, Container $container)
    {
        return $user->id == $container->id;
    }
}
