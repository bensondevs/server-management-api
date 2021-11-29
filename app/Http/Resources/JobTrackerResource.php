<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobTrackerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'job_class' => $this->job_class,
            'status' => $this->status,
            'status_description' => $this->status_description, 
        ];
    }
}
