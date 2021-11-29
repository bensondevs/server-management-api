<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{Container, VpnUser};

use App\Rules\SubnetIp;

class ChangeVpnUserSubnetIpRequest extends FormRequest
{
    private $serverContainer;
    private $vpnUser;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $id = $this->input('container_id');
        return $this->serverContainer = Container::findOrFail($id);
    }

    public function getVpnUser()
    {
        if ($this->vpnUser) return $this->vpnUser;

        if ($username = $this->input('username')) {
            $container = $this->getServerContainer();
            $vpnUser = VpnUser::findInContainer($container, $username);
            return $this->vpnUser = $vpnUser;
        }

        $id = $this->input('vpn_user_id');
        return $this->vpnUser = VpnUser::findOrFail($id);
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
            'subnet_ip' => ['required', 'string', new SubnetIp],
        ];
    }
}
