<?php

namespace Idev\EasyAdmin\app\Http\Middleware;

use Idev\EasyAdmin\app\Helpers\Constant;
use Closure;
use Illuminate\Http\Request;

class MiddlewareByAccess
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
        $routeName = $request->route()->getName();
        $allowAccess = (new Constant())->permissions();
        
        return in_array($routeName, $allowAccess['list_access']) ? $next($request) : abort(404);
    }
}
