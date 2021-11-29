<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SambaUserResource extends JsonResource
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
        ];

        if ($this->relationLoaded('container')) {
            $structure['container'] = $this->container;
        }

        if ($this->relationLoaded('shares')) {
            $shares = $this->shares;
            $structure['shares'] = SambaShareResource::collection($shares);
        }

        if ($this->relationLoaded('groups')) {
            $groups = $this->groups;
            $structure['groups'] = SambaGroupResource::collection($groups);
        }

        return $structure;
    }
}
