<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SubnetIp implements Rule
{
    protected $message;

    protected $ipAddress;
    protected $netmask;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'We don\'t know why, we think that this is not correct Subnet Mask';
    }

    public function splitSlash($cidr)
    {
        $parts = explode('/', $cidr);

        if (count($parts) != 2) {
            $this->message = 'This is not correct CIDR / Subnet Mask Address';

            return false;
        }

        $this->ipAddress = $parts[0];
        $this->netmask = $parts[1];

        if ($this->netmask < 0) {
            $this->message = 'Netmask value cannot be less than 0!';

            return false;
        }

        return true;
    }

    public function isCorrectIpAddress($ipAddress)
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->message = 'This IP Address has IPv4 type, netmask value cannot be greater than 32';

            return $this->netmask <= 32;
        }

        if (! filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->message = 'This IP Address has IPv6 type, netmask value cannot be greater than 128';

            return $this->netmask <= 128;
        }

        return false;
    }

    public function isCorrectInteger($netmask)
    {
        if (! intval($this->netmask)) {
            $this->message = 'Netmask value should be integer';

            return false;
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
        if (! $this->splitSlash($value))
            return false;

        if (! $this->isCorrectInteger($this->netmask))
            return false;

        if (! $this->isCorrectIpAddress($this->ipAddress))
            return false;

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
