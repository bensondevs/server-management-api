<?php

namespace App\Http\Requests\Containers\Samba\Group;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Container;

use App\Rules\SambaUniqueGroup;

class CreateSambaGroupRequest extends FormRequest
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
            'group_name' => [
                'required', 
                'string', 
                new SambaUniqueGroup($container)
            ],
        ];
    }
}