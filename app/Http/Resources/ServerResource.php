<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $datacenter = $this->datacenter;
        $region = ($datacenter) ? $datacenter->region : null;

        $regionName = ($region) ? $region->region_name : null;
        $datacenterName = ($datacenter) ? $datacenter->datacenter_name : null;
        $serverName = $regionName . '-' . $datacenterName . '-' . $this->server_name;

        return [
            'id' => $this->id,
            'server_name' => $serverName,
            'ip_address' => $this->ip_address,
            'datacenter' => $datacenter->datacenter_name,
            'total_containers' => $this->containers_count,
            'status' => $this->status,
        ];
    }
}
