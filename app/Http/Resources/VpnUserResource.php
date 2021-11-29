<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VpnUserResource extends JsonResource
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
            'container_id' => $this->container_id,
            'username' => $this->username,
            'config_content' => $this->config_content,
            'vpn_subnet' => $this->vpn_subnet,
            'assigned_subnet_ip' => $this->assigned_subnet_ip,
        ];

        if ($this->relationLoaded('container')) {
            $container = new ContainerResource($this->container);
            $structure['container'] = $container;
        }

        return $structure;
    }
}
