<?php

namespace App\Observers;

use App\Models\ContainerProperty;

class ContainerPropertyObserver
{
    /**
     * Handle the ContainerProperty "creating" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function creating(ContainerProperty $property)
    {
        $property->id = generateUuid();
    }

    /**
     * Handle the ContainerProperty "created" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function created(ContainerProperty $property)
    {
        $container = $property->container;
        if ($container->isCreatedOnServer()) {
            $property->syncToServer();
        }
    }

    /**
     * Handle the ContainerProperty "updated" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function updated(ContainerProperty $property)
    {
        $container = $property->container;
        if ($container->isCreatedOnServer()) {
            $property->syncToServer();
        }
    }

    /**
     * Handle the ContainerProperty "deleted" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function deleted(ContainerProperty $property)
    {
        //
    }

    /**
     * Handle the ContainerProperty "restored" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function restored(ContainerProperty $property)
    {
        //
    }

    /**
     * Handle the ContainerProperty "force deleted" event.
     *
     * @param  \App\Models\ContainerProperty  $property
     * @return void
     */
    public function forceDeleted(ContainerProperty $property)
    {
        //
    }
}
