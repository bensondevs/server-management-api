<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\UniqueWithCondition;

use App\Traits\InputRules;

use App\Models\Container;
use App\Models\NfsFolder;

class CreateContainerNfsExportRequest extends FormRequest
{
    use InputRules;

    private $serverContainer;
    private $nfsFolder;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $id = $this->input('id') ?: $this->input('container_id');
        return $this->serverContainer = Container::findOrFail($id);
    }

    public function getNfsFolder()
    {
        if ($this->nfsFolder) return $this->nfsFolder;

        $id = $this->input('nfs_folder_id');
        return $this->nfsFolder = NfsFolder::findOrFail($id);
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
        $this->setRules([
            'nfs_folder_id' => ['required', 'string'],
            'ip_address' => ['required', 'string'],
            'permissions' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
