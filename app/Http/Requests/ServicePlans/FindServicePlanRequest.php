<?php

namespace App\Http\Requests\ServicePlans;

use Illuminate\Foundation\Http\FormRequest;

class FindServicePlanRequest extends FormRequest
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
            'id' => ['required', 'string', 'exists:service_plans,id']
        ];
    }
}
