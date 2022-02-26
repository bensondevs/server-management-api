<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\{ User, Container, NginxLocation };

class ContainerNginxPolicy
{
    use HandlesAuthorization;

    /**
     * Determines that current user is allowed to check status
     * of the NGINX service in certain container
     * 
     * @param  \App\Models\User       $user
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function checkStatus(User $user, Container $container)
    {
        $permission = 'check status container nginxes';
        return $user->hasContainerPermission($container->id, $permission);
    }

    /**
     * Determines that current user is allowed to check PID Numbers
     * of the NGINX service in certain container
     * 
     * @param  \App\Models\User           $user
     * @param  \App\Models\NginxLocation  $location
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function checkPidNumbers(User $user, NginxLocation $location)
    {
        $permission = 'check pid numbers container nginxes';
        return $user->hasContainerPermission($location->container_id, $permission);
    }

    /**
     * Determines that current user is allowed to create location
     * 
     * @param  \App\Models\User           $user
     * @param  \App\Models\NginxLocation  $location
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function createLocation(User $user, NginxLocation $location)
    {
        $permission = 'create location container nginxes';
        return $user->hasContainerPermission($location->container_id, $permission);
    }

    /**
     * Determines that current user is allowed to create location
     * 
     * @param  \App\Models\User           $user
     * @param  \App\Models\NginxLocation  $location
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function removeLocation(User $user, Container $container)
    {
        $permission = 'remove location container nginxes';
        return $user->hasContainerPermission($container, $permission);
    }

    /**
     * Determines that current user is allowed to start NGINX service
     * 
     * @param  \App\Models\User       $user
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function start(User $user, Container $container)
    {
        $permission = 'start container nginxes';
        return $user->hasContainerPermission($container, $permission);
    }

    /**
     * Determines that current user is allowed to restart NGINX service
     * 
     * @param  \App\Models\User       $user
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function restart(User $user, Container $container)
    {
        $permission = 'restart container nginxes';
        return $user->hasContainerPermission($container, $permission);
    }

    /**
     * Determines that current user is allowed to reload NGINX service
     * 
     * @param  \App\Models\User       $user
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function reload(User $user, Container $container)
    {
        $permission = 'reload container nginxes';
        return $user->hasContainerPermission($container, $permission);
    }

    /**
     * Determines that current user is allowed to stop NGINX service
     * 
     * @param  \App\Models\User       $user
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Auth\Access\Response|bool
     */
    public function stop(User $user, Container $container)
    {
        $permission = 'stop container nginxes';
        return $user->hasContainerPermission($container, $permission);
    }
}
