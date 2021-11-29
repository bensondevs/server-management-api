<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\IpAddress;

use App\Traits\InputRules;

class SaveServerRequest extends FormRequest
{
    use InputRules;
    
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
        $this->setRules([
            'server_name' => ['required', 'string'],
            'ip_address' => ['required', 'string', new IpAddress],
            'datacenter_id' => ['required', 'string', 'exists:datacenters,id'],
            'status' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
