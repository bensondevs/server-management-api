<?php

namespace App\Observers;

use App\Models\ContainerAdditionalProperty as AdditionalProp;

class ContainerAdditionalPropertyObserver
{
    /**
     * Handle the ContainerAdditionalProperty "created" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function creating(AdditionalProp $additionalProp)
    {
        $additionalProp->id = generateUuid();
    }

    /**
     * Handle the ContainerAdditionalProperty "created" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function created(AdditionalProp $additionalProp)
    {
        //
    }

    /**
     * Handle the ContainerAdditionalProperty "updated" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function updated(AdditionalProp $additionalProp)
    {
        //
    }

    /**
     * Handle the ContainerAdditionalProperty "deleted" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function deleted(AdditionalProp $additionalProp)
    {
        $property = $additionalProp->property;

        //
    }

    /**
     * Handle the ContainerAdditionalProperty "restored" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function restored(AdditionalProp $additionalProp)
    {
        //
    }

    /**
     * Handle the ContainerAdditionalProperty "force deleted" event.
     *
     * @param  AdditionalProp  $additionalProp
     * @return void
     */
    public function forceDeleted(AdditionalProp $additionalProp)
    {
        //
    }
}
