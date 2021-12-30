<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\{ Container, NginxLocation };

class UniqueNginxLocationName implements Rule
{
    /**
     * Validation error message container
     * 
     * @var string|null
     */
    private $message;

    /**
     * Server container which contains the NGINX location
     * inside of it.
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Create a new rule instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        $this->serverContainer = $serverContainer;
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
        $container = $this->serverContainer;
        $exists = NginxLocation::where('container_id', $container->id)
            ->where('nginx_location', $value)
            ->exists();

        if ($exists) {
            $this->message = 'NGINX Location with name of ' . $value . ' is already exists';
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
