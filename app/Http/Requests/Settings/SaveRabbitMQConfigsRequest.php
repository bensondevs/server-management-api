<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SaveRabbitMQConfigsRequest extends FormRequest
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
            'rabbitmq_host' => ['required', 'string'],
            'rabbitmq_port' => ['required', 'integer'],
            'rabbitmq_user' => ['required', 'string'],
            'rabbitmq_password' => ['required', 'string'],
            'rabbitmq_vhost' => ['required', 'string'],
            'rabbitmq_api_base_url' => ['required', 'string'],
        ];
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
