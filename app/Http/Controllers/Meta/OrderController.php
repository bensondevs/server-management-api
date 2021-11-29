<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\Order\OrderStatus;

class OrderController extends Controller
{
    /**
     * Collect all possible statuses for order
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statuses()
    {
        $statuses = OrderStatus::asSelectArray();
        return response()->json(['statuses' => $statuses]);   
    }

    /**
     * Collect all order status badge classes
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statusBadges()
    {
        $statuses = collect(OrderStatus::asSelectArray());
        return $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new OrderStatus($value))->badgeHtmlClass(),
            ];
        });
    }
}
