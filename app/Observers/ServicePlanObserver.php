<?php

namespace App\Observers;

use App\Models\ServicePlan;

class ServicePlanObserver
{
    /**
     * Handle the ServicePlan "currency" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function creating(ServicePlan $servicePlan)
    {
        $servicePlan->id = generateUuid();
    }

    /**
     * Handle the ServicePlan "created" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function created(ServicePlan $servicePlan)
    {
        //
    }

    /**
     * Handle the ServicePlan "updated" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function updated(ServicePlan $servicePlan)
    {
        //
    }

    /**
     * Handle the ServicePlan "deleted" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function deleted(ServicePlan $servicePlan)
    {
        //
    }

    /**
     * Handle the ServicePlan "restored" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function restored(ServicePlan $servicePlan)
    {
        //
    }

    /**
     * Handle the ServicePlan "force deleted" event.
     *
     * @param  \App\Models\ServicePlan  $servicePlan
     * @return void
     */
    public function forceDeleted(ServicePlan $servicePlan)
    {
        //
    }
}
