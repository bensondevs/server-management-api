<?php

namespace App\Http\Requests\Containers\Samba\Share;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\SambaShare;

class DeleteSambaShareRequest extends FormRequest
{
    private $serverContainer;
    private $sambaShare;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $share = $this->getSambaShare();
        return $this->serverContainer = $share->container;
    }

    public function getSambaShare()
    {
        if ($this->sambaShare) return $this->sambaShare;

        $id = $this->input('id') ?: $this->input('share_id');
        return $this->sambaShare = SambaShare::findOrFail($id);
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
            'share_id' => ['required'],
        ];
    }
}
