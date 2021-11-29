<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class DatacenterResource extends JsonResource
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
            'datacenter_name' => $this->datacenter_name,
            'client_datacenter_name' => $this->client_datacenter_name,
            'location' => $this->location,
            'status' => $this->status,
            'status_description' => $this->status_description,
        ];

        if ($this->relationLoaded('region')) {
            $structure['region'] = $this->region;
            $structure['region_name'] = $this->region->region_name;
        }

        return $structure;
    }
}