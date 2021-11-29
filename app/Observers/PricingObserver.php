<?php

namespace App\Observers;

use App\Models\Pricing;

class PricingObserver
{
    /**
     * Handle the Pricing "created" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function created(Pricing $pricing)
    {
        $existedPricings = $pricing->sameCurrencyPricings();

        foreach ($existedPricings as $existedPricing) {
            $existedPricing->deactivate();
        }
    }

    /**
     * Handle the Pricing "updated" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function updated(Pricing $pricing)
    {
        if ($pricing->isDirty('status')) {
            $existedPricings = $pricing->sameCurrencyPricings();

            foreach ($existedPricings as $existedPricing) {
                $existedPricing->deactivate();
            }
        }
    }

    /**
     * Handle the Pricing "deleted" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function deleted(Pricing $pricing)
    {
        if ($otherPricings = $pricing->sameCurrencyPricings()) {
            $otherPricings->first()->activate();
        }
    }

    /**
     * Handle the Pricing "restored" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function restored(Pricing $pricing)
    {
        $existedPricings = $pricing->sameCurrencyPricings();

        foreach ($existedPricings as $existedPricing) {
            $existedPricing->deactivate();
        }
    }

    /**
     * Handle the Pricing "force deleted" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function forceDeleted(Pricing $pricing)
    {
        if ($otherPricings = $pricing->sameCurrencyPricings()) {
            $otherPricings->first()->activate();
        }
    }
}
