<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\UniqueWithCondition;

use App\Models\VpnUser;
use App\Models\Container;

class RevokeContainerVpnUserRequest extends FormRequest
{
    private $vpnUser;
    private $serverContainer;

    public function getVpnUser()
    {
        if ($this->vpnUser) return $this->vpnUser;

        $username = $this->input('username');
        $container = $this->getServerContainer();
        $vpnUser = VpnUser::findInContainer($container, $username);

        return $this->vpnUser = $vpnUser;
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $vpnUser = $this->getVpnUser();
        $container = $vpnUser->container;
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
            'username' => ['required', 'string', 'exists:vpn_users'],
        ];
    }
}
