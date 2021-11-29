<?php

namespace App\Observers;

use App\Models\SambaShare;

class SambaShareObserver
{
    /**
     * Handle the SambaShare "created" event.
     *
     * @param  \App\Models\SambaShare  $sambaShare
     * @return void
     */
    public function created(SambaShare $sambaShare)
    {
        //
    }

    /**
     * Handle the SambaShare "updated" event.
     *
     * @param  \App\Models\SambaShare  $sambaShare
     * @return void
     */
    public function updated(SambaShare $sambaShare)
    {
        //
    }

    /**
     * Handle the SambaShare "deleted" event.
     *
     * @param  \App\Models\SambaShare  $sambaShare
     * @return void
     */
    public function deleted(SambaShare $sambaShare)
    {
        //
    }

    /**
     * Handle the SambaShare "restored" event.
     *
     * @param  \App\Models\SambaShare  $sambaShare
     * @return void
     */
    public function restored(SambaShare $sambaShare)
    {
        return false;
    }

    /**
     * Handle the SambaShare "force deleted" event.
     *
     * @param  \App\Models\SambaShare  $sambaShare
     * @return void
     */
    public function forceDeleted(SambaShare $sambaShare)
    {
        //
    }
}
