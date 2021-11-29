<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\User;

class FindUserRequest extends FormRequest
{
    private $user;

    public function getUser()
    {
        if ($this->user) return $this->user;

        $id = $this->input('user_id') ?: $this->input('id');
        return $this->user = User::findOrFail($id);
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
            'id' => ['required', 'string'],
        ];
    }
}
