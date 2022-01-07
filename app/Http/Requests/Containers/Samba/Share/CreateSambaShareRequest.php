<?php

namespace App\Http\Requests\Containers\Samba\Share;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AllowedSambaName;

class CreateSambaShareRequest extends FormRequest
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
            'directory_name' => [
                'required', 
                'string', 
                new AllowedSambaName()
            ],
        ];
    }
}
