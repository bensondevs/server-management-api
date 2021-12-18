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
        $structure = [
            'id' => $this->id,

            'addon_name' => $this->addon_name,
            'addon_code' => $this->addon_code,
            'description' => $this->description,

            'status' => $this->status,
            'status_description' => $this->status_description,
            'duration_days' => $this->duration_days,

            'property_type' => $this->property_type,
            'property_type_description' => $this->property_type_description,
            'property_value' => $this->property_value,
        ];

        return $structure;
    }
}
