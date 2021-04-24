<?php

namespace App\Http\Controllers\traits;

use App\Models\Overtime;
use App\Models\DTR;
use App\Models\Shift;
use Auth;

/**
 * 
 */
trait OvertimeTrait
{
    
    public function overtime_list($refno, $empno){

        $ot_lists    =   Overtime::where('refno', $refno)->where('empno', $empno)->orderBy('dateot', 'desc')->get();
        
        $ots    =   [];
        $x  =   0;
        foreach($ot_lists as $ot){
            $shift  =   DTR::select('shift')->where('txndate', $ot->dateot)->where('empno', $ot->empno)->first();
            
            $ots[$x]['id']          =   $ot->id;
            $ots[$x]['empno']       =   $ot->empno;
            $ots[$x]['refno']       =   $ot->refno;
            $ots[$x]['dateot']      =   $ot->dateot;
            $ots[$x]['seqno']       =   $ot->seqno;
            $ots[$x]['clientname']  =   $ot->clientname;
            $ots[$x]['workdone']    =   $ot->workdone;
            $ots[$x]['timestart']   =   $ot->timestart;
            $ots[$x]['timefinish']  =   $ot->timefinish;
            $ots[$x]['hours']       =   $ot->hours;
            $ots[$x]['appr_hours']  =   $ot->appr_hours;
            $ots[$x]['pdf_file_ot'] =   $ot->pdf_file_ot;
            $ots[$x]['shift']       =   $shift == null ? "" : $shift->shift;
            // $ots[$x]['shift_desc']  =   $shift == null ? "" : Shift::where('shift', $shift->shift)->first()->desc;
            $ots[$x]['entity01']    =   $ot->entity01;
            $ots[$x]['app_code']    =   $ot->app_code;
            $ots[$x]['status']      =   $ot->status;
            $ots[$x]['created_at']  =   $ot->created_at;
            $x++;
        }
        
        return $ots;
    }

}
