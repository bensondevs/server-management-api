<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\PaymentExistInVendor;

class PaymentCallbackRequest extends FormRequest
{
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
            'order_reference' => ['required', 'string', 'exists:orders,id'],
            'payment_reference' => ['required', 'string'],
        ];
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
