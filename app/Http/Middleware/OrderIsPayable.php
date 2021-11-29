<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrderIsPayable
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
        if ($order->status != 'unpaid')
            abort(403, 'This order is already ' . $order->status . '. You cannot pay it!');

        return $next($request);
    }
}
