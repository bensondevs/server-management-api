<?php

namespace App\Http\Requests\Containers\Samba\Share;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ Container, SambaDirectory };

use App\Rules\AllowedSambaName;

class CreateSambaShareRequest extends FormRequest
{
    private $directory;
    private $serverContainer;

    public function getSambaDirectory()
    {
        if ($this->directory) return $this->directory;

        $directoryName = $this->input('directory_name');
        return $this->directory = SambaDirectory::findByName($directoryName);
    }

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
        return [
            'directory_name' => [
                'required', 
                'string', 
                new AllowedSambaName()
            ],
        ];
    }
}
