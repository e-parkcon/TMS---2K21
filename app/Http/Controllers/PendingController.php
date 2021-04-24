<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\traits\Employee;
use App\Http\Controllers\traits\Leave_Type;
use App\Http\Controllers\traits\CheckLeave;
use App\Http\Controllers\traits\Status;
use App\Mail\LeaveMailable;
use App\Mail\OTMailable;
use App\Models\ApprovingGroup;
use App\Models\Branch;
use App\Models\DTR;
use App\Models\LeaveCredits;
use App\Models\LeaveLedger;
use App\Models\LeaveOvertimeStatus;
use App\Models\Overtime;
use App\Models\Shift;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Response;
use Session;

class PendingController extends Controller
{
    //
    use Employee;
    use Leave_Type;
    use CheckLeave;
    use Status;

    public function pending_leave_page(){

        return view('pending.pending_leave.pending_lv');
    }

    public function pending_leave_data(){

        $pending_lv =   Leave::where('leave_crypt', Auth::user()->crypt)
                            ->where('status', '!=', 'Cancelled')
                            ->where('status', '!=', 'Disapproved')
                            ->orderBy('created_at', 'asc')
                            ->get();

        $x  =   0;
        $pend_lv =   [];
        foreach($pending_lv as $lv){
            $pend_lv[$x]['lv_id']        =   $lv->id;
            $pend_lv[$x]['empno']        =   $lv->empno;
            $pend_lv[$x]['name']         =   $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
            $pend_lv[$x]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
            $pend_lv[$x]['reason']       =   $lv->reason;

            $pend_lv[$x]['fromdate']     =   $lv->fromdate;
            $pend_lv[$x]['todate']       =   $lv->todate;
            $pend_lv[$x]['days']         =   $lv->total_day;

            $pend_lv[$x]['lv_code']      =   $lv->leavecode;
            $pend_lv[$x]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;

            $pend_lv[$x]['app_fromdate'] =   $lv->app_fromdate;
            $pend_lv[$x]['app_todate']   =   $lv->app_todate;
            $pend_lv[$x]['app_days']     =   $lv->app_days;

            $pend_lv[$x]['pdf_file_leave']   =   $lv->pdf_file_leave;

            $pend_lv[$x]['w_pay_or_wo_pay']  =   $lv->wp_wop;
            $pend_lv[$x]['date_file']       =   date('d-M-Y', strtotime($lv->created_at));

            $pend_lv[$x]['status']       =   '';
            if($lv->status == 'Approved'){
                $pend_lv[$x]['status']       =   'Approved';
            }
            else{
                $pend_lv[$x]['status']       =   'Pending';
            }

            $x++;
        }

        return  Response::json(array('data'=>$pend_lv));
    }

