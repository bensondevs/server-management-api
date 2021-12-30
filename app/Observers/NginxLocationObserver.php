<?php

namespace App\Observers;

use App\Models\NginxLocation;

class NginxLocationObserver
{
    /**
     * Handle the NginxLocation "creating" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function creating(NginxLocation $nginxLocation)
    {
        $nginxLocation->id = generateUuid();
    }

    /**
     * Handle the NginxLocation "created" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function created(NginxLocation $nginxLocation)
    {
        //
    }

    /**
     * Handle the NginxLocation "updated" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function updated(NginxLocation $nginxLocation)
    {
        //
    }

    /**
     * Handle the NginxLocation "deleted" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function deleted(NginxLocation $nginxLocation)
    {
        //
    }

    /**
     * Handle the NginxLocation "restored" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function restored(NginxLocation $nginxLocation)
    {
        //
    }

    /**
     * Handle the NginxLocation "force deleted" event.
     *
     * @param  \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function forceDeleted(NginxLocation $nginxLocation)
    {
        //
    }
}
