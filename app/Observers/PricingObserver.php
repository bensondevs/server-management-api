<?php

namespace App\Observers;

use App\Models\Pricing;

class PricingObserver
{
    /**
     * Handle the Pricing "creating" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function creating(Pricing $pricing)
    {
        $pricing->id = generateUuid();
    }

    /**
     * Handle the Pricing "created" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function created(Pricing $pricing)
    {
        //
    }

    /**
     * Handle the Pricing "updated" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function updated(Pricing $pricing)
    {
        //
    }

    /**
     * Handle the Pricing "deleted" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function deleted(Pricing $pricing)
    {
        //
    }

    /**
     * Handle the Pricing "restored" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function restored(Pricing $pricing)
    {
        //
    }

    /**
     * Handle the Pricing "force deleted" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function forceDeleted(Pricing $pricing)
    {
        //
    }
}
