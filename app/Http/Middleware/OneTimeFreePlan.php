<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\{ Order, ServicePlan };
use App\Enums\Order\OrderStatus;

class OneTimeFreePlan
{
    /*
    |--------------------------------------------------------------------------
    | One Time Free Plan Middleware
    |--------------------------------------------------------------------------
    |
    | This middleware create gate to check if a certain user has claimed his
    | one time free service plan.
    |
    */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $freePlan = ServicePlan::whereHas('pricings',  function ($pricing) {
            $pricing->where('price', 0);
        })->first();

        $claimed = Order::whereHas('items', function ($item) use ($freePlan) {
            $item->where('itemable_id', $freePlan->id)
                ->where('itemable_type', ServicePlan::class);
        })->where('status', '>', OrderStatus::Unpaid)
        ->exists();

        return $next($request);
    }
}
