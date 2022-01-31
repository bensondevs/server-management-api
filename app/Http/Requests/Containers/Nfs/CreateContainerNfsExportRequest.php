<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\UniqueWithCondition;
use App\Traits\InputRules;
use App\Models\{ Container, NfsFolder };

class CreateContainerNfsExportRequest extends FormRequest
{
    use InputRules;

    /**
     * Target server container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Target NFS folder to be exported
     * 
     * @var \App\Models\NfsFolder|null
     */
    private $nfsFolder;

    /**
     * Get server container from route binding or input
     * 
     * @return \App\Models\Container|abort 404
     */
    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($this->route('container')) {
            return $this->serverContainer = $this->route('container');
        }

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
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if (is_array($this->input('permissions'))) {
            $permissions = $this->input('permissions');
            $this->merge(['permissions' => implode('', $permissions)]);
        }
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
