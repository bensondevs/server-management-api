<?php

namespace App\Observers;

use App\Models\SambaShareUserPermission;

class SambaShareUserPermissionObserver
{
    /**
     * Handle the SambaShareUserPermission "creating" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function creating(SambaShareUserPermission $permission)
    {
        $permission->id = generateUuid();
    }

    /**
     * Handle the SambaShareUserPermission "created" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function created(SambaShareUserPermission $permission)
    {
        //
    }

    /**
     * Handle the SambaShareUserPermission "updated" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function updated(SambaShareUserPermission $permission)
    {
        //
    }

    /**
     * Handle the SambaShareUserPermission "deleted" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function deleted(SambaShareUserPermission $permission)
    {
        //
    }

    /**
     * Handle the SambaShareUserPermission "restored" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function restored(SambaShareUserPermission $permission)
    {
        //
    }

    /**
     * Handle the SambaShareUserPermission "force deleted" event.
     *
     * @param  \App\Models\SambaShareUserPermission  $permission
     * @return void
     */
    public function forceDeleted(SambaShareUserPermission $permission)
    {
        //
    }
}
