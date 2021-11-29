<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Container;
use App\Models\NginxLocation;

class ContainerNginxPolicy
{
    use HandlesAuthorization;

    public function checkStatus(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'check status container nginxes');
    }

    public function checkPidNumbers(User $user, NginxLocation $location)
    {
        return $user->hasContainerPermission($location->container_id, 'check pid numbers container nginxes');
    }

    public function createLocation(User $user, NginxLocation $location)
    {
        return $user->hasContainerPermission($location->container_id, 'create location container nginxes');
    }

    public function removeLocation(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'remove location container nginxes');
    }

    public function start(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'start container nginxes');
    }

    public function restart(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'restart container nginxes');
    }

    public function reload(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'reload container nginxes');
    }

    public function stop(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'stop container nginxes');
    }
}
