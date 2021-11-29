<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Datacenter;

use App\Repositories\ServerRepository;
use App\Repositories\SubnetRepository;
use App\Repositories\SubnetIpRepository;
use App\Repositories\ContainerRepository;

use App\Jobs\SendMail;
use App\Mail\Orders\OrderPlacedMail;

use App\Enums\Order\OrderStatus;

use App\Jobs\Order\ProcessOrder;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        try {
            $order->countTotal();
            $order->saveQuietly();

            /*$mail = new OrderPlacedMail($order);
            $send = new SendMail($mail, $order->customer->email);
            dispatch($send);*/

            if ($order->status == OrderStatus::Paid) {
                $order->process();
            }

            $order->createPayment();
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            abort(500, $error);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            if ($order->status == OrderStatus::Paid) {
                $process = new ProcessOrder($order);
                $process->delay(1);
                dispatch($process);
            }
        }

        if ($order->isDirty('vat_size_percentage')) {
            $order->countTotal();
            $order->save();
        }
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
