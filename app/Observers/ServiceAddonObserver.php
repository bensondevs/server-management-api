<?php

namespace App\Observers;

use App\Models\ServiceAddon;

class ServiceAddonObserver
{
    /**
     * Handle the ServiceAddon "creating" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function creating(ServiceAddon $serviceAddon)
    {
        $serviceAddon->id = generateUuid();
    }

    /**
     * Handle the ServiceAddon "created" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function created(ServiceAddon $serviceAddon)
    {
        //
    }

    /**
     * Handle the ServiceAddon "updated" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function updated(ServiceAddon $serviceAddon)
    {
        //
    }

    /**
     * Handle the ServiceAddon "deleted" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function deleted(ServiceAddon $serviceAddon)
    {
        //
    }

    /**
     * Handle the ServiceAddon "restored" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function restored(ServiceAddon $serviceAddon)
    {
        //
    }

    /**
     * Handle the ServiceAddon "force deleted" event.
     *
     * @param  \App\Models\ServiceAddon  $serviceAddon
     * @return void
     */
    public function forceDeleted(ServiceAddon $serviceAddon)
    {
        //
    }
}
