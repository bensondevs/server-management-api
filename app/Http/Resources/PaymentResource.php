<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class PaymentResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'order_id' => $this->order_id,

            'methods' => $this->methods,
            'methods_description' => $this->methods_desription,

            'amount' => $this->formatted_amount,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'created_at' => $this->created_at,
        ];

        if ($this->vendor_api_response) {
            $structure['vendor_api_response'] = $this->vendor_api_response;
        }

        if ($this->payment_reference) {
            $structure['payment_reference'] = $this->payment_reference;
        }

        if ($this->billing_address) {
            $structure['billing_address'] = $this->billing_address;
        }

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
        }

        if ($this->relationLoaded('order')) {
            $structure['order'] = new OrderResource($this->order);
        }

        if ($this->relationLoaded('servicePlan')) {
            $structure['service_plan'] = new ServidePlan($this->servicePlan);
        }

        return $structure;
    }
}
