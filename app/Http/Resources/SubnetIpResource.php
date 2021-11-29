<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class SubnetIpResource extends JsonResource
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
            'is_forbidden' => $this->is_forbidden,
            'ip_address' => $this->ip_address,
            'assigned_user_id' => $this->assigned_user_id,
            'comment' => $this->comment ? $this->comment : '-',
        ];

        if ($this->relationLoaded('assignedUser')) {
            if ($user = $this->assignedUser) {
                $user->full_name = $user->full_name;
                $structure['user'] = $user;
            }
        }

        return $structure;
    }
}