<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MediumPassword implements Rule
{
    /*
    |--------------------------------------------------------------------------
    | Medium Password Rule
    |--------------------------------------------------------------------------
    |
    | This rule validate input request for password with strength of MEDIUM.
    | Medium security password means, user must input:
    | 
    | - Minimum 8 characters
    | - Conists of atleast 1 upper case letter
    | - Consist of atleast 1 numeric character (eg: 1234567890)
    | - Consist of atleast 1 special symbols (eg: !@#$%^&*())
    |
    */

    /**
     * Error message container
     * 
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'Unknown error of validation.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $password
     * @return bool
     */
    public function passes($attribute, $password)
    {
        // Ensure input has atleast 8 characters
        if (strlen($password) < 8) {
            $this->message = 'Password too short, it must be atleast 8 characters';
            return false;
        }

        // Ensure input has atleast one upper case letter
        if (! string_has_uppercase($password)) {
            $this->message = 'Password doesn\'t have uppercase character. Please add atleast one.';
            return false;
        }

        // Ensure input has atleast one numeric
        if (! string_has_numeric($password)) {
            $this->message = 'Password doesn\'t have numeric character. Please add atleast one.';
            return false;
        }

        // Ensure input has atleast one special symbol
        if (! string_has_special_char($password)) {
            $this->message = 'Password doesn\'t have special characters (eg: "!@#$%^&*()")';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
