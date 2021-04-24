<?php

namespace App\Http\Controllers;

use App\Models\DTR;
use App\Models\Shift;
use App\Models\OBApplication;
use App\User;
use Illuminate\Http\Request;
use DB;
use Response;
use Session;

class DTRController extends Controller
{
    //
    public function dtr_trans($from, $to, $empno){

        $shift  =   Shift::select('shift', 'desc')->get();

        $employee   =   User::where('empno', $empno)->first();

        $daily_logs =   DTR::where('empno', $empno)->whereBetween('txndate', [$from, $to])->get();
        
        return view('logs.dtr_transaction.dtr')->with('shift', $shift)
                                            ->with('employee', $employee)
                                            ->with('daily_logs', $daily_logs);
    }

    public function ob_dtr(Request $request){

        $this->validate($request,[
            'am_in' => 'required',
            'am_out' => 'required',
            'pm_in' =>  'required',
            'pm_out' => 'required'
        ]);

        $txndate = date('Y-m-d', strtotime($request->txndate));

        $this->validate_time($this, $request);
        
        $empno   = $request->empno;
        $shift   = $request->shift;
        $am_in   = $request->am_in;
        $am_out  = $request->am_out;
        $pm_in   = $request->pm_in;
        $pm_out  = $request->pm_out;
        $am_in_old   = $request->am_in_old;
        $am_out_old  = $request->am_out_old;
        $pm_in_old   = $request->pm_in_old;
        $pm_out_old  = $request->pm_out_old;
        $nd_out  = $request->nd_out;
        $remarks = $request->remarks;
        $manual_input = "*";

        // $dtr = $this->dtr($empno, $txndate);
        $ob_app =   OBApplication::where('empno', $empno)->where('txndate', $txndate)->exists();

        if($ob_app == false){
            OBApplication::insert([
                            'empno' =>  $empno,
                            'date_filed' => date('Y-m-d'),
                            'txndate'   => $txndate
                        ]);
        }

        if($am_in != $am_in_old){
            if($am_in == ''){
                $manual_input = '';
            }
            DTR::where('empno', $empno)->where('txndate', $txndate)
                                    ->update([
                                        'in' => $am_in, 
                                        'nextday_out' => $nd_out,
                                        'in_manual' => $manual_input
                                    ]);

            OBApplication::where('empno', $empno)->where('date_filed', date('Y-m-d'))
                                                ->where('txndate', $txndate)
                                                ->update([
                                                    'in'        =>  $am_in, 
                                                    'remarks'   =>  $remarks
                                                ]);
        }

        if($am_out != $am_out_old){
            if($am_out == ''){
                $manual_input = '';
            }
            DTR::where('empno', $empno)->where('txndate', $txndate)
                                    ->update([
                                        'break_out' => $am_out, 
                                        'nextday_out' => $nd_out,
                                        'break_out_manual' => $manual_input
                                    ]);

            OBApplication::where('empno', $empno)->where('date_filed', date('Y-m-d'))
                                                ->where('txndate', $txndate)
                                                ->update([
                                                    'break_out' =>  $am_out, 
                                                    'remarks'   =>  $remarks
                                                ]);
        }

        if($pm_in != $pm_in_old){
            if($pm_in == ''){
                $manual_input = '';
            }
            DTR::where('empno', $empno)->where('txndate', $txndate)
                                    ->update([
                                        'break_in' => $pm_in, 
                                        'nextday_out' => $nd_out,
                                        'break_in_manual' => $manual_input
                                    ]);

            OBApplication::where('empno', $empno)->where('date_filed', date('Y-m-d'))
                                                ->where('txndate', $txndate)
                                                ->update([
                                                    'break_in'  =>  $pm_in, 
                                                    'remarks'   =>  $remarks
                                                ]);
        }

        if($pm_out != $pm_out_old){
            if($pm_out == ''){
                $manual_input = '';
            }
            DTR::where('empno', $empno)->where('txndate', $txndate)
                                    ->update([
                                        'out' => $pm_out, 
                                        'nextday_out' => $nd_out,
                                        'out_manual' => $manual_input
                                    ]);
                                                
            OBApplication::where('empno', $empno)->where('date_filed', date('Y-m-d'))
                                                ->where('txndate', $txndate)
                                                ->update([
                                                    'out'        =>  $pm_out, 
                                                    'remarks'   =>  $remarks
                                                ]);
        }

        Session::flash('success', 'DTR Updated!');
        return back();
    }

    private function validate_time($pointer, $request){

        if($request->am_in == '--'){
            $request->am_in = null;
        }

        if($request->am_out == '--'){
            $request->am_out = null;
        }

        if($request->pm_in == '--'){
            $request->pm_in = null;
        }

        if($request->pm_out == '--'){
            $request->pm_out = null;
        }

    }

    public function change_shift($from, $to, $empno){
        $shift      =   Shift::get();

        $employee   =   User::where('empno', $empno)->first();

        $daily_logs =   DTR::where('empno', $empno)->whereBetween('txndate', [$from, $to])->get();
        
        return view('logs.dtr_transaction.change_shift')->with('shift', $shift)
                                                ->with('employee', $employee)
                                                ->with('daily_logs', $daily_logs);
    }

    public function update_shift(Request $request){
        // dd($request->empno);
        DTR::where('empno', $request->empno)->where('txndate', $request->txndate)
                                        // ->where('shift', $request->curr_shift)
                                        ->update([
                                            'shift' =>  $request->new_shift
                                        ]);

        Session::flash('success', 'Shift Updated!');
        return back();
    }

    public function empno_list(){ // AJAX
        $empno =    User::select(DB::raw("CONCAT(lname, ', ', fname) AS name"), "empno")
                        ->where('active', 'Y')
                        ->orderBy('name', 'asc')
                        ->get();

        return Response::json($empno);
    }
}
