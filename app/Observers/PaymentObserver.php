<?php

namespace App\Observers;

use App\Repositories\Payments\{
    SebRepository, PaypalRepository, StripeRepository
};

use App\Models\Payment;
use App\Enums\Payment\{
    PaymentStatus as Status,
    PaymentMethod as Method
};

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
        switch ($payment->status) {
            case Method::SEB:
                $respository = new SebRepository();
                break;
            
            default:
                $respository = new SebRepository();
                break;
        }

        $respository->create($payment);
    }

    /**
     * Handle the Payment "updated" event.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        /**
         * Actions in corresponding status change
         */
        if ($payment->isDirty('status')) {
            /**
             * Indicate payment has been paid
             */
            if ($payment->status == Status::Settled) {
                switch ($payment->method) {
                    case Method::SEB:
                        // code...
                        break;
                    
                    case Method::Paypal:
                        // code...
                        break;

                    case Method::Stripe:
                        // code...
                        break;

                    default:
                        // code...
                        break;
                }
            }

            /**
             * Indicate payment has been failed
             * due to late of payment or status change
             */
            if ($payment->status == Status::Failed) {
                //
            }
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
