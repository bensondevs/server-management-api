<?php

namespace App\Observers;

use App\Models\Subnet;

use App\Models\WaitingContainer;

class SubnetObserver
{
    /**
     * Handle the Subnet "creating" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function creating(Subnet $subnet)
    {
        $subnet->id = generateUuid();
    }

    /**
     * Handle the Subnet "created" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function created(Subnet $subnet)
    {
        $subnet->generateIps(); // Generate IPs

        //
    }

    /**
     * Handle the Subnet "updated" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function updated(Subnet $subnet)
    {
        //
    }

    /**
     * Handle the Subnet "deleted" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function deleted(Subnet $subnet)
    {
        //
    }

    /**
     * Handle the Subnet "restored" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function restored(Subnet $subnet)
    {
        //
    }

    /**
     * Handle the Subnet "force deleted" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function forceDeleted(Subnet $subnet)
    {
        //
    }
}
