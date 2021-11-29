<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NfsFolderResource extends JsonResource
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
            'folder_name' => $this->folder_name,
        ];

        if ($this->relationLoaded('container')) {
            $structure['container'] = $this->container;
        }

        if ($this->relationLoaded('exports')) {
            $structure['exports'] = $this->exports;
        }

        return $structure;
    }
}
