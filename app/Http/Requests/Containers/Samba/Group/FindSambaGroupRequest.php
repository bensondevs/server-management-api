<?php

namespace App\Http\Requests\Containers\Samba\Group;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaGroup;

class FindSambaGroupRequest extends FormRequest
{
    private $group;

    public function getSambaGroup()
    {
        if ($this->group) return $this->group;

        $id = $this->input('samba_group_id');
        return $this->group = SambaGroup::findOrFail($id);
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
