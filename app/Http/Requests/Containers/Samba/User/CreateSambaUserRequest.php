<?php

namespace App\Http\Requests\Containers\Samba\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Container;
use App\Rules\SambaUniqueUser;

class CreateSambaUserRequest extends FormRequest
{
    /**
     * Target container model container
     * 
     * @var  \App\Models\Container
     */
    private $serverContainer;

    /**
     * Get server container from route parameter binding
     * or supplied input of `container_id`
     * 
     * @return \App\Models\Container|abort 404
     */
    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($container = $this->route('container')) {
            return $this->serverContainer = $container;
        }

        $id = $this->input('container_id');
        return $this->serverContainer = Container::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $container = $this->getServerContainer();
        return [
            'username' => ['required', 'string', new SambaUniqueUser($container)],
            'password' => ['required', 'string'],
        ];
    }
}
