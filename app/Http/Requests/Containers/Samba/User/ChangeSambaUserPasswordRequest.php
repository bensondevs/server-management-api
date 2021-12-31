<?php

namespace App\Http\Requests\Containers\Samba\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SambaUser;

class ChangeSambaUserPasswordRequest extends FormRequest
{
    /**
     * Target samba user container
     * 
     * @var \App\Models\SambaUser|null
     */
    private $sambaUser;

    /**
     * Get samba user from route parameter binding of `user` or
     * supplied input of `samba_user_id`
     * 
     * @return \App\Models\SambaUser|abort 404
     */
    public function getSambaUser()
    {
        if ($this->sambaUser) return $this->sambaUser;

        if ($sambaUser = $this->route('user')) {
            return $this->sambaUser = $sambaUser;
        }

        $id = $this->input('samba_user_id');
        return $this->sambaUser = SambaUser::findOrFail($id);
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
        $sambaUser = $this->getSambaUser();
        return [
            'old_password' => ['required', 'string', 'in:' . $sambaUser->decrypted_password],
            'password' => ['required', 'string', 'min:5'],
            'confirm_password' => ['required', 'string', 'same:password'],
        ];
    }
}
