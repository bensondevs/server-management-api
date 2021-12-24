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

        if (! $response->requester_ip) {
            $response->requester_ip = request()->ip();
        }
    }

    /**
     * Handle the SebPaymentApiResponse "created" event.
     *
     * @param  \App\Models\SebPaymentApiResponse  $response
     * @return void
     */
    public function created(SebPaymentApiResponse $response)
    {
        if ($data = $response->response_array) {
            // Update status of the seb payment model
            if (isset($data['payment_state'])) {
                $sebPayment = $response->sebPayment;
                $sebPayment->payment_state = $data['payment_state'];
                $sebPayment->save();
            }
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
