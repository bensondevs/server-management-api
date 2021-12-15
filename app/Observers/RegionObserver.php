<?php

namespace App\Observers;

use App\Models\Region;

class RegionObserver
{
    /**
     * Handle the Region "creating" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function creating(Region $region)
    {
        $region->id = generateUuid();
    }

    /**
     * Handle the Region "created" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function created(Region $region)
    {
        //
    }

    /**
     * Handle the Region "updated" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function updated(Region $region)
    {
        //
    }

    /**
     * Handle the Region "deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function deleted(Region $region)
    {
        //
    }

    /**
     * Handle the Region "restored" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function restored(Region $region)
    {
        //
    }

    /**
     * Handle the Region "force deleted" event.
     *
     * @param  \App\Models\Region  $region
     * @return void
     */
    public function forceDeleted(Region $region)
    {
        //
    }
}
