<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SambaShareResource extends JsonResource
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
            'share_name' => $this->share_name,
        ];

        if ($this->relationLoaded('container')) {
            $container = $this->container;
            $structure['container'] = new ContainerResource($container);
        }

        if ($this->relationLoaded('groups')) {
            $groups = $this->groups;
            $structure['groups'] = SambaGroupResource::collection($groups);
            $structure['groups_count'] = count($groups);
        }

        if (! is_null($this->groups_count)) {
            $structure['groups_count'] = $this->groups_count;
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
