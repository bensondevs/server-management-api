<?php

namespace App\Observers;

use App\Models\SubnetIp;

use App\Models\WaitingContainer;

class SubnetIpObserver
{
    /**
     * Handle the SubnetIp "created" event.
     *
     * @param  \App\Models\SubnetIp  $subnetIp
     * @return void
     */
    public function created(SubnetIp $subnetIp)
    {
        WaitingContainer::pushBack();
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
