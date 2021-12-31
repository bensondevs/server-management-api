<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\{ Container, SambaUser, SambaGroup };

class SambaUniqueUser implements Rule
{
    /**
     * Target container model container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Error message container
     * 
     * @var string|null
     */
    private $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->serverContainer = $container;
        $this->errorMessage = 'Unknown error occured.';
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
        if (SambaUser::isExistsInContainer($container, $username)) {
            $this->errorMessage = 'This username is already exists.';
            return false;
        }

        // Check if there is username within a container
        if (SambaGroup::isExistsInContainer($container, $username)) {
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
