<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\{ Container, SambaGroup };

class SambaUniqueGroup implements Rule
{
    private $serverContainer;

    private $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->serverContainer = $container;

        $this->errorMessage = 'Unknown error occur.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $groupName)
    {
        if (! $container = $this->serverContainer) {
            $this->errorMessage = 'Invalid container record.';
            return false;
        }

        if (SambaGroup::isExistsInContainer($groupName, $container)) {
            $this->errorMessage = 'The group with this name is already exists.';
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
        return $this->errorMessage;
    }
}
