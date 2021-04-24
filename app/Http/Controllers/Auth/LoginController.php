<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OTP;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/leave';

    public function username(){
        return 'empno';
    }


    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $result =   $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );

        if($result){
            if(Auth::user()->PIF_otp() == 'Y'){
                
                Auth::user()->sendUserOTP();
                // auth()->user()->sendUserOTP();
            }
        }

        return $result;
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $otp    =   OTP::where('empno', auth()->user()->empno)
                            ->orderBy('txndate', 'desc')
                            ->orderBy('txntime', 'desc')->first();
    
        if(Auth::user()->PIF_otp() == 'Y'){
            if($otp->IsUsed == 'Y'){
                // User::where('empno', auth()->user()->empno)->update(['isVerified' => 0]);
            }
            else{
                // User::where('empno', auth()->user()->empno)->update(['isVerified' => 0]);
            // if(!empty($otp)){
                OTP::where('empno', auth()->user()->empno)
                        ->where('otp', $otp->otp)->update(['IsUsed' => 'N']);
            }
        }
        // else{
        // //     User::where('empno', auth()->user()->empno)->update(['isVerified' => 0]);
        // }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

}
