<?php

namespace App\Http\Requests\SubnetIps;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulateSubnetIpsRequest extends FormRequest
{
    use PopulateRequestOptions;

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
            //
        ];
    }

    public function options()
    {
        if ($subnet = $this->route('subnet')) {
            $this->addWhere([
                'column' => 'subnet_id',
                'value' => $subnet->id,
            ]);
        }

        $this->addWith('assignedUser');

        return $this->collectOptions();
    }
}
