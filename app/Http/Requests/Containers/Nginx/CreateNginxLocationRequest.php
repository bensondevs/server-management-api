<?php

namespace App\Http\Requests\Containers\Nginx;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Container;
use App\Rules\UniqueNginxLocationName;

class CreateNginxLocationRequest extends FormRequest
{
    /**
     * Target container model container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * Get server container from route parameter
     * or request input of `id`
     * 
     * @return \App\Models\Container
     */
    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($container = $this->route('container')) {
            return $this->serverContainer = $container;
        }

        $id = $this->input('id');
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
            'nginx_location' => [
                'required', 
                'string',
                new UniqueNginxLocationName($container),
            ],
        ];
    }
}
