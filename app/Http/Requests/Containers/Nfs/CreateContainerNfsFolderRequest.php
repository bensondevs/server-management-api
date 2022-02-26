<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Container;

class CreateContainerNfsFolderRequest extends FormRequest
{
    /**
     * Server Co
     */
    private $serverContainer;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($container = $this->route('container')) {
            return $this->serverContainer = $this->route('container');
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
        return [
            'folder_name' => ['required', 'string'],
        ];
    }
}
