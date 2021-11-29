<?php

namespace App\Observers;

use App\Models\Payment;

use App\Enums\Payment\PaymentStatus;
use App\Enums\Order\OrderStatus;

use App\Jobs\SendMail;
use App\Jobs\Order\ProcessOrder;

use App\Mail\Orders\OrderPaidMail;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        if ($payment->status == PaymentStatus::Settled) {
            $order = $payment->order;
            $order->status = OrderStatus::Paid;
            $order->save();

            /*$mail = new OrderPaidMail($order);
            $send = new SendMail($mail, $order->customer->email);
            $send->delay(1);
            dispatch($send);*/

            $process = new ProcessOrder($order);
            $process->delay(1);
            dispatch($process);
        }
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        if ($payment->isDirty('status') && $payment->status == PaymentStatus::Settled) {
            $order = $payment->order;
            $order->status = OrderStatus::Paid;
            $order->save();

            /*$mail = new OrderPaidMail($order);
            $send = new SendMail($mail, $order->customer->email);
            $send->delay(1);
            dispatch($send);*/

            $process = new ProcessOrder($order);
            $process->delay(1);
            dispatch($process);
        }
    }

    /**
     * Handle the Payment "deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
