<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'customer_id' => $this->customer_id,
            'container_id' => $this->container_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'vat_size' => $this->vat_size_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
        ];

        if ($this->relationLoaded('plan')) {
            $structure['plan'] = new OrderPlanResource($this->plan);
        }

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new UserResource($this->customer);
            $structure['customer_name'] = $structure['customer']->full_name;
        }

        if ($this->relationLoaded('container')) {
            $structure['container'] = new ContainerResource($this->container);
        }

        if ($this->relationLoaded('payment')) {
            $structure['payment'] = new PaymentResource($this->payment);
        }

        return $structure;
    }
}