    public function leave_details($lv_id, $empno){

        $leave_dtl  =   Leave::where('id', $lv_id)->where('empno', $empno)->first();

        $emp_lv_credits =   $this->empl_lv_credits($empno);

        try {
            $leave  =   [];
            $leave['lv_id']        =   $leave_dtl->id;
            $leave['empno']        =   $leave_dtl->empno;
            $leave['name']         =   $this->emp_name($leave_dtl->empno)->fname . ' ' . $this->emp_name($leave_dtl->empno)->lname;
            $leave['entity03_desc']=    Branch::where('entity01', $this->emp_name($leave_dtl->empno)->entity01)->where('entity03', $this->emp_name($leave_dtl->empno)->entity03)->first()->entity03_desc;
            $leave['lv_period']    =   date('d-M-Y', strtotime($leave_dtl->fromdate)) . ' to ' . date('d-M-Y', strtotime($leave_dtl->todate));
            $leave['reason']       =   $leave_dtl->reason;
    
            $leave['fromdate']     =   $leave_dtl->fromdate;
            $leave['todate']       =   $leave_dtl->todate;
            $leave['days']         =   $leave_dtl->total_day;
    
            $leave['lv_code']      =   $leave_dtl->leavecode;
            $leave['lv_desc']      =   $this->leave_type()->where('lv_code', $leave_dtl->leavecode)->first()->lv_desc;
    
            $leave['app_fromdate'] =   $leave_dtl->app_fromdate;
            $leave['app_todate']   =   $leave_dtl->app_todate;
            $leave['app_days']     =   $leave_dtl->app_days;
    
            $leave['pdf_file_leave']    =   $leave_dtl->pdf_file_leave != null || !empty($leave_dtl->pdf_file_leave) ? $leave_dtl->pdf_file_leave : '';
    
            $leave['w_pay_or_wo_pay']   =   $leave_dtl->wp_wop;
            $leave['date_file']         =   date('d-M-Y H:i A', strtotime($leave_dtl->created_at));
    
            $leave['leave_timeline']    =   $this->leave_overtime_status($lv_id);
    
            // $x  =   0;
            // $leave =   [];
            // foreach($leave_dtl as $lv){
            //     $leave[$x]['lv_id']        =   $lv->id;
            //     $leave[$x]['empno']        =   $lv->empno;
            //     $leave[$x]['name']         =   $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
            //     $leave[$x]['entity03_desc']=    Branch::where('entity01', $this->emp_name($lv->empno)->entity01)->where('entity03', $this->emp_name($lv->empno)->entity03)->first()->entity03_desc;
            //     $leave[$x]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
            //     $leave[$x]['reason']       =   $lv->reason;
    
            //     $leave[$x]['fromdate']     =   $lv->fromdate;
            //     $leave[$x]['todate']       =   $lv->todate;
            //     $leave[$x]['days']         =   $lv->total_day;
    
            //     $leave[$x]['lv_code']      =   $lv->leavecode;
            //     $leave[$x]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;
    
            //     $leave[$x]['app_fromdate'] =   $lv->app_fromdate;
            //     $leave[$x]['app_todate']   =   $lv->app_todate;
            //     $leave[$x]['app_days']     =   $lv->app_days;
    
            //     $leave[$x]['pdf_file_leave']    =   $lv->pdf_file_leave != null || !empty($lv->pdf_file_leave) ? $lv->pdf_file_leave : '';
    
            //     $leave[$x]['w_pay_or_wo_pay']   =   $lv->wp_wop;
            //     $leave[$x]['date_file']         =   date('d-M-Y H:i A', strtotime($lv->created_at));
    
            //     $leave[$x]['leave_timeline']    =   $this->leave_overtime_status($lv_id);
    
            //     $x++;
            // }
    
            return view('pending.pending_leave.leave_details')->with('leave_dtl', $leave)
                                                            ->with('lv_credits', $emp_lv_credits);
        } catch (\ErrorException $e) {
            return abort('404');
        }


    }

    public function approve_leave(Request $request, $lv_id, $empno){

        $reason =   $request->reason;
        
        $leave  =   Leave::find([$lv_id, $empno])->first();

        $seqno  =   $leave->stage + 1;        
        $app_officers   =   ApprovingGroup::user_approvingOfficers($empno, 'L')->where('seqno', $seqno)->where('app_code', $leave->app_code);

        if(count($app_officers) != 0){
            // EMAIL LEAVE TO NEXT OFFICER
            Mail::to($app_officers->first()->email)->send(new LeaveMailable($app_officers->first(), User::where('empno', $empno)->first(), $leave));

            $leave->update([ 
                'stage' =>   $seqno,
                'leave_crypt'   =>  $app_officers->first()->crypt
            ]);
        }
        else{
            // UPDATE LEAVE STATUS TO APPROVED (LAST OFFICER)
            $leave->update([
                'stage'     =>   $seqno,
                'status'    =>  'Approved',
                'leave_crypt'   =>  '--'
            ]);
        }

        $this->save_otlv_status($lv_id, 'A', $reason); // SAVE HISTORY
    }

    public function disapprove_leave(Request $request, $lv_id, $empno){

        $reason =   $request->reason;
        
        $leave  =   Leave::find([$lv_id, $empno])->first();
        $leave->update([
            'status'    =>  'Disapproved',
            'leave_crypt' =>  '--'
        ]);

        $this->save_otlv_status($lv_id, 'D', $reason); // SAVE HISTORY
    }

