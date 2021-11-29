<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SambaGroupResource extends JsonResource
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
            'group_name' => $this->group_name,
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

        if ($this->relationLoaded('users')) {
            $users = $this->users;
            $structure['users'] = SambaUserResource::collection($users);
            $structure['users_count'] = count($users);
        }

        if (! is_null($this->users_count)) {
            $structure['users_count'] = $this->users_count;
        }

        return $structure;
    }
}
