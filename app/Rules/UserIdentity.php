<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserIdentity implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $found = db('users')->where('username', $value)
            ->orWhere('email', $value)
            ->count();

        return ($found > 0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The identity is not match any username or email in records.';
    }
}