    public function update_lv_period(Request $req, $lv_id, $empno){
        
        $this->validate($req, [
            'fromdate_app'  =>  'required|date',
            'todate_app'    =>  'required|date',
            'reason'        =>  'required'
        ]);

        $fromdate_app   =   $req->fromdate_app;
        $todate_app     =   $req->todate_app;
        $lv_code        =   $req->lv_code;
        
        $check_date  =   $this->leave_period_check($fromdate_app, $todate_app, $lv_id, $lv_code, $empno);
        if($check_date->lv == 'TRUE'){
            Session::flash('warning', 'Leave details not updated.');
            return back();
        }

        try {
            
            Leave::where('id', $lv_id)->where('empno', $empno)
                ->update([
                    'leavecode'     =>  $lv_code,
                    'app_fromdate'  =>  $fromdate_app,
                    'app_todate'    =>  $todate_app,
                    'app_days'      =>  $req->no_days
                ]);
            
            $this->save_otlv_status($lv_id, 'E', $req->reason); // SAVE HISTORY

            Session::flash('success', 'Leave period updated.');
            return back();

        } catch (\Exception $th) {
            //throw $th;
            return back()->withErrors($th->getMessage());
        }
    }

    public function pending_ot_page(){

        return view('pending.pending_overtime.pending_ot_header');
    }

