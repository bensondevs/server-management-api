<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SaveSiteSettingsRequest extends FormRequest
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
            'site_title' => ['required', 'string'],
            'site_name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
