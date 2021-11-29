<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class ServicePlanResource extends JsonResource
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
        return [
            'id' => $this->id,
            'plan_code' => $this->plan_code,
            'plan_name' => $this->plan_name,
            'currency' => $this->currency,
            'subscription_fee' => $this->subscription_fee,
            'duration' => $this->duration_in_days,
            'description' => $this->description,
        ];
    }
}
