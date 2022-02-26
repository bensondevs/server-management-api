<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\SubscriptionRepository;

class SubscriptionActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('user_subscription')) {
            return session()->get('user_subscription');
        }

        $user = $request->user();

        $subsRepository = new SubscriptionRepository();
        return $subsRepository->userSubscription($user) ? 
            $next($request) :
            response()->json([
                'status' => 'error',
                'message' => 'Sorry you have no subscription active'
            ], 401);
    }
}