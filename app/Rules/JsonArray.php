<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class JsonArray implements Rule
{
    protected $message;

    protected $required;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($required = [])
    {
        $this->required = $required;
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
        $array = json_decode($value, true);

        if (! is_array($array)) {
            $this->message = 'This value is not correct JSON value, please check again.';

            return false;
        }

        if (count($this->required)) {
            foreach ($this->required as $key) {
                if (! isset($array[$key])) {
                    $this->message = 'Key value of ' . $key . ' is missing';

                    return false;
                }
            }
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
