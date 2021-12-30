<?php

namespace App\Observers;

use App\Models\NfsExport;

class NfsExportObserver
{
    /**
     * Handle the NfsExport "creating" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function creating(NfsExport $nfsExport)
    {
        $nfsExport->id = generateUuid();
    }

    /**
     * Handle the NfsExport "created" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function created(NfsExport $nfsExport)
    {
        //
    }

    /**
     * Handle the NfsExport "updated" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function updated(NfsExport $nfsExport)
    {
        //
    }

    /**
     * Handle the NfsExport "deleted" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function deleted(NfsExport $nfsExport)
    {
        //
    }

    /**
     * Handle the NfsExport "restored" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function restored(NfsExport $nfsExport)
    {
        //
    }

    /**
     * Handle the NfsExport "force deleted" event.
     *
     * @param  \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function forceDeleted(NfsExport $nfsExport)
    {
        //
    }
}
