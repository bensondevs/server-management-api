<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\HasNumerical;
use App\Rules\HasLowerCase;
use App\Rules\HasUpperCase;
use App\Rules\HasSpecialCharacter;

class RegisterRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        //
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

            'country' => ['required', 'string'],
            'address' => ['required', 'string'],

            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => [
                'required', 
                'string',
                'min:8',
                new HasNumerical(),
                new HasLowerCase(),
                new HasUpperCase(),
                new HasSpecialCharacter()
            ],
            'confirm_password' => ['required', 'same:password'],
        ];

        /*
            Optional Properties
        */
        if ($this->middle_name) {
            $rules['middle_name'] = ['string'];
        }

        if ($this->company_name) {
            $rules['company_name'] = ['string'];
        }

        if ($this->newsletter) {
            $rules['newsletter'] = ['boolean'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Please fill "First Name" field.',
            'first_name.string' => 'Please fill "First Name" field with text or string value.',

            'middle_name.string' => 'Please fill "Middle Name" field with text or string value.',

            'last_name.required' => 'Please fill "Last Name" field.',
            'last_name.string' => 'Please fill "Last Name" field with text or string value.',

            'country.required' => 'Please select value for "Country" option.',
            'country.string' => 'Please don\'t fill this field with other value outside the available options.',

            'username.required' => 'Please fill "Username" field.',
            'username.string' => 'Please fill "Username" field with alphanumeric value',
            'username.unique' => 'Sorry, this username has been taken, please try another',

            'email.required' => 'Please fill "Email" field.',
            'email.string' => 'Please fill "Email" field with only text or string value.',
            'email.unqiue' => 'Sorry, this email has been registered by other user, if this is a problem, please contact us.',

            'password.required' => 'Please fill "Password" field.',
            'password.string' => 'Please fill "Password" only with alphanumeric and symbols available in your keyboard.',
            'password.min' => 'Sorry, password needs to have atleast 8 characters',

            'confirm_password.required' => 'Please fill "Confirm Password" field.',
            'confirm_password.same' => 'Password Confirmation is not match, please try again.',

            'company_name.string' => 'Please fill "Company" value with text or string value',

            'newsletter.boolean' => 'Please fill "Newsletter" value with boolean',
        ];
    }
}
