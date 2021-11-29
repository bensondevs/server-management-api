<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdministratorAuthentication
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
        return ($request->user()->isAdministrator()) ? 
            $next($request) :
            ($request->user()->hasPermission('dashboard login') ? 
                $next($request) : abort(403));
    }
}
