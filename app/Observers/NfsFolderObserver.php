<?php

namespace App\Observers;

use App\Models\NfsFolder;

class NfsFolderObserver
{
    /**
     * Handle the NfsFolder "creating" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function creating(NfsFolder $nfsFolder)
    {
        $nfsFolder->id = generateUuid();
    }

    /**
     * Handle the NfsFolder "created" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function created(NfsFolder $nfsFolder)
    {
        //
    }

    /**
     * Handle the NfsFolder "updated" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function updated(NfsFolder $nfsFolder)
    {
        //
    }

    /**
     * Handle the NfsFolder "deleted" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function deleted(NfsFolder $nfsFolder)
    {
        //
    }

    /**
     * Handle the NfsFolder "restored" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function restored(NfsFolder $nfsFolder)
    {
        //
    }

    /**
     * Handle the NfsFolder "force deleted" event.
     *
     * @param  \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function forceDeleted(NfsFolder $nfsFolder)
    {
        //
    }
}
