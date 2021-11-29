<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Order;

class FindOrderRequest extends FormRequest
{
    private $order;

    public function getOrder()
    {
        if ($this->order) return $this->order;

        $id = $this->input('id');
        return $this->order = Order::findOrFail($id);
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
            'id' => ['required'],
        ];
    }
}
