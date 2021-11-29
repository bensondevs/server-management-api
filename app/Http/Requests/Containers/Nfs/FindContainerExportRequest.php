<?php

namespace App\Http\Requests\Containers\Nfs;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\NfsExport;

class FindContainerExportRequest extends FormRequest
{
    private $nfsExport;

    public function getNfsExport()
    {
        if ($this->nfsExport) return $this->nfsExport;

        $id = $this->input('id') ?: $this->input('nfs_export_id');
        return $this->nfsExport = NfsExport::findOrFail($id);
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
