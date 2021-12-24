<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SebPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'order_reference' => $this->order_reference,
            'state' => $this->state,
            'state_description' => $this->state_description,
            'amount' => $this->amount,
            'billing_address' => $this->billing_address,
        ];

        if ($this->relationLoaded('payment')) {
            $payment = new PaymentResource($this->payment);
            $structure['payment'] = $payment;
        }

        if ($this->relationLoaded('apiResponses')) {
            $responses = $this->apiResponses;
            $responses = SebPaymentApiResponseResource::collection($responses);
            $structure['api_responses'] = $responses;
        }

        return $structure;
    }
}
