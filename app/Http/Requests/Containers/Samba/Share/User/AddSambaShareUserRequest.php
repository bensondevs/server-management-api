<?php

namespace App\Http\Requests\Containers\Samba\Share\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ Container, SambaShare };

class AddSambaShareUserRequest extends FormRequest
{
    private $serverContainer;
    private $sambaShare;
    private $sambaUser;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if ($id = $this->input('container_id')) {
            $container = Container::findOrFail($id);
            return $this->serverContainer = $container;
        }

        if ($sambaShare = $this->sambaShare) {
            $this->serverContainer = $sambaShare->container;
            return $this->serverContainer;
        }

        if ($sambaUser = $this->sambaUser) {
            $this->serverContainer = $sambaUser->container;
            return $this->serverContainer;
        }

        $sambaUser = $this->getSambaUser();
        $container = $sambaUser->container;
        return $this->serverContainer = $container;
    }

    public function getSambaShare()
    {
        if ($this->sambaShare) return $this->sambaShare;

        $id = $this->input('samba_share_id');
        return $this->sambaShare = SambaShare::findOrFail($id);
    }

    public function getSambaUser()
    {
        if ($this->sambaUser) return $this->sambaUser;

        $id = $this->input('samba_user_id');
        return $this->sambaUser = SambaUser::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->getSambaShare();
        $this->getSambaUser();
        $this->getServerContainer();
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
