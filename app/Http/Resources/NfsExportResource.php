<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NfsExportResource extends JsonResource
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
            'nfs_folder_id' => $this->nfs_folder_id,

            'ip_address' => $this->ip_address,
            'permissions' => $this->permissions_array,
        ];

        if ($this->relationLoaded('container')) {
            $structure['container'] = $this->container;
        }

        if ($this->relationLoaded('folder')) {
            $structure['folder'] = $this->folder;
        }

        return $structure;
    }
}
