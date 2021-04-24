<?php
namespace App\Http\Controllers\traits;

use App\Models\LeaveOvertimeStatus;
use Illuminate\Support\Facades\Auth;

/**
 * 
 */
trait Status
{
    
    public function leave_overtime_status($app_id){

        return LeaveOvertimeStatus::where('otlv_id', $app_id)->get();
    }

    public function save_otlv_status($app_id, $status, $reason){

        $lv_status  =   new LeaveOvertimeStatus;
        $lv_status->otlv_id     =   $app_id;
        $lv_status->approver    =   $status == 'AWP' || $status == 'AWOP' ? '---' : Auth::user()->fname . ' ' . Auth::user()->lname; //Auth::user()->empno;
        $lv_status->remarks     =   $reason;
        $lv_status->status      =   $status;
        $lv_status->txndate     =   date('Y-m-d');
        $lv_status->txntime     =   date('H:i:s');
        $lv_status->save();
    }

}
