<?php

namespace App\Http\Requests\Containers;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\InputRules;

use App\Models\Container;

class UpdateContainerRequest extends FormRequest
{
    use InputRules;

    private $serverContainer;

    public function getServerContainer()
    {
        if ($this->serverContainer) return $this->serverContainer;

        $id = $this->input('id') ?: $this->input('container_id');
        return $this->model = $this->serverContainer = Container::findOrFail($id);
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
        $this->setRules([
            'hostname' => ['required', 'string'],

            'order_id' => ['string', 'exists:orders,id'],
            'customer_id' => ['string', 'exists:users,id'],
            'client_email' => ['string', 'email'],
            'order_date' => ['date'],
            'activation_date' => ['date'],
            'expiration_date' => ['date'],
        ]);

        if (! $this->input('order_id'))
            $this->addRule('service_plan_id', ['string', 'exists:service_plans,id']);

        return $this->returnRules();
    }
}
