<?php

namespace App\Http\Requests\Containers\Samba\Share\Group;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ SambaShare, SambaGroup, SambaShareGroup };

class FindSambaShareGroupRequest extends FormRequest
{
    private $share;
    private $group;
    private $shareGroup;

    public function getSambaShare()
    {
        if ($this->share) return $this->share;

        $id = $this->input('samba_share_id');
        return $this->share = SambaShare::findOrFail($id);
    }

    public function getSambaGroup()
    {
        if ($this->group) return $this->group;

        $id = $this->input('samba_group_id');
        return $this->group = SambaGroup::findOrFail($id);
    }

    public function getSambaShareGroup()
    {
        if ($this->shareGroup) return $this->shareGroup;

        if ($this->has('samba_share_group_id')) {
            $id = $this->input('samba_share_group_id');
            return $this->shareGroup = SambaShareGroup::findOrFail($id);
        }

        $shareId = $this->input('samba_share_id');
        $groupId = $this->input('samba_group_id');
        return $this->shareGroup = SambaShareGroup::where('samba_share_id', $shareId)
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
