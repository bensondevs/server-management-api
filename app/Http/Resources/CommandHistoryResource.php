<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommandHistoryResource extends JsonResource
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
            'executor_name' => $this->executor->full_name,
            'queue_name' => $this->queue_name,
            'content' => $this->content,
            'executed_from' => $this->executed_from,
            'status' => ($this->execution_errors) ?
                'error' : 
                'success',
            'executed_at' => carbon($this->executed_at)->format('[H:i:s] M d, Y'),
        ];
    }
}
