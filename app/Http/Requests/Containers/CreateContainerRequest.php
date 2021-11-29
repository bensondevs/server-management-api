<?php

namespace App\Http\Requests\Containers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Order;

class CreateContainerRequest extends FormRequest
{
    private $order;

    public function getOrder()
    {
        return $this->order ?:
            Order::findOrFail(request()->input('order_id'));
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
        $order = $this->getOrder();

        // Check order status
        if ($order->status == 'unpaid')
            abort(422, 'The order is unpaid, please finsih the payment. If you think that the payment is done, please wait or ask the customer service.');
        else if ($order->status == 'activated')
            abort(422, 'This order has been activated');
        else if ($order->status == 'expired')
            abort(422, 'This order has been expired, please do reorder if necessary');
        else if ($order->status == 'destroyed')
            abort(422, 'This order has been destroyed.');

        return [];
    }
}
