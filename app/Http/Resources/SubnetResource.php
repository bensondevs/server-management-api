<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class SubnetResource extends JsonResource
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
            'status' => $this->status,
            'subnet_mask' => $this->subnet_mask,
        ];

        if ($this->total_available_ips !== null) {
            $structure['total_available_ips'] = $this->total_available_ips;
        }

        if ($this->ips_count !== null) {
            $structure['total_ips'] = $this->ips_count;
        }

        if ($this->relationLoaded('datacenter')) {
            $structure['datacenter'] = $this->datacenter;
            $structure['datacenter_name'] = $this->datacenter->datacenter_name;
        }

        return $structure;
    }
}
