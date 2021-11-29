<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Container;
use App\Models\SambaUser;
use App\Models\SambaGroup;

class SambaUniqueUser implements Rule
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
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $username
     * @return bool
     */
    public function passes($attribute, $username)
    {
        $container = $this->serverContainer;

        // Check if username is exist or not
        if (SambaUser::isExistsInContainer($username, $container)) {
            $this->errorMessage = 'This username is already exists.';
            return false;
        }

        // Check if there is username within a container
        if (SambaGroup::isExistsInContainer($username, $container)) {
            $this->errorMessage = 'There is group with this name. Cannot use this username.';
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
