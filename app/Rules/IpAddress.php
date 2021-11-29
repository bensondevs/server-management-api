<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IpAddress implements Rule
{
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'We don\'t know why, we think that this is not correct IP Address';
    }

    public function hasFourDigits($ipAddress)
    {
        $digits = explode('.', $ipAddress);

        if (! $digits) return false;

        return (count($digits) == 4);
    }

    public function eachDigitIsNumeric($ipAddress)
    {
        $digits = explode('.', $ipAddress);

        foreach ($digits as $key => $digit) {
            if (! is_numeric($digit)) {
                $this->message = 'Digit ' . $key . ' is not numeric.';
                return false;
            }
        }

        return true;
    }

    public function isNotExceedingPossibleDigitAmount($ipAddress)
    {
        $digits = explode('.', $ipAddress);

        foreach ($digits as $digit) {
            $digit = (int) $digit;

            if ($digit > 255 || $digit < 0) {
                $this->message = 'There is no IP digit that has value of ' . $digit;
                return false;
            }
        }

        return true;
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
        $ipAddress = $value;

        if (! $this->hasFourDigits($ipAddress)) {
            $this->message = 'IP Address needs to have 4 digits with "." as separator';
            return false;
        }

        if (! $this->eachDigitIsNumeric($ipAddress)) {
            return false;
        }

        if (! $this->isNotExceedingPossibleDigitAmount($ipAddress)) {
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
