<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SambaDirectoryResource extends JsonResource
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
            'directory_name' => $this->directory_name,
        ];

        if ($this->relationLoaded('container')) {
            $container = $this->container;
            $structure['container'] = new ContainerResource($container);
        }

        if ($this->relationLoaded('shares')) {
            $shares = $this->shares;
            $structure['shares'] = SambaShareResource::collection($shares);
            $structure['shares_count'] = count($shares);
        }

        if (! is_null($this->shares_count)) {
            $structure['shares_count'] = $this->shares_count;
        }

        return $structure;
    }
}
