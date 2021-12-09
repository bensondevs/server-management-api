<?php

namespace App\Observers;

use App\Models\{SebPayment, SebPaymentApiResponse};

class SebPaymentApiResponseObserver
{
    /**
     * Handle the SebPaymentApiResponse "creating" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function creating(SebPaymentApiResponse $response)
    {
        $response->id = generateUuid();
        $response->requester_ip = request()->ip();
    }

    /**
     * Handle the SebPaymentApiResponse "created" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function created(SebPaymentApiResponse $response)
    {
        if ($data = $response->response) {
            // Update status of the seb payment model
            $sebPayment = $response->sebPayment;
            $sebPayment->state = $data['state'];
            $sebPayment->save();
        }
    }

    /**
     * Handle the SebPaymentApiResponse "updated" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function updated(SebPaymentApiResponse $response)
    {
        //
    }

    /**
     * Handle the SebPaymentApiResponse "deleted" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function deleted(SebPaymentApiResponse $response)
    {
        //
    }

    /**
     * Handle the SebPaymentApiResponse "restored" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function restored(SebPaymentApiResponse $response)
    {
        //
    }

    /**
     * Handle the SebPaymentApiResponse "force deleted" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function forceDeleted(SebPaymentApiResponse $response)
    {
        //
    }
}
