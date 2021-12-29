<?php

namespace App\Observers;

use App\Models\{ Order, User };
use App\Enums\Order\OrderStatus as Status;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $order->id = isset($order->id) ? $order->id : generateUuid();
        $order->order_number = $order->generateOrderNumber();
        $order->expired_at = now()->addDays(3);

        if (! $order->user_id) {
            $order->user_id = auth()->user()->id;
        }

        if (! $order->currency) {
            $user = User::findOrFail($order->user_id);
            $order->currency = $user->currency;
        }

        if (! $order->vat_size_percentage) {
            $user = User::findOrFail($order->user_id);
            $order->vat_size_percentage = $user->vat_size_percentage;
        }
    }

    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        // Count order grand total and save without triggering event
        // Because, triggering event in observer might causing endless loop
        $order->countGrandTotal();
        $order->saveQuietly();
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
