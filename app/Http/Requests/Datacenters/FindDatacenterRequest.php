<?php

namespace App\Http\Requests\Datacenters;

use Illuminate\Foundation\Http\FormRequest;

class FindDatacenterRequest extends FormRequest
{
    private $datacenter;

    public function getDatacenter()
    {
        return $this->datacenter = $this->datacenter ?:
            Datacenter::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'string'],
        ];
    }
}
