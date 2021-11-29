<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Order;

class PayOrderRequest extends FormRequest
{
    private $order;

    public function getOrder()
    {
        if ($this->order) return $this->order;

        $id = $this->input('id') ?: $this->input('order_id');
        return $this->order = Order::findOrFail($id);
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
        $rules = [
            'billing_city' => ['required', 'string', 'alpha'],
            'billing_country' => ['required', 'string', 'alpha'],
            'billing_line1' => ['required', 'string'],
            'billing_postcode' => ['required', 'string', 'alpha_num'],
            'billing_state' => ['required', 'string'],
        ];

        if ($this->input('billing_line2')) {
            $rules['billing_line2'] = ['string'];
        }

        if ($this->input('billing_line3')) {
            $rules['billing_line3'] = ['string'];
        }

        return $rules;
    }
}
