<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchHashedPassword implements Rule
{
    protected $hashedPassword;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return hashCheck($value, $this->hashedPassword);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The password you inserted does not match the record. If you found trouble to remember please follow "Forgot Password"';
    }
}
