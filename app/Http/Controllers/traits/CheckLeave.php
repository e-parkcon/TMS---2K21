<?php

namespace App\Http\Controllers\traits;

use App\Models\Leave;
use DB;

/**
 * 
 */
trait CheckLeave
{
    
    private function leave_exists($empno, $fromdate, $todate){

        $lv =   Leave::where('empno', $empno)
                        ->where('status', '!=', 'Cancelled')
                        ->where('status', '!=', 'Disapproved')
                        ->where('fromdate', $fromdate)
                        ->count();

        return $lv;
    }

    // private function leave_rule($lv_code, $fromdate){


    //     if($date_range > 3 && $lv_code == 'SL'){
    //         Session::flash('error', 'Filing of ' . $lv_desc . ' atleast 3 days after you get sick.');
    //         return back();
    //     }

    //     $fromDate   =   strtotime($fromdate);
    //     if($fromDate < strtotime('+2 days') && $lv_code == 'VL'){
    //         Session::flash('error', 'Filing of '. $lv_desc .' at least three days before your actual leave date.');
    //         return back();
    //     }

    // }

    private function dateRange($date){

        $lv_date    =   strtotime($date);
        $currDate   =   strtotime(date('Y-m-d'));

        $diff   =   $currDate - $lv_date;

        // To get the year divide the resultant date into total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24));
        // To get the month, subtract it with years and divide the resultant date into total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        // To get the day, subtract it with years and  months and divide the resultant date into total seconds in a days (60*60*24) 
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        // To get the hour, subtract it with years,  months & seconds and divide the resultant date into total seconds in a hours (60*60) 
        $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
        // To get the minutes, subtract it with years, months, seconds and hours and divide the  resultant date into total seconds i.e. 60 
        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60);
        // To get the minutes, subtract it with years, months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));

        return $days;
    }

    private function leave_period_check($fromdate, $todate, $lv_id, $lv_code, $empno){

        $leave  =   Leave::select(
                            DB::raw(
                                "CASE 
                                    WHEN leavecode= '$lv_code' AND app_fromdate = '$fromdate' AND app_todate = '$todate' THEN 'TRUE' 
                                    ELSE 'FALSE' 
                                END AS lv"))
                        ->where('id', $lv_id)
                        ->where('empno', $empno)
                        ->first();

        return $leave;

    }

}
