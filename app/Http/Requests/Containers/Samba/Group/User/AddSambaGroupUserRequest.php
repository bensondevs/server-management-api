<?php

namespace App\Http\Requests\Containers\Samba\Group\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ Container, SambaUser, SambaGroup };

class AddSambaGroupUserRequest extends FormRequest
{
    private $serverContainer;
    private $sambaUser;
    private $sambaGroup;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($id = $this->input('container_id')) {
            $container = Container::findOrFail($id);
            return $this->serverContainer = $container;
        }

        $sambaUser = $this->getSambaUser();
        $container = $sambaUser->container;
        return $this->serverContainer = $container;
    }

    public function getSambaUser()
    {
        if ($this->sambaUser) return $this->sambaUser;

        $id = $this->input('samba_user_id');
        return $this->sambaUser = SambaUser::findOrFail($id);
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
