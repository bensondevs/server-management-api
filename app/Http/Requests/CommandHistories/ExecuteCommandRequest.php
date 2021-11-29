<?php

namespace App\Http\Requests\CommandHistories;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\JsonArray;

class ExecuteCommandRequest extends FormRequest
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
            'queue' => ['required'],
            'commands' => ['required', new JsonArray()],
        ];
    }

    public function onlyInRequest()
    {
        return $this->only(array_keys($this->rules()));
    }
}
