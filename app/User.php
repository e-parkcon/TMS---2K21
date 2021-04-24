<?php

namespace App;

use App\Models\ApprovingMember;
use App\Models\ApprovingOfficers;
use App\Models\Branch;
use App\Models\District;
use App\Models\Employee_Image;
use App\Models\OTP;
use App\Models\PIF;
use App\Models\UserLevel_Menu;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'emp_master';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function PIF_otp(){

        return PIF::where('empno', $this->empno)->first()->otp;
    }

    public function user_mobile_no(){

        $num =  PIF::where('empno',$this->empno)->first()->mobile_no;
        
        $ptn =  "/^0/";  // Regex
        $str =  $num;
        $phone_num = str_replace("-", "", $str);
        $rpltxt = "63";  // Replacement string
        
        $number = preg_replace($ptn, $rpltxt, $phone_num);
        return $phone_num;
    }

    public function userOTP(){
        return Cache::get($this->user_OTPKey());
    }

    public function user_OTPKey(){
        return "OTP_for_empno_{$this->empno}";
    }

    public function cacheOTP(){
        
        $otp    =   rand(100000, 999999);
        $otp_exp_path   =   config('sms.otp_expiration');

        $OTP_expires    =   now()->addMinutes($otp_exp_path);
        Cache::put([$this->user_OTPKey() => $otp], $OTP_expires);

        $this->saveOTP_to_db();

        return $otp;
    }

    public function sendUserOTP(){

        // $file   =   public_path() . config('sms.sms_file_path');
        $file   =   config('sms.sms_file_path');
        // $sms    =   fopen($file, "w");
        $sms    =   fopen($file.'/'.$this->empno, "w");
        $txt    =   "To: ". str_replace("-", "", $this->user_mobile_no()) . " \n";
        fwrite($sms, $txt);
        $txt    =   "\r\nYour One-Time-PIN is " . $this->cacheOTP() . ". \n\nDO NOT SHARE THIS TO ANYONE.";
        fwrite($sms, $txt);

        fclose($sms);
    }

    public function reference_no(){
        $random     =   rand(1000, 9999);
        $refno      =   'TMS' . date('His') . $random;

        return $refno;
    }
    
    public function saveOTP_to_db(){

        $db_otp =  new OTP();

        $db_otp->empno      =   $this->empno;
        $db_otp->txndate    =   date('Y-m-d');
        $db_otp->txntime    =   date('H:i:s');
        $db_otp->otp        =   $this->userOTP();
        $db_otp->refno      =   $this->reference_no();

        $db_otp->save();
    }

    public function otpIsUsed(){
        $otp    =   OTP::where('empno', $this->empno)
                            ->orderBy('txndate', 'desc')
                            ->orderBy('txntime', 'desc')->first();

        return $otp->IsUsed;
    }

    public function app_group($ot_lv){
        $app_group  =   ApprovingMember::where('empno', $this->empno)->where('otlv', $ot_lv);

        return $app_group;
    }

    public function app_officer($ot_lv){
        $officer    =   ApprovingOfficers::where('app_code', $this->app_group($ot_lv)->first()->app_code)
                                        ->where('otlv', $ot_lv)->orderBy('seqno', 'asc')->first();

        $name   =   self::where('empno', $officer->empno)->first();
        
        $off['name']   =   $name->fname . ' ' . $name->lname;
        $off['email']  =   $name->email;
        $off['empno']  =   $officer->empno;
        $off['app_code']  =   $officer->app_code;
        $off['otlv']   =   $officer->otlv;
        $off['seqno']  =   $officer->seqno;
        $off['app_crypt']   =   $name->crypt;

        return $off;
    }

    public function user_menu(){
        $menus  =   UserLevel_Menu::select('web_level_menu.*', 'web_menu.*')
                    ->where('user_level', $this->level)
                    ->leftJoin('web_menu', function($menu){
                        $menu->on('web_menu.menu_code', 'web_level_menu.menu_code')
                            ->on('web_menu.code', 'web_level_menu.code');
                    })->get();
                    
        return $menus;
    }
   
}