<?php

namespace App\Http\Requests\Containers\Samba\Share;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaShare;

class FindSambaShareRequest extends FormRequest
{
    private $share;

    public function getSambaShare()
    {
        if ($this->share) return $this->share;

        $id = $this->input('samba_share_id');
        return $this->share = SambaShare::findOrFail($id);
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
