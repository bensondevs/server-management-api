<?php

namespace App\Http\Requests\Containers\Samba\Directory;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaDirectory;

class DeleteSambaDirectoryRequest extends FormRequest
{
    private $directory;

    public function getSambaDirectory()
    {
        if ($this->directory) return $this->directory;

        $id = $this->input('samba_directory_id');
        return $this->directory = SambaDirectory::findOrFail($id);
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
