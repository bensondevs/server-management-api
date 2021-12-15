<?php

namespace App\Observers;

use App\Models\PrecreatedContainer;

class PrecreatedContainerObserver
{
    /**
     * Handle the PrecreatedContainer "creating" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function creating(PrecreatedContainer $precreatedContainer)
    {
        $precreatedContainer->id = generateUuid();
    }

    /**
     * Handle the PrecreatedContainer "created" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function created(PrecreatedContainer $precreatedContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "updated" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function updated(PrecreatedContainer $precreatedContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "deleted" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function deleted(PrecreatedContainer $precreatedContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "restored" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function restored(PrecreatedContainer $precreatedContainer)
    {
        //
    }

    /**
     * Handle the PrecreatedContainer "force deleted" event.
     *
     * @param  \App\Models\PrecreatedContainer  $precreatedContainer
     * @return void
     */
    public function forceDeleted(PrecreatedContainer $precreatedContainer)
    {
        //
    }
}
