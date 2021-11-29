<?php

namespace App\Http\Requests\Datacenters;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

class SaveDatacenterRequest extends FormRequest
{
    use InputRules;

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
        $this->setRules([
            'region_id' => ['required', 'string', 'exists:regions,id'],
            'datacenter_name' => ['required', 'string'],
            'client_datacenter_name' => ['string'],
            'location' => ['required', 'string'],
            'status' => ['string'],
        ]);

        return $this->returnRules();
    }
}
