<?php

namespace App\Http\Requests\Containers\Samba\Group\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ SambaUser, SambaGroup, SambaGroupUser };

class FindSambaGroupUserRequest extends FormRequest
{
    private $user;
    private $group;
    private $groupUser;

    public function getSambaUser()
    {
        if ($this->user) return $this->user;

        $id = $this->input('samba_user_id');
        return $this->user = SambaUser::findOrFail($id);
    }

    public function getSambaGroup()
    {
        if ($this->group) return $this->group;

        $id = $this->input('samba_group_id');
        return $this->group = SambaGroup::findOrFail($id);
    }

    public function getSambaGroupUser()
    {
        if ($this->groupUser) return $this->groupUser;

        if ($this->has('samba_group_user_id')) {
            $id = $this->input('samba_group_user_id');
            return $this->groupUser = SambaGroupUser::findOrFail($id);
        }

        $userId = $this->input('samba_user_id');
        $groupId = $this->input('samba_group_id');
        return $this->groupUser = SambaGroupUser::where('samba_user_id', $userId)
            ->where('samba_group_id', $groupId)
            ->firstOrFail();
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
