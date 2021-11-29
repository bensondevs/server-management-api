<?php

namespace App\Http\Requests\Containers\Nginx;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Container;

class CreateLocationRequest extends FormRequest
{
    private $serverContainer;

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
        $container = $this->getServerContainer();
        return Gate::allows('create-location-container-nginx', $container);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
