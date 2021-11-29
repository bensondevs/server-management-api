<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\VpnUser;
use App\Models\Container;

class RevokeVpnUserApiRequest extends FormRequest
{
    private $vpnUser;
    private $serverContainer;

    public function getVpnUser()
    {
        if ($this->vpnUser) {
            return $this->vpnUser;
        }

        if ($this->has('vpn_user_id')) {
            $id = $this->input('vpn_user_id') ?: $this->input('id');
            $vpnUser = VpnUser::findOrFail($id);
            return $this->vpnUser = $vpnUser;
        }

        if ($this->has('username')) {
            $username = $this->input('username');
            $vpnUser = VpnUser::findInContainer($container, $username);

            return $this->vpnUser = $vpnUser;
        }

        return abort(422, 'No VPN User data specified.');
    }

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $user = $this->user();
        if (! $container = Container::ownedBy($user)->current()->first()) {
            $container = Container::ownedBy($user)->active()->firstOrFail();
        }

        return $this->serverContainer = $container;
    }

    protected function prepareForValidation()
    {
        $this->getServerContainer();
        $this->getVpnUser();
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