    public function pending_ot_data(){
        
        $pending_ot    =   Overtime::select
                                (DB::raw('count(*) as no_of_ot, refno, date_format(overtime.created_at, "%M %d, %Y") as date_file,
                                submitted, empno'))
                                ->where('ot_crypt', Auth::user()->crypt)
                                ->where('submitted', 'Y')
                                ->where('status', '!=' ,'Cancelled')
                                ->orderBy('created_at', 'desc')
                                ->groupBy('refno')->get();

        $x  =   0;
        $overtime   =   [];
        foreach($pending_ot as $ot){

            $emp_name   =   $this->emp_name($ot->empno);

            $overtime[$x]['empno']  =   $ot->empno;
            $overtime[$x]['name']   =   $emp_name != null ? $this->emp_name($ot->empno)->fname . ' ' . $this->emp_name($ot->empno)->lname : '';
            $overtime[$x]['refno']  =   $ot->refno;
            $overtime[$x]['date_filed']  =   $ot->date_file;
            
            $x++;
        }

        return Response::json(array('data' => $overtime));
    }

    public function pending_ot_list($refno, $empno){

        $emp_name   =   $this->emp_name($empno);
        $emp_branch =   $this->emp_branch($empno);
        $ot_lists   =   Overtime::where('refno', $refno)->where('empno',$empno)
                                ->where('status', '!=', 'Cancelled')
                                ->where('status', '!=', 'Disapproved')
                                ->where('ot_crypt', Auth::user()->crypt)
                                ->orderBy('seqno', 'asc')->get();
        
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
            $ots[$x]['shift_desc']  =   $shift == null ? "" : Shift::where('shift', $shift->shift)->first()->desc;
            $ots[$x]['entity01']    =   $ot->entity01;
            $ots[$x]['app_code']    =   $ot->app_code;
            $ots[$x]['status']      =   $ot->status;
            $ots[$x]['ot_crypt']    =   $ot->ot_crypt;
            $ots[$x]['created_at']      =   $ot->created_at;
            $x++;
        }


        return view('pending.pending_overtime.ot_list')->with('ot_lists', $ots)->with('refno', $refno)
                                                    ->with('emp_name', $emp_name)
                                                    ->with('emp_branch', $emp_branch);
    }

    public function approve_selected_ot(Request $request, $refno, $empno){

        $selected_ot    =   $request->checked; // SELECTED OT

        foreach ($selected_ot as $key => $ot_seqno) {

            $overtime   =   Overtime::where('refno', $refno)->where('empno', $empno)->where('seqno', $ot_seqno)->first();
            $ot_hours   =   $overtime->appr_hours == 0 ? $overtime->hours : $overtime->appr_hours;

            $overtime->update([
                'appr_hours'    =>  $ot_hours
            ]);

            $seqno  =   $overtime->stage + 1;
            $app_officers   =   ApprovingGroup::user_approvingOfficers($empno, 'O')->where('seqno', $seqno)->where('app_code', $overtime->app_code);

            if(count($app_officers) != 0){
                // EMAIL LEAVE TO NEXT OFFICER
                Mail::to($app_officers->first()->email)->send(new OTMailable($app_officers->first(), User::where('empno', $empno)->first(), $refno));
                
                $overtime->update([
                    'stage' =>   $seqno,
                    'ot_crypt'   =>  $app_officers->first()->crypt
                ]);
            }
            else{
                $overtime->update([
                    'stage'     =>   $seqno,
                    'status'    =>  'Approved',
                    'ot_crypt'   =>  '--'
                ]);
            }

            $this->save_otlv_status($overtime->id, 'A', ''); // SAVE HISTORY
        }
        
        Session::flash('success', 'Overtime Approved!');
        return redirect()->route('pending_ot_header');
        // return response()->json('/pending_ot');
    }

    public function disapprove_selected_ot(Request $request, $refno, $empno){

        // dd('disapproved');
        $selected_ot    =   $request->checked;
        $reason         =   $request->reason;
        
        foreach ($selected_ot as $key => $ot_seqno) {
            # code...
            $overtime   =   Overtime::where('refno', $refno)->where('empno', $empno)->where('seqno', $ot_seqno)->first();
            $overtime->update([
                'status'    =>  'Disapproved',
                'ot_crypt'  =>  '--'
            ]);
            
            $this->save_otlv_status($overtime->id, 'D', $reason); // SAVE HISTORY
        }        

        return response()->json('/pending_ot');
    }

    public function edit_ot_hours(Request $request, $refno, $ot_id, $empno){

        $appr_hrs   =   $request->appr_hours;
        $reason     =   $request->reason;
        
        $ot  =   Overtime::find([$refno, $ot_id, $empno])->first();
        $ot->update([
            'appr_hours'    =>  $appr_hrs,  
        ]);
        $this->save_otlv_status($ot_id, 'E', $reason); // SAVE HISTORY

        // $seqno  =   $ot->stage + 1;        
        // $app_officers   =   ApprovingGroup::approvingOfficers($empno, 'O')->where('seqno', $seqno)->where('app_code', $ot->app_code);

        // if(count($app_officers) != 0){
        //     // EMAIL LEAVE TO NEXT OFFICER
        //     // 
        //     $ot->update([
        //         'stage'         =>   $seqno,
        //         'appr_hours'    =>  $appr_hrs,  
        //         'ot_crypt'      =>  $app_officers->first()->app_crypt
        //     ]);

        //     return response()->json(0);
        // }
        // else{
        //     $ot->update([
        //         'stage'     =>   $seqno,
        //         'status'    =>  'Approved',
        //         'ot_crypt'   =>  '--'
        //     ]);

        //     return response()->json(0);
        // }
    }

    public function disapprove_one_overtime(Request $request, $refno, $ot_id, $empno){
        $reason     =   $request->reason;

        $overtime   =   Overtime::find([$refno, $ot_id, $empno])->first();
        $overtime->update([
            'status'    =>  'Disapproved',
            'ot_crypt'  =>  '--'
        ]);

        $this->save_otlv_status($ot_id, 'D', $reason); // SAVE HISTORY

        return response()->json('/pending_ot');
    }

    public function approved_leave_dashboard(){

        return view('pending.approve_leave.approveLeave_dashboard');
    }

    public function app_lv_dtls(){

        $appr_lv    =   Leave::where('status', 'APPROVED')
                            ->where('wp_wop', '')->orderBy('created_at', 'desc')->get();

        $x  =   0;
        $app_lv =   [];
        foreach($appr_lv as $lv){
            $app_lv[$x]['lv_id']        =   $lv->id;
            $app_lv[$x]['empno']        =   $lv->empno;
            // $app_lv[$x]['name']         =   $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
            $app_lv[$x]['name']         =   $this->emp_name($lv->empno) == null ? 'N/A' : $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
            $app_lv[$x]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
            $app_lv[$x]['reason']       =   $lv->reason;

            $app_lv[$x]['fromdate']     =   $lv->fromdate;
            $app_lv[$x]['todate']       =   $lv->todate;
            $app_lv[$x]['days']         =   $lv->total_day;

            $app_lv[$x]['lv_code']      =   $lv->leavecode;
            $app_lv[$x]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;

            $app_lv[$x]['app_fromdate'] =   $lv->app_fromdate;
            $app_lv[$x]['app_todate']   =   $lv->app_todate;
            $app_lv[$x]['app_days']     =   $lv->app_days;

            $app_lv[$x]['pdf_file_leave']   =   $lv->pdf_file_leave;

            $app_lv[$x]['w_pay_or_wo_pay']  =   $lv->wp_wop;
            $app_lv[$x]['date_file']       =   date('d-M-Y', strtotime($lv->created_at));

            $app_lv[$x]['status']       =   '';
            if($lv->status == 'Approved'){
                $app_lv[$x]['status']       =   'Approved';
            }
            else{
                $app_lv[$x]['status']       =   'Pending';
            }

            $x++;
        }

        return Response::json(array('data'=>$app_lv));
    }

    public function approve_leave_details($lv_id, $empno){
        
        $leave_dtl  =   Leave::where('id', $lv_id)->where('empno', $empno)->first();
        $emp_lv_credits =   $this->empl_lv_credits($empno);

        $leave  =   [];
        $leave['lv_id']        =   $leave_dtl->id;
        $leave['empno']        =   $leave_dtl->empno;
        $leave['name']         =   $this->emp_name($leave_dtl->empno)->fname . ' ' . $this->emp_name($leave_dtl->empno)->lname;
        $leave['entity03_desc']=    Branch::where('entity01', $this->emp_name($leave_dtl->empno)->entity01)->where('entity03', $this->emp_name($leave_dtl->empno)->entity03)->first()->entity03_desc;
        $leave['lv_period']    =   date('d-M-Y', strtotime($leave_dtl->fromdate)) . ' to ' . date('d-M-Y', strtotime($leave_dtl->todate));
        $leave['reason']       =   $leave_dtl->reason;

        $leave['fromdate']     =   $leave_dtl->fromdate;
        $leave['todate']       =   $leave_dtl->todate;
        $leave['days']         =   $leave_dtl->total_day;

        $leave['lv_code']      =   $leave_dtl->leavecode;
        $leave['lv_desc']      =   $this->leave_type()->where('lv_code', $leave_dtl->leavecode)->first()->lv_desc;

        $leave['app_fromdate'] =   $leave_dtl->app_fromdate;
        $leave['app_todate']   =   $leave_dtl->app_todate;
        $leave['app_days']     =   $leave_dtl->app_days;

        $leave['pdf_file_leave']    =   $leave_dtl->pdf_file_leave;

        $leave['w_pay_or_wo_pay']   =   $leave_dtl->wp_wop;
        $leave['date_file']         =   date('d-M-Y H:i A', strtotime($leave_dtl->created_at));

        $leave['leave_timeline']    =   $this->leave_overtime_status($lv_id);

        // $x  =   0;
        // $leave =   [];
        // foreach($leave_dtl as $lv){
        //     $leave[$x]['lv_id']        =   $lv->id;
        //     $leave[$x]['empno']        =   $lv->empno;
        //     $leave[$x]['name']         =   $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
        //     $leave[$x]['entity03_desc']=    Branch::where('entity01', $this->emp_name($lv->empno)->entity01)->where('entity03', $this->emp_name($lv->empno)->entity03)->first()->entity03_desc;
        //     $leave[$x]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
        //     $leave[$x]['reason']       =   $lv->reason;

        //     $leave[$x]['fromdate']     =   $lv->fromdate;
        //     $leave[$x]['todate']       =   $lv->todate;
        //     $leave[$x]['days']         =   $lv->total_day;

        //     $leave[$x]['lv_code']      =   $lv->leavecode;
        //     $leave[$x]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;

        //     $leave[$x]['app_fromdate'] =   $lv->app_fromdate;
        //     $leave[$x]['app_todate']   =   $lv->app_todate;
        //     $leave[$x]['app_days']     =   $lv->app_days;

        //     $leave[$x]['pdf_file_leave']    =   $lv->pdf_file_leave;

        //     $leave[$x]['w_pay_or_wo_pay']   =   $lv->wp_wop;
        //     $leave[$x]['date_file']         =   date('d-M-Y H:i A', strtotime($lv->created_at));

        //     $leave[$x]['leave_timeline']    =   $this->leave_overtime_status($lv_id);

        //     $x++;
        // }
        // dd($leave);
        return view('pending.approve_leave.approve_leave_details')->with('leave_dtl', $leave)
                                                        ->with('lv_credits', $emp_lv_credits);
    }

    public function approve_with_pay($lv_id, $empno){

        $leave  =   Leave::where('id', $lv_id)->where('empno', $empno)->first();

        $fromdate_app   =   $leave->app_fromdate;
        $todate_app   =   $leave->app_todate;

        $leave->update([
            'wp_wop'    =>  'WP'
        ]);

        $lv_credit  =   LeaveCredits::where('empno', $empno)->where('lv_code', $leave->leavecode)->first();
        $test   =   $lv_credit->lv_balance == 0 ? 0 : ($lv_credit->lv_balance - $leave->app_days);
        LeaveCredits::where('empno', $empno)->where('lv_code', $leave->leavecode)
                    ->update([
                        'lv_balance'   =>  $test
                    ]);

        LeaveLedger::insert([
                        'empno'         =>  $empno,
                        'txndate'       =>  $fromdate_app,
                        'lv_frmdate'    =>  $fromdate_app,
                        'lv_toodate'    =>  $todate_app,
                        'lv_period'     =>  $fromdate_app . ' - ' . $todate_app,
                        'lv_code'       =>  $leave->leavecode,
                        'lv_credit'     =>  $lv_credit->lv_balance,
                        'lv_used'       =>  $leave->app_days,
                        'lv_balance'    =>  $test,
                        'remarks'       =>  $leave->reason
                    ]);

        while($fromdate_app <= $todate_app){

            $dtr    =   Dtr::select('dtr.*', 'holiday.*')->where('empno', $empno)
                                                        ->where('txndate', $fromdate_app)
                                                        ->leftJoin('holiday', 'holiday.hol_date', '=', 'dtr.txndate')
                                                        ->first();
            if($dtr->shift == 'X') {

                Dtr::where('empno', $empno)->where('txndate', $fromdate_app)->update(['lv_code' => '']);
                $fromdate_app = date('Y-m-d', strtotime('+1 day', strtotime($fromdate_app)));

            }elseif($dtr->holtype_code == 'REG' || $dtr->holtype_code == 'SPL') {

                Dtr::where('empno', $empno)->where('txndate', $fromdate_app)->update(['lv_code' => '']);
                $fromdate_app = date('Y-m-d', strtotime('+1 day', strtotime($fromdate_app)));

            }
            else{
                Dtr::where('empno', $empno)->where('txndate', $fromdate_app)->update(['lv_code' => $leave->leavecode]);
                $fromdate_app = date('Y-m-d', strtotime('+1 day', strtotime($fromdate_app)));
            }

        }

        $this->save_otlv_status($lv_id, 'AWP', '--'); // SAVE HISTORY
    }

    public function approve_without_pay($lv_id, $empno){
        
        $leave  =   Leave::find([$lv_id, $empno])->first();
        $leave->update([
            'wp_wop'    =>  'AWOP'
        ]);

        $lv_credit  =   LeaveCredits::where('empno', $leave->empno)->where('lv_code', $leave->leavecode)->first();
        LeaveLedger::insert([
                        'empno'         =>  $leave->empno,
                        'txndate'       =>  $leave->app_fromdate,
                        'lv_frmdate'    =>  $leave->app_fromdate,
                        'lv_toodate'    =>  $leave->app_todate,
                        'lv_period'     =>  $leave->app_fromdate . ' - ' . $leave->app_todate,
                        'lv_code'       =>  $leave->leavecode,
                        'lv_credit'     =>  $lv_credit->lv_balance,
                        'lv_used'       =>  $leave->app_days,
                        'lv_balance'    =>  0,
                        'remarks'       =>  $leave->reason
                    ]);

        $this->save_otlv_status($lv_id, 'AWOP', '--'); // SAVE HISTORY

    }
}
