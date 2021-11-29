<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
        ];

        if (request()->middle_name)
            $rules['middle_name'] = ['required', 'string'];

        if (request()->company_name)
            $rules['company_name'] = ['required', 'string'];

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
