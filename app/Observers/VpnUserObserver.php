<?php

namespace App\Observers;

use App\Models\{VpnUser, VpnSubnet};

class VpnUserObserver
{
    /**
     * Handle the VpnUser "creating" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function creating(VpnUser $vpnUser)
    {
        if (! $vpnUser->id) {
            $vpnUser->id = generateUuid();
        }
    }

    /**
     * Handle the VpnUser "created" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function created(VpnUser $vpnUser)
    {
        if (! VpnSubnet::subnetExist($vpnUser->container_id, $vpnUser->vpn_subnet)) {
            VpnSubnet::create([
                'container_id' => $vpnUser->container_id,
                'subnet' => $vpnUser->vpn_subnet,
            ]);
        }
    }

    /**
     * Handle the VpnUser "updated" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function updated(VpnUser $vpnUser)
    {
        if (! VpnSubnet::subnetExist($vpnUser->container_id, $vpnUser->vpn_subnet)) {
            VpnSubnet::create([
                'container_id' => $vpnUser->container_id,
                'subnet' => $vpnUser->vpn_subnet,
            ]);
        }
    }

    /**
     * Handle the VpnUser "deleted" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function deleted(VpnUser $vpnUser)
    {
        //
    }

    /**
     * Handle the VpnUser "restored" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function restored(VpnUser $vpnUser)
    {
        //
    }

    /**
     * Handle the VpnUser "force deleted" event.
     *
     * @param  \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function forceDeleted(VpnUser $vpnUser)
    {
        //
    }
}
