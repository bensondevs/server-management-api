<?php

namespace App\Http\Requests\Containers\Vpn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Rules\UniqueWithCondition;

use App\Models\Container;
use App\Models\VpnUser;

class CreateContainerVpnUserRequest extends FormRequest
{
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
                        'value' => $this->route('container')->id,
                    ]
                ])
            ],
        ];
    }
}
