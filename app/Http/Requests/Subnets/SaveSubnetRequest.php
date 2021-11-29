<?php

namespace App\Http\Requests\Subnets;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\SubnetMask;
use App\Rules\AmongStrings;

use App\Traits\InputRules;

class SaveSubnetRequest extends FormRequest
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
            'datacenter_id' => ['required'],

            'status' => ['required', new AmongStrings(['active', 'inactive'])],
            'subnet_mask' => ['required', 'unique:subnets', new SubnetMask],
        ]);

        return $this->returnRules();
    }
}
