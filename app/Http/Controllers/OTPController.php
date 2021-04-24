<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTPRequest;
use App\Models\OTP;
use Illuminate\Http\Request;

use Auth;
class OTPController extends Controller
{
    //

    public function verify_otp_page(){

        return view('otp.verify_otp');
    }

    public function verify_otp(OTPRequest $req_otp){
        // return request('OTP');
        // dd($req_otp->OTP);
        $OTP = Auth::user()->userOTP();

        if($req_otp->OTP == $OTP){
            
            // User::where('empno', Auth::user()->empno)->update(['isVerified' => 1]);

            OTP::where('empno', Auth::user()->empno)
                        ->where('otp', Auth::user()->userOTP())->update(['IsUsed' => 'Y']);

            return redirect('/home');
        }

        return back()->withErrors('OTP is invalid or expired');
    }
}
