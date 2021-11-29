<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulateServersRequest extends FormRequest
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
        return $this->collectOptions();
    }
}
