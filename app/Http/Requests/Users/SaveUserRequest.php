<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\VatNumber;
use App\Rules\HasNumerical;
use App\Rules\HasLowerCase;
use App\Rules\HasUpperCase;
use App\Rules\HasSpecialCharacter;

class SaveUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'alpha'],
            'last_name' => ['required', 'string', 'alpha'],

            'country' => ['required', 'string', 'alpha'],
            'address' => ['required', 'string', 'alpha_dash'],

            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => [
                'required', 
                'string', 
                new HasNumerical(),
                new HasLowerCase(),
                new HasUpperCase(),
                new HasSpecialCharacter()
            ],
            'confirm_password' => ['required', 'same:password'],
        ];

        if (request()->middle_name)
            $rules['middle_name'] = ['string', 'alpha'];

        if (request()->company_name)
            $rules['company_name'] = ['string', 'alpha'];

        if (request()->vat_number)  
            $rules['vat_number'] = ['alpha_num', new VatNumber()];

        if (request()->newsletter)
            $rules['newsletter'] = ['boolean'];

        if (request()->notes)
            $rules['notes'] = ['string'];

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
