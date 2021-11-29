<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRules;

use App\Models\NfsExport;

class DeleteContainerNfsExportRequest extends FormRequest
{
    use InputRules;

    private $serverContainer;
    private $nfsExport;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $nfsExport = $this->getNfsExport();
        $container = $nfsExport->container;
        return $this->serverContainer = $container;
    }

    public function getNfsExport()
    {
        if ($this->nfsExport) return $this->nfsExport;

        $id = $this->input('id') ?: $this->input('nfs_export_id');
        return $this->nfs = NfsExport::findOrFail($id);
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
