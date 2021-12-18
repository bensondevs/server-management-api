<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

use App\Models\Order;
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
     * Populate user orders
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function orders()
    {
        $orders = QueryBuilder::for(auth()->user()->orders())
            ->allowedFilters(['status'])
            ->allowedIncludes([
                'items', 
                'precreatedContainers'
            ])->allowedSorts([
                'total', 
                'vat_size_percentage', 
                'grand_total'
            ])->allowAppends([
                'vat_amount', 
                'status_description', 
                'vat_size_percentage'
            ])
            ->get();
        $orders = new OrderCollection($orders);

    	return response()->json(['orders' => $orders]);
    }

    /**
     * Show details of an order
     * 
     * @param \App\Models\Order  $order
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Order $order)
    {
        $order = new OrderResource($order);
        return response()->json(['order' => $order]);
    }

    /**
     * Report order to administrator
     * 
     * @param  ReportRequest  $request
     * @param \App\Models\Order  $order
     * @return Illuminate\Support\Facades\Response
     */
    public function report(ReportRequest $request, Order $order)
    {
        //
    }
}