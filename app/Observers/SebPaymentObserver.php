<?php

namespace App\Observers;

use App\Models\SebPayment;
use App\Enums\Payment\PaymentStatus;
use App\Enums\SebPayment\SebPaymentState as State;

class SebPaymentObserver
{
    /**
     * Handle the SebPayment "creating" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function creating(SebPayment $sebPayment)
    {
        $sebPayment->id = generateUuid();
    }

    /**
     * Handle the SebPayment "created" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function created(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "updated" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function updated(SebPayment $sebPayment)
    {
        if ($sebPayment->isDirty('state')) {
            switch ($sebPayment->state) {
                case State::Settled:
                    $payment = $sebPayment->payment;
                    $payment->status = PaymentStatus::Settled;
                    $payment->save();
                    break;
                
                default:
                    // code...
                    break;
            }
        }
    }

    /**
     * Handle the SebPayment "deleted" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function deleted(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "restored" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function restored(SebPayment $sebPayment)
    {
        //
    }

    /**
     * Handle the SebPayment "force deleted" event.
     *
     * @param  \App\Models\SebPayment  $sebPayment
     * @return void
     */
    public function forceDeleted(SebPayment $sebPayment)
    {
        //
    }
}
