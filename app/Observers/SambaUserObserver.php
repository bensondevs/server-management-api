<?php

namespace App\Observers;

use App\Models\SambaUser;
use App\Models\SambaGroup;

class SambaUserObserver
{
    /**
     * Handle the SambaUser "created" event.
     *
     * @param  \App\Models\SambaUser  $sambaUser
     * @return void
     */
    public function created(SambaUser $sambaUser)
    {
        // Create group using user username
        $group = SambaGroup::create([
            'container_id' => $sambaUser->container_id,
            'group_name' => $sambaUser->username,
        ]);

        // Add the user to group
        $group->addUser($sambaUser);
    }

    /**
     * Handle the SambaUser "updated" event.
     *
     * @param  \App\Models\SambaUser  $sambaUser
     * @return void
     */
    public function updated(SambaUser $sambaUser)
    {
        //
    }

    /**
     * Handle the SambaUser "deleted" event.
     *
     * @param  \App\Models\SambaUser  $sambaUser
     * @return void
     */
    public function deleted(SambaUser $sambaUser)
    {
        $userGroup = $sambaUser->userGroup;

        if (! $userGroup->users()->count()) {
            $userGroup->delete();
        }
    }

    /**
     * Handle the SambaUser "restored" event.
     *
     * @param  \App\Models\SambaUser  $sambaUser
     * @return void
     */
    public function restored(SambaUser $sambaUser)
    {
        //
    }

    /**
     * Handle the SambaUser "force deleted" event.
     *
     * @param  \App\Models\SambaUser  $sambaUser
     * @return void
     */
    public function forceDeleted(SambaUser $sambaUser)
    {
        //
    }
}
