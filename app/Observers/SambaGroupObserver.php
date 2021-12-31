<?php

namespace App\Observers;

use App\Models\SambaGroup;

class SambaGroupObserver
{
    /**
     * Handle the SambaGroup "creating" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function creating(SambaGroup $sambaGroup)
    {
        $sambaGroup->id = generateUuid();
    }

    /**
     * Handle the SambaGroup "created" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function created(SambaGroup $sambaGroup)
    {
        //
    }

    /**
     * Handle the SambaGroup "updated" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function updated(SambaGroup $sambaGroup)
    {
        //
    }

    /**
     * Handle the SambaGroup "deleted" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function deleted(SambaGroup $sambaGroup)
    {
        //
    }

    /**
     * Handle the SambaGroup "restored" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function restored(SambaGroup $sambaGroup)
    {
        //
    }

    /**
     * Handle the SambaGroup "force deleted" event.
     *
     * @param  \App\Models\SambaGroup  $sambaGroup
     * @return void
     */
    public function forceDeleted(SambaGroup $sambaGroup)
    {
        //
    }
}
