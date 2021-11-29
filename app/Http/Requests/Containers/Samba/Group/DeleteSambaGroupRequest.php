<?php

namespace App\Http\Requests\Containers\Samba\Group;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ Container, SambaGroup };

class DeleteSambaGroupRequest extends FormRequest
{
    private $serverContainer;
    private $sambaGroup;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($id = $this->input('container_id')) {
            return $this->serverContainer = Container::findOrFail($id);
        }

        $sambaGroup = $this->getSambaGroup();
        $container = $sambaGroup->container;
        return $this->serverContainer = $container;
    }

    public function getSambaGroup()
    {
        if ($this->sambaGroup) return $this->sambaGroup;

        $id = $this->input('samba_group_id');
        return $this->sambaGroup = SambaGroup::findOrFail($id);
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
