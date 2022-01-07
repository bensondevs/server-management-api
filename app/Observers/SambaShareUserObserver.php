<?php

namespace App\Observers;

use App\Models\SambaShareUser;

class SambaShareUserObserver
{
    /**
     * Handle the SambaShareUser "creating" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function creating(SambaShareUser $sambaShareUser)
    {
        $sambaShareUser->id = generateUuid();
    }

    /**
     * Handle the SambaShareUser "created" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function created(SambaShareUser $sambaShareUser)
    {
        //
    }

    /**
     * Handle the SambaShareUser "updated" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function updated(SambaShareUser $sambaShareUser)
    {
        //
    }

    /**
     * Handle the SambaShareUser "deleted" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function deleted(SambaShareUser $sambaShareUser)
    {
        //
    }

    /**
     * Handle the SambaShareUser "restored" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function restored(SambaShareUser $sambaShareUser)
    {
        //
    }

    /**
     * Handle the SambaShareUser "force deleted" event.
     *
     * @param  \App\Models\SambaShareUser  $sambaShareUser
     * @return void
     */
    public function forceDeleted(SambaShareUser $sambaShareUser)
    {
        //
    }
}
