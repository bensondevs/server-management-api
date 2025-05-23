<?php

namespace App\Observers;

use App\Models\SubnetIp;

class SubnetIpObserver
{
    /**
     * Handle the SubnetIp "creating" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function creating(SubnetIp $subnetIp)
    {
        $subnetIp->id = generateUuid();
    }

    /**
     * Handle the SubnetIp "created" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function created(SubnetIp $subnetIp)
    {
        //
    }

    /**
     * Handle the SubnetIp "updated" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function updated(SubnetIp $subnetIp)
    {
        //
    }

    /**
     * Handle the SubnetIp "deleted" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function deleted(SubnetIp $subnetIp)
    {
        //
    }

    /**
     * Handle the SubnetIp "restored" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function restored(SubnetIp $subnetIp)
    {
        //
    }

    /**
     * Handle the SubnetIp "force deleted" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function forceDeleted(SubnetIp $subnetIp)
    {
        //
    }
}
