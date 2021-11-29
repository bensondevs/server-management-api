<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Orders\{
    PlaceOrderRequest as PlaceRequest,
    PopulateOrdersApiRequest as PopulateApiRequest
};

use App\Http\Resources\UserOrderResource;

use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * Order Repository Class Container
     * 
     * @var App\Repositories\OrderRepository
     */
    private $order;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\OrderRepository  $order
     * @return void
     */
    public function __construct(OrderRepository $order)
    {
    	$this->order = $order;
    }

    /**
     * Populate customer orders
     * 
     * @param PopulateApiRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function orders(PopulateApiRequest $request)
    {
        $options = $request->options();

        $orders = $this->order->all($options, true);
        $orders = $this->order->paginate();
        $orders = UserOrderResource::apiCollection($orders);

    	return response()->json(['orders' => $orders]);
    }

    /**
     * Plade order
     * 
     * @param PlaceRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function place(PlaceRequest $request)
    {
        $orderData = $request->orderData();
        $order = $this->order->place($orderData);

        return apiResponse($this->order, ['order' => $order]);
    }

    /**
     * Show details of an order
     * 
     * @param \App\Models\Order  $order
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Order $order)
    {
        if ($order->status >= OrderStatus::Paid && $order->status <= OrderStatus::Activated) {
            $order->load(['payment']);
        }

        $order = new UserOrderResource($order);
        return response()->json(['order' => $order]);
    }
}