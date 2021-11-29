<?php

namespace App\Http\Requests\Subnets;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulateSubnetsRequest extends FormRequest
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
        if ($datacenter = $this->route('datacenter')) {
            $this->addWith('datacenter');
            $this->addWhere([
                'column' => 'datacenter_id',
                'value' => $datacenter->id,
            ]);
        }

        return $this->collectOptions();
    }
}
