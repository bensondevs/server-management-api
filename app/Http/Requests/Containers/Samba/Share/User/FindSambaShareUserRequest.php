<?php

namespace App\Http\Requests\Containers\Samba\Share\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ SambaUser, SambaShare, SambaShareUser };

class FindSambaShareUserRequest extends FormRequest
{
    private $user;
    private $share;
    private $shareUser;

    public function getSambaUser()
    {
        if ($this->user) return $this->user;

        $id = $this->input('samba_user_id');
        return $this->user = SambaUser::findOrFail($id);
    }

    public function getSambaShare()
    {
        if ($this->share) return $this->share;

        $id = $this->input('samba_share_id');
        return $this->share = SambaShare::findOrFail($id);
    }

    public function getSambaShareUser()
    {
        if ($this->shareUser) return $this->shareUser;

        if ($this->has('samba_share_group_id')) {
            $id = $this->input('samba_share_user_id');
            return $this->shareUser = SambaShareUser::findOrFail($id);
        }

        $userId = $this->input('samba_user_id');
        $shareId = $this->input('samba_share_id');
        return $this->shareUser = SambaShareUser::where('samba_user_id', $userId)
            ->where('samba_share_id', $shareId)
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
