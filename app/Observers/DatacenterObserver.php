<?php

namespace App\Observers;

use App\Models\Datacenter;

class DatacenterObserver
{
    /**
     * Handle the Datacenter "creating" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function creating(Datacenter $datacenter)
    {
        $datacenter->id = generateUuid();
    }

    /**
     * Handle the Datacenter "created" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function created(Datacenter $datacenter)
    {
        //
    }

    /**
     * Handle the Datacenter "updated" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function updated(Datacenter $datacenter)
    {
        //
    }

    /**
     * Handle the Datacenter "deleted" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function deleted(Datacenter $datacenter)
    {
        //
    }

    /**
     * Handle the Datacenter "restored" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function restored(Datacenter $datacenter)
    {
        //
    }

    /**
     * Handle the Datacenter "force deleted" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function forceDeleted(Datacenter $datacenter)
    {
        //
    }
}
