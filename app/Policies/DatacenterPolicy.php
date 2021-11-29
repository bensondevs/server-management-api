<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Datacenter;

class DatacenterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view datacenters');
    }

    public function view(User $user, Datacenter $datacenter)
    {
        return $user->hasPermissionTo('view datacenters');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create datacenters');
    }

    public function update(User $user, Datacenter $datacenter)
    {
        return $user->hasPermissionTo('edit datacenters');
    }

    public function delete(User $user, Datacenter $datacenter)
    {
        return $user->hasPermissionTo('delete datacenter');
    }

    public function restore(User $user, Datacenter $datacenter)
    {
        //
    }

    public function forceDelete(User $user, Datacenter $datacenter)
    {
        //
    }
}