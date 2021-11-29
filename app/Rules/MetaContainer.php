<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Datacenter;

class MetaContainer implements Rule
{
    public $message;

    private $metaContainer;

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
        // Check if format is correct
        if (! $metaContainer = json_decode($value, true)) {
            $this->message = 'Meta container value has incorrect format';
            return false;
        }

        // Check hostname
        if (! isset($metaContainer['hostname'])) {
            $this->message = 'Meta container needs hostname value';
            return false;
        }

        // If Datacenter Exist
        if (isset($metaContainer['datacenter_id'])) {
            if (! Datacenter::find($metaContainer['datacenter_id'])) {
                $this->message = 'Invalid Datacenter ID';
                return false;
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
