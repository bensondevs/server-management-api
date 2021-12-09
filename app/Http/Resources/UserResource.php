<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

            'account_type' => $this->account_type,
            'account_type_description' => $this->account_type_description,

            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,

            'country' => $this->country,
            'address' => $this->address,
            'vat_number' => $this->var_number,

            'username' => $this->username,
            'email' => $this->email,
            
            'company_name' => $this->company_name,
            'subscribe_newsletter' => $this->subscribe_newsletter,
        ];
    }
}