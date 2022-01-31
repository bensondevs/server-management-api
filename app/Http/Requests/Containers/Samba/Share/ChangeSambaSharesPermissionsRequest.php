<?php

namespace App\Http\Requests\Containers\Samba\Share;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaShare;

class ChangeSambaSharesPermissionsRequest extends FormRequest
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
            'samba_share_ids' => ['required', 'array'],
            'permissions' => ['required', 'array'],
        ];
    }
}
