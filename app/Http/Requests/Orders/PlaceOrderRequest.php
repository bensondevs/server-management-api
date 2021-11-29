<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;
use App\Rules\FloatValue;
use App\Rules\AmongStrings;
use App\Rules\MetaContainer;

class PlaceOrderRequest extends FormRequest
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
            'service_plan_id' => ['required', 'string', 'exists:service_plans,id'],
            'quantity' => ['required', 'integer'],
            'note' => ['string', 'alpha_dash'],

            'datacenter_id' => ['required', 'string', 'exists:datacenters,id'],
            'hostname' => ['required', 'string'],
            'disk_size' => ['required', 'numeric'],
            /*'meta_container' => [
                'required', 
                'string', 
                new JsonArray([
                    'datacenter_id',
                    'hostname',
                ]),
                new MetaContainer(),
            ],*/

            'addons_list' => ['string', new JsonArray()],
        ];
    }

    public function orderData()
    {
        $orderData = [
            'order_data' => [
                'customer_id' => $this->user()->id,
                'datacenter_id' => $this->input('datacenter_id'),
                'hostname' => $this->input('hostname'),
            ],
            'plan_data' => [
                'service_plan_id' => $this->input('service_plan_id'),
                'quantity' => $this->input('quantity'),
                'note' => $this->input('note'),
            ],
            'addons_list' => json_decode($this->input('addons_list'), true),
            'meta_container' => [
                'datacenter_id' => $this->input('datacenter_id'),
                'hostname' => $this->input('hostname'),
                'disk_size' => $this->input('disk_size'),
            ],
        ];

        return $orderData;
    }
}
