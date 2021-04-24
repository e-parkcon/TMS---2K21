<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class TwoFactorAuth
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
        // return $next($request);

        if(Auth::user()->PIF_otp() == 'N'){
            return $next($request);
        }
        elseif(Auth::user()->PIF_otp() == 'Y'){
            
            if (Auth::user()->otpIsUsed() == 'Y') {
                return $next($request);
            }

            return redirect('/verifyUserOTP');
        }
    }
}
