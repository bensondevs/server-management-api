<?php

namespace App\Http\Requests\Containers\Samba\Directory;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Container;

use App\Rules\SambaDirectoryName;

class CreateSambaDirectoryRequest extends FormRequest
{
    private $serverContainer;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

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
            'directory_name' => [
                'required', 
                'string', 
                new SambaDirectoryName($container)
            ],
        ];
    }
}
