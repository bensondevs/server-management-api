<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SaveRabbitMQOptionRequest extends FormRequest
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
            'config_rabbitmq_connection' => ['required'],
            'autosync_rabbitmq' => ['string'],
        ];
    }

    public function onlyInRules()
    {
        $keys = array_keys($this->rules());
        $requests = [];
        foreach ($keys as $key)
            $requests[$key] = request()->input($key);

        return $requests;
    }
}
