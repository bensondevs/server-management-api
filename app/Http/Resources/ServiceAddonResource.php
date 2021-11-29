<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAddonResource extends JsonResource
{
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
            'addon_name' => $this->addon_name,
            'addon_type' => strtoupper($this->addon_type),
            'currency' => strtoupper($this->currency),
            'addon_fee' => $this->addon_fee,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'description' => $this->description,
        ];
    }
}
