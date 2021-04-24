<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class InquiryMiddleware
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

        if(Auth::user()->level == 1 || Auth::user()->level == 5 || Auth::user()->level == 6 || Auth::user()->level >= 8){
            return $next($request);
        }

        return abort('401');
    }
}
