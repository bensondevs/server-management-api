<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

use App\Models\{ Container, SubnetIp };
use App\Jobs\Container\DestroyContainer;
use App\Repositories\ContainerRepository;

class ContainerObserver
{
    /**
     * Handle the Container "creating" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function creating(Container $container)
    {
        $subnetIp = SubnetIp::findOrFail($container->subnet_ip_id);
        $container->id = $container->generate_id($subnetIp->ip_address);
    }

    /**
     * Handle the Container "created" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function created(Container $container)
    {
        /*$containerRepo = new ContainerRepository;
        $containerRepo->setModel($container);
        $containerRepo->createOnServer();

        // Activate container
        if ($order = $container->order) {
            $order->activate();
        }

        if ($user = auth()->user()) {
            record_activity($user->anchorName() . ' had created a new container.', $user, $container);
        }*/

        // Create on server
        $container->createOnServer();

        // Assign user to Subnet IP
        $subnetIp = $container->subnetIp;
        $subnetIp->assignTo($container->user);
    }

    /**
     * Handle the Container "updated" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function updated(Container $container)
    {
        if ($container->isDirty('subnet_ip_id')) {
            // Revoke old subnet ip
            $previousSubnetIp = SubnetIp::findOrFail($container->getOriginal('subnet_ip_id'));
            if ($previousSubnetIp) {
                $previousSubnetIp->revokeUser();
            }

            // Assign to new subnet ip
            if ($newSubnetIp = $container->subnet_ip) {
                $newSubnetIp->assigned_user_id = $container->customer_id;
                $newSubnetIp->save();
            }
        }

        if ($container->isDirty('current') && $container->current) {
            $unselectedContainer = Container::where('current', true)
                ->where('id', '!=', $container->id)
                ->first();
            if ($unselectedContainer) {
                $unselectedContainer->current = false;
                $unselectedContainer->save();
            }
        }

        if ($container->isDirty('vpn_status')) {
            $cache = Cache::get('vpn_status') ?: [];
            $cache[$container->id] = $container->vpn_status;
            Cache::put('vpn_status', $cache, 300);
        }

        if ($container->isDirty('vpn_pid_numbers')) {
            $cache = Cache::get('vpn_pid_numbers');
            $cache[$container->id] = $container->vpn_pid_numbers;
            Cache::put('vpn_pid_numbers', $cache, 300);
        }

        if ($container->isDirty('samba_status')) {
            $cache = Cache::get('samba_status') ?: [];
            $cache[$container->id] = $container->samba_status;
            Cache::put('samba_status', $cache, 300);
        }

        if ($container->isDirty('samba_pid_numbers')) {
            $cache = Cache::get('samba_pid_numbers') ?: [];
            $cache[$container->id] = $container->samba_pid_numbers;
            Cache::put('samba_pid_numbers', $cache, 300);
        }

        if ($container->isDirty('nginx_status')) {
            $cache = Cache::get('nginx_status') ?: [];
            $cache[$container->id] = $container->nginx_status;
            Cache::put('nginx_status', $cache, 300);
        }

        if ($container->isDirty('nginx_pid_numbers')) {
            $cache = Cache::get('nginx_pid_numbers') ?: [];
            $cache[$container->id] = $container->nginx_pid_numbers;
            Cache::put('nginx_pid_numbers', $cache, 300);
        }

        if ($container->isDirty('nfs_status')) {
            $cache = Cache::get('nfs_status') ?: [];
            $cache[$container->id] = $container->nfs_status;
            Cache::put('nfs_status', $cache, 300);
        }

        if ($container->isDirty('nfs_pid_numbers')) {
            $cache = Cache::get('nfs_pid_numbers') ?: [];
            $cache[$container->id] = $container->nfs_pid_numbers;
            Cache::put('nfs_status', $cache, 300);
        }

        if ($user = auth()->user()) {
            record_activity($user->anchorName() . ' has updated container (ID:' . $container->id . ')', $user, $container);
        }
    }

    /**
     * Handle the Container "deleted" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function deleted(Container $container)
    {
        if ($subnetIp = $container->subnetIp) {
            $subnetIp->revokeUser();
        }

        $destroyContainer = new DestroyContainer($container);
        dispatch($destroyContainer);
    }

    /**
     * Handle the Container "restored" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function restored(Container $container)
    {
        //
    }

    /**
     * Handle the Container "force deleted" event.
     *
     * @param  \App\Models\Container  $container
     * @return void
     */
    public function forceDeleted(Container $container)
    {
        if ($subnetIp = $container->subnet_ip) {
            $subnetIp->revokeUser();
        }

        $destroyContainer = new DestroyContainer($container);
        dispatch($destroyContainer);
    }
}
