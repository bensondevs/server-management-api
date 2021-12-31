<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchEncryptedPassword implements Rule
{
    /**
     * Encrypted password container
     * 
     * @var string
     */
    protected $encryptedPassword;

    /**
     * Create a new rule instance.
     *
     * @param  string  $encryptedPassword
     * @return void
     */
    public function __construct(string $encryptedPassword)
    {
        $this->encryptedPassword = $encryptedPassword;
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
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
