<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'order_id' => $this->order_id,
            'service_plan_id' => $this->service_plan_id,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ];

        if ($this->relationLoaded('order')) {
            $structure['order'] = new OrderResource($this->order);
        }

        if ($this->relationLoaded('servicePlan')) {
            $structure['service_plan'] = new ServicePlanResource($this->servicePlan);
        }

        return $structure;
    }
}
