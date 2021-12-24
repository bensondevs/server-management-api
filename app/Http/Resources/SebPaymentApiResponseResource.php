<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SebPaymentApiResponseResource extends JsonResource
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
            'seb_payment_id' => $this->seb_payment_id,
            'requester_ip' => $this->requester_ip,
            'response' => $this->response_array,
        ];

        if ($this->relationLoaded('sebPayment')) {
            $sebPayment = new SebPaymentResource($this->sebPayment);
            $structure['seb_payment'] = $sebPayment;
        }

        return $structure;
    }
}
