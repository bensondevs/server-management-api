<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Container;

class ContainerIsNotExpired
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
        $container = Container::findOrFail($request->get('id'));
        if ($container->isExpired()) {
            // Delete
            // $container->delete();

            // Set as expired
            // $container->setStatus('expired');

            return abort(403, 'This container is expired.');
        }

        return $next($request);
    }
}
