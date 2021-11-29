<?php

namespace App\Http\Requests\Containers\Samba\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaUser;

class DeleteSambaUserRequest extends FormRequest
{
    private $sambaUser;
    private $serverContainer;

    public function getSambaUser()
    {
        if ($this->sambaUser) return $this->sambaUser;

        $id = $this->input('samba_user_id') ?: $this->input('id');
        return $this->sambaUser = SambaUser::findOrFail($id);
    }

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $sambaUser = $this->getSambaUser();
        return $this->serverContainer = $sambaUser->container;
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
