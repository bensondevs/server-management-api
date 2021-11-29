<?php

namespace App\Http\Requests\Containers\Samba\Group\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{
    Container,
    SambaUser,
    SambaGroup,
    SambaGroupUser
};

class RemoveSambaGroupUserRequest extends FormRequest
{
    private $serverContainer;
    private $sambaGroupUser;
    private $sambaUser;
    private $sambaGroup;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $groupUser = $this->getSambaGroupUser();
        $container = $groupUser->container;
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

    public function getSambaGroupUser()
    {
        if ($this->sambaGroupUser) return $this->sambaGroupUser;

        if ($id = $this->input('samba_group_user_id')) {
            $sambaGroupUser = SambaGroupUser::findOrFail($id);
            return $this->sambaGroupUser = $sambaGroupUser;
        }

        $sambaUser = $this->getSambaUser();
        $sambaGroup = $this->getSambaGroup();
        $sambaGroupUser = SambaGroupUser::where('samba_group_id', $sambaGroup->id)
            ->where('samba_user_id', $sambaUser->id)
            ->firstOrFail();

        return $this->sambaGroupUser = $sambaGroupUser;
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
