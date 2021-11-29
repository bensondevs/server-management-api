<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Container;
use App\Models\NfsFolder;

class DeleteContainerNfsFolderRequest extends FormRequest
{
    private $serverContainer;
    private $nfsFolder;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($container = $this->route('container')) {
            return $this->serverContainer = $container;
        }

        $folder = $this->getNfsFolder();
        $container = $folder->container;
        return $this->serverContainer = $container;
    }

    public function getNfsFolder()
    {
        if ($this->nfsFolder) return $this->nfsFolder;

        if ($folder = $this->route('folder')) {
            return $this->nfsFolder = $folder;
        }

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
        return [
            //
        ];
    }
}
