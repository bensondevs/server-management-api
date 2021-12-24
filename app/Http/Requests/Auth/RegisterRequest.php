<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\MediumPassword;
use App\Enums\User\UserAccountType as AccountType;

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

    /**
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'subscribe_newsletter' => strtobool($this->input('subscribe_newsletter')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_type' => [
                'required', 
                'numeric', 
                'min:' . AccountType::Personal, 
                'max:' . AccountType::Business
            ],

            'first_name' => ['required', 'string'],
            'middle_name' => ['nullable', 'string'],
            'last_name' => ['required', 'string'],

            'country' => ['required', 'string'],
            'address' => ['required', 'string'],
            'vat_number' => ['required', 'string'],

            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'string', new MediumPassword],
            'confirm_password' => ['required', 'string', 'same:password'],
            
            'company_name' => [
                'required_if:account_type,==,' . AccountType::Business, 
                'string'
            ],
            'subscribe_newsletter' => ['required', 'boolean'],
        ];
    }

    /**
     * Validation error response messages
     * 
     * @return array
     */
    /*public function messages()
    {
        return [
            //
        ];
    }*/
}
