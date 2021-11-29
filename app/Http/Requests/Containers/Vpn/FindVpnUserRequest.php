<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\VpnUser;
use App\Models\Container;

class FindVpnUserRequest extends FormRequest
{
    private $vpnUser;

    public function getVpnUser()
    {
        if ($this->vpnUser) return $this->vpnUser;

        if ($this->has('username') && $this->has('container_id')) {
            $containerId = $this->input('container_id');
            $container = Container::findOrFail($containerId);
            $username = $this->input('username');

            $vpnUser = VpnUser::findInContainer($container, $username);
            return $this->vpnUser = $vpnUser;
        }

        if ($this->has('id') || $this->has('vpn_user_id')) {
            $id = $this->input('id') ?: $this->input('vpn_user_id');
            return $this->vpnUser = VpnUser::findOrFail($id);
        } 

        return abort(422, 'No input.');
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
