<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NginxLocationResource extends JsonResource
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
            'nginx_location' => $this->nginx_location,
            'nginx_config' => $this->nginx_config,
        ];

        if ($this->relationLoaded('container')) {
            $container = new ContainerResource($this->container);
            $structure['container'] = $container;
        }

        return $structure;
    }
}
