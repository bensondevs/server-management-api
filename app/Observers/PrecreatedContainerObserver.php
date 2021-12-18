<?php

namespace App\Observers;

use App\Models\PrecreatedContainer;

class PrecreatedContainerObserver
{
    /**
     * Handle the PrecreatedContainer "creating" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function creating(PrecreatedContainer $preContainer)
    {
        $preContainer->id = generateUuid();
    }

    /**
     * Handle the PrecreatedContainer "created" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function created(PrecreatedContainer $preContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "updated" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function updated(PrecreatedContainer $preContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "deleted" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function deleted(PrecreatedContainer $preContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "restored" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function restored(PrecreatedContainer $preContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "force deleted" event.
     *
     * @param  \App\Models\PrecreatedContainer  $preContainer
     * @return void
     */
    public function forceDeleted(PrecreatedContainer $preContainer)
    {
        //
    }
}
