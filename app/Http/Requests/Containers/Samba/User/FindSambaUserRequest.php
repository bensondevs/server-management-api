<?php

namespace App\Http\Requests\Containers\Samba\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaUser;

class FindSambaUserRequest extends FormRequest
{
    private $user;

    public function getSambaUser()
    {
        if ($this->user) return $this->user;

        $id = $this->input('samba_user_id');
        return $this->user = SambaUser::findOrFail($id);
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
