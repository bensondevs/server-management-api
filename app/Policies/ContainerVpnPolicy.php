<?php

namespace App\Policies;

use App\Models\Container;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContainerVpnPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'manage vpns');
    }

    public function checkStatus(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'check status container vpns');
    }

    public function checkPidNumbers(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'check pid numbers container vpns');
    }

    public function start(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'start container vpns');
    }

    public function restart(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'restart container vpns');
    }

    public function stop(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'stop container vpns');
    }

    public function toggleStartOnBoot(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'toggle start on boot container vpns');
    }

    public function downloadConfig(User $user, Container $container)
    {
        return $user->hasContainerPermission($container->id, 'download config container vpns');
    }
}
