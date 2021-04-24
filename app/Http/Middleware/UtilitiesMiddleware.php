<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UtilitiesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Auth::user()->level >= 7){
            return $next($request);    
        }

        return abort('401');
    }
}
