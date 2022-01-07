<?php

namespace App\Observers;

use App\Models\SambaShareGroup;

class SambaShareGroupObserver
{
    /**
     * Handle the SambaShareGroup "creating" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function creating(SambaShareGroup $sambaShareGroup)
    {
        $sambaShareGroup->id = generateUuid();
    }

    /**
     * Handle the SambaShareGroup "created" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function created(SambaShareGroup $sambaShareGroup)
    {
        //
    }

    /**
     * Handle the SambaShareGroup "updated" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function updated(SambaShareGroup $sambaShareGroup)
    {
        //
    }

    /**
     * Handle the SambaShareGroup "deleted" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function deleted(SambaShareGroup $sambaShareGroup)
    {
        //
    }

    /**
     * Handle the SambaShareGroup "restored" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function restored(SambaShareGroup $sambaShareGroup)
    {
        //
    }

    /**
     * Handle the SambaShareGroup "force deleted" event.
     *
     * @param  \App\Models\SambaShareGroup  $sambaShareGroup
     * @return void
     */
    public function forceDeleted(SambaShareGroup $sambaShareGroup)
    {
        //
    }
}
