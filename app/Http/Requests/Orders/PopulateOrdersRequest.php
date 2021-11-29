<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\PopulateRequestOptions;

class PopulateOrdersRequest extends FormRequest
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
        $this->setWiths(['container', 'customer', 'payment', 'plan']);

        return $this->collectOptions();
    }
}
