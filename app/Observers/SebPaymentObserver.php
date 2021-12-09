<?php

namespace App\Observers;

use App\Models\SebPayment;

class SebPaymentObserver
{
    /**
     * Handle the SebPayment "creating" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function creating(SebPayment $sebPayment)
    {
        $sebPayment->id = generateUuid();
    }

    /**
     * Handle the SebPayment "created" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function created(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "updated" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function updated(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "deleted" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function deleted(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "restored" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function restored(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "force deleted" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function forceDeleted(SebPayment $sebPayment)
    {
        //
    }
}
