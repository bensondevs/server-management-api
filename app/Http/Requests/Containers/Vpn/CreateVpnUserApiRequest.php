<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\{ VpnUser, Container };
use App\Rules\{ UniqueWithCondition, SubnetIp };

class CreateVpnUserApiRequest extends FormRequest
{
    /**
     * Model Server Container variable container
     * 
     * @var \App\Model\Container 
     */
    private $serverContainer;

    /**
     * Get the context server container
     * 
     * @return \App\Models\Container
     */
    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        if (! $container = $this->route('container')) {
            $user = $this->user();
            if (! $container = Container::ownedBy($user)->current()->first()) {
                $container = Container::ownedBy($user)->active()->firstOrFail();
            }
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
            'username' => ['required', 'string', new UniqueWithCondition(
                new VpnUser, [
                    [
                        'column' => 'container_id', 
                        'operator' => '=',
                        'value' => $this->getServerContainer()->id,
                    ]
                ])
            ],
            'subnet_ip' => ['required', 'string', new SubnetIp],
        ];
    }
}