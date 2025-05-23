<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'user_id' => $this->user_id,

            'subscribeable_type' => $this->subscribeable_type,
            'subscribeable_id' => $this->subscribeable_type,

            'subscriber_type' => $this->subscriber_type,
            'subscriber_id' => $this->subscriber_id,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'start' => $this->start,
            'human_start' => $this->human_start,
            'end' => $this->end,
            'human_end' => $this->human_end,
        ];

        if ($this->relationLoaded('user')) {
            $user = new UserResource($this->user);
            $structure['user'] = $user;
        }

        if ($this->relationLoaded('subscriber')) {
            $subscriber = $this->subscriber;
            $structure['subscriber'] = $subscriber;
        }

        if ($this->relationLoaded('subscribeable')) {
            $subscribeable = $this->subscribeable;
            $structure['subscribeable'] = $subscribeable;
        }

        return $structure;
    }
}
