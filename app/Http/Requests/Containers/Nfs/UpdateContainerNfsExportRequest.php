<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

use App\Models\{NfsExport, Container};

class UpdateContainerNfsExportRequest extends FormRequest
{
    use InputRules;

    private $nfsExport;
    private $serverContainer;

    public function getNfsExport()
    {
        if ($this->nfsExport) return $this->nfsExport;

        $id = $this->input('id') ?: $this->input('nfs_export_id');
        return $this->nfsExport = NfsExport::findOrFail($id);
    }

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($this->has('container_id')) {
            $id = $this->input('container_id');
            return $this->serverContainer = Container::findOrFail($id);
        }

        $container = $this->getNfsExport()->container;
        return $this->serverContainer = $container;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasRole('administrator')) {
            return true;
        }

        return auth()->user()->id == $this->getServerContainer()->customer_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'permissions' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
