<?php

namespace App\Http\Requests\ServicePlans;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\FloatValue;
use App\Rules\AmongStrings;
use App\Rules\UniqueValue;

class SaveServicePlanRequest extends FormRequest
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
            'plan_name' => [
                'required', 
                'string', 
                new UniqueValue(
                    'service_plans',
                    'plan_name',
                    $this->input('id')
                )
            ],
            'plan_code' => [
                'required',
                'string',
                'unique:service_plans,plan_code'
            ],
            'time_quantity' => [
                'required',
                'integer',
            ],
            'time_unit' => [
                'required',
                'string'
            ],
            'description' => [
                'required',
                'string',
            ]
        ];

        if ($this->input('currency')) {
            $rules['currency'] = ['required', 'string'];
        }

        if ($this->input('price')) {
            $rules['price'] = ['required', 'numeric'];
        }

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
