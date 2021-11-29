<?php

namespace App\Observers;

use App\Models\Datacenter;

class DatacenterObserver
{
    /**
     * Handle the Datacenter "created" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function created(Datacenter $datacenter)
    {
        $user = auth()->user();
        activity()
            ->performedOn($datacenter)
            ->causedBy($user)
            ->log($user->anchorName() . ' had created a new datacenter');
    }

    /**
     * Handle the Datacenter "updated" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function updated(Datacenter $datacenter)
    {
        $user = auth()->user();
        $anchorName = $user->anchorName();

        if ($datacenter->isDirty('status')) {
            $latestStatus = $datacenter->status_description;
            $updateStatusMessage = $anchorName . ' had changed a datacenter status to `' . $latestStatus . '`';

            record_activity($updateMessage, $user, $datacenter);
        }

        $updateMessage = $anchorName . ' had updated a datacenter';
        record_activity($updateMessage, $user, $datacenter);
    }

    /**
     * Handle the Datacenter "deleted" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function deleted(Datacenter $datacenter)
    {
        $user = auth()->user();

        $deleteMessage = $user->anchorName() . ' has deleted a datacenter.';
        record_activity($deleteMessage, $user, $datacenter);
    }

    /**
     * Handle the Datacenter "restored" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function restored(Datacenter $datacenter)
    {
        //
    }

    /**
     * Handle the Datacenter "force deleted" event.
     *
     * @param  \App\Models\Datacenter  $datacenter
     * @return void
     */
    public function forceDeleted(Datacenter $datacenter)
    {
        //
    }
}
