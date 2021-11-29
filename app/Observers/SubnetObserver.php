<?php

namespace App\Observers;

use App\Models\Subnet;

use App\Models\WaitingContainer;

class SubnetObserver
{
    /**
     * Handle the Subnet "created" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function created(Subnet $subnet)
    {
        // Create all IP(s)
        $subnet->createIps();

        // Try to push back
        WaitingContainer::pushBack();

        if ($user = auth()->user()) {
            $message = $user->anchorName() . ' has created a subnet.';
            record_activity($message, $user, $subnet);
        }
    }

    /**
     * Handle the Subnet "updated" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function updated(Subnet $subnet)
    {
        if ($user = auth()->user()) {
            $message = $user->anchorName() . ' has updated subnet.';
            record_activity($message, $user, $subnet);

            if ($subnet->isDirty('status')) {
                $latestStatus = $subnet->status_description;
                $message = $user->anchorName() . ' has switched subnet status to ' . $latestStatus . '.';
                record_activity($message, $user, $subnet);
            }
        }

    }

    /**
     * Handle the Subnet "deleted" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function deleted(Subnet $subnet)
    {
        if ($user = auth()->user()) {
            $anchorName = $user->anchorName();

            $message = $anchorName . ' has deleted a subnet.';
            record_activity($message, $user, $subnet);
        }
    }

    /**
     * Handle the Subnet "restored" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function restored(Subnet $subnet)
    {
        //
    }

    /**
     * Handle the Subnet "force deleted" event.
     *
     * @param  \App\Models\Subnet  $subnet
     * @return void
     */
    public function forceDeleted(Subnet $subnet)
    {
        //
    }
}
