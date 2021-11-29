<?php

namespace App\Observers;

use App\Models\OrderPlan;

class OrderPlanObserver
{
    /**
     * Handle the OrderPlan "created" event.
     *
     * @param  \App\Models\OrderPlan  $orderPlan
     * @return void
     */
    public function created(OrderPlan $orderPlan)
    {
        $order = $orderPlan->order;
        $order->total_amount = $order->countTotal();
        $order->save();
    }

    /**
     * Handle the OrderPlan "updated" event.
     *
     * @param  \App\Models\OrderPlan  $orderPlan
     * @return void
     */
    public function updated(OrderPlan $orderPlan)
    {
        $order = $orderPlan->order;
        $order->total_amount = $order->countTotal();
        $order->save();
    }

    /**
     * Handle the OrderPlan "deleted" event.
     *
     * @param  \App\Models\OrderPlan  $orderPlan
     * @return void
     */
    public function deleted(OrderPlan $orderPlan)
    {
        //
    }

    /**
     * Handle the OrderPlan "restored" event.
     *
     * @param  \App\Models\OrderPlan  $orderPlan
     * @return void
     */
    public function restored(OrderPlan $orderPlan)
    {
        //
    }

    /**
     * Handle the OrderPlan "force deleted" event.
     *
     * @param  \App\Models\OrderPlan  $orderPlan
     * @return void
     */
    public function forceDeleted(OrderPlan $orderPlan)
    {
        //
    }
}
