<?php

namespace App\Http\Requests\Containers;

use Illuminate\Foundation\Http\FormRequest;

class SendCommandRequest extends FormRequest
{
    private $container;

    public function getContainer()
    {
        return $this->container = $this->container ?:
            Container::findOrFail($this->input('container_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $container = $this->getContainer();
        if ($user->hasRole('administrator')) return true;

        return ($container->customer_id == $user->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'container_id' => ['required', 'string'],
            'command' => ['required', 'string'],
        ];
    }
}
