<?php

namespace App\Http\Requests\Payments\Seb;

use Illuminate\Foundation\Http\FormRequest;

class SebPaymentRequest extends FormRequest
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
            'billing_city' => ['nullable', 'string'],
            'billing_country' => ['nullable', 'string'],
            'billing_line1' => ['nullable', 'string'],
            'billing_line2' => ['nullable', 'string'],
            'billing_line3' => ['nullable', 'string'],
            'billing_postcode' => ['nullable', 'numeric'],
            'billing_state' => ['nullable', 'string'],
        ];
    }
}