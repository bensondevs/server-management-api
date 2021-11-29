<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderIsActivatable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $order = Order::findOrFail($request->get('id'));
        if ($order->status != 'paid') 
            abort(
                403, 
                'This order is not activatable, you cannot create container from ' . $order->status . ' order'
            );

        return $next($request);
    }
}
