<?php

namespace App\Http\Requests\Containers\Samba\Share\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ SambaShare, SambaUser, SambaShareUser };

class RemoveSambaShareUserRequest extends FormRequest
{
    private $shareUser;

    public function getShareUser()
    {
        if ($this->shareUser) return $this->shareUser;

        $id = $this->input('id') ?: $this->input('share_user_id');
        return $this->shareUser = SambaShareUser::findOrFail($id);
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
