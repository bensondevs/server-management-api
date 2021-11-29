<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;
use App\Rules\FloatValue;
use App\Rules\AmongStrings;

use App\Enums\Order\OrderStatus;

class SaveOrderRequest extends FormRequest
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
        $rules = [
            'service_plan_id' => ['required', 'string', 'exists:service_plans,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_id' => ['required', 'string', 'exists:users,id'],
            'vat_size_percentage' => ['required', new FloatValue],

            // For meta container
            'datacenter_id' => ['required', 'string', 'exists:datacenters,id'],
            'hostname' => ['required', 'string'],

            'addons_list' => ['string', new JsonArray()],
        ];

        if ($this->input('status')) {
            $rules['status'] = ['required', 'numeric', 'min:' . OrderStatus::Unpaid, 'max:' . OrderStatus::Destroyed];
        }

        if ($this->input('note')) {
            $rules['note'] = ['required', 'string'];
        }

        return $rules;
    }

    public function onlyInRules()
    {
        $parameters = [
            'order_data' => [
                'customer_id' => $this->input('customer_id'),
                'vat_size_percentage' => $this->input('vat_size_percentage'),
                'status' => $this->input('status') ?: 'unpaid',
                'meta_container' => [
                    'datacenter_id' => $this->input('datacenter_id'),
                    'hostname' => $this->input('hostname'),
                ],
            ],
            'plan_data' => [
                'service_plan_id' => $this->input('service_plan_id'),
                'quantity' => $this->input('quantity'),
                'note' => $this->input('note'),
            ],
            'addons_list' => json_decode($this->input('addons_list'), true),
        ];

        return $parameters;
    }
}
