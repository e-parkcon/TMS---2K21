<?php

namespace App\Http\Controllers;

use App\Exports\LeaveSummaryCSV;
use Illuminate\Http\Request;

use App\Http\Controllers\traits\Employee;
use App\Http\Controllers\traits\Leave_Type;
use App\Http\Controllers\traits\CheckLeave;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Leave;
use App\User;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class LeaveSummary extends Controller
{
    //
    use Employee;
    use Leave_Type;
    use CheckLeave;

    public function leave_summ_dashboard(Request $request){

        $company    =   Company::get();
        $idNo       =   User::select('empno', 'lname', 'fname')->where('active', 'Y')->orderBy('empno', 'asc')->get();
        $name       =   User::select('empno', 'lname', 'fname')->where('active', 'Y')->orderBy('fname', 'asc')->get();

        $empno      =   $request->empno;
        // $name       =   $request->name;
        $entity01   =   $request->cocode;
        $entity02   =   $request->distcode;
        $entity03   =   $request->brchcode;
        $status     =   $request->status;

        $lv_summary =   Leave::select('leave.*', 'emp_master.empno', 
                                    'emp_master.fname', 'emp_master.lname',
                                    'emp_master.entity01', 'emp_master.entity02', 'emp_master.entity03', 
                                    'leave_type.lv_desc')
                                ->leftJoin('emp_master', 'emp_master.empno', 'leave.empno')
                                ->leftJoin('leave_type', 'leave_type.lv_code', 'leave.leavecode')
                                // ->whereRaw('leave.created_at > NOW() - INTERVAL 11 MONTH')
                                ->whereBetween('leave.fromdate', [Input::get('fromdate'), Input::get('todate')]);

        // // NAME OR ID No.
        $lv_summary->when($empno != NULL, function($leave) use ($empno){
            return $leave->where('leave.empno', 'like', $empno . '%');
        });

        // // COMPANY || DISTRICT || BRANCH
        if(!empty($entity01)){
            $lv_summary->where('emp_master.entity01', $entity01);
        }
        if(!empty($entity02)){
            $lv_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02);
        }
        if(!empty($entity03)){
            $lv_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02)->where('emp_master.entity03', $entity03);
        }

        // // STATUS
        if($status == 'Approved_With_Pay'){
            $lv_summary->where('leave.wp_wop', 'wp')
                    ->where('leave.status', 'Approved');
        }elseif($status == 'Approved'){
            $lv_summary->where('leave.status', 'Approved');
        }
        elseif($status == 'Disapproved'){
            $lv_summary->where('leave.status', 'Disapproved');
        }


        $lv_summary = $lv_summary->orderBy('leave.created_at', 'asc')->paginate(10);
        return view('summary.leave.leave_summary', compact('lv_summary', $lv_summary->appends(Input::except('page'))))
                                                ->with('idNo', $idNo)
                                                ->with('name', $name)
                                                ->with('company', $company);
    }

    public function leave_list(){

        $leave  =   Leave::select('id', 'empno', 'fromdate', 'todate', 'total_day',
                                'app_fromdate', 'app_todate', 'app_days',
                                'leavecode', 'reason', 'status',
                                'app_code', 'pdf_file_leave', 'remarks', 'wp_wop',
                                'created_at')
                                // ->whereRaw('YEAR(created_at) > ?', '2020')
                                // ->whereYear('created_at', date('Y', strtotime('-10 MONTH')))
                                ->where('fromdate', '>', Carbon::now()->subMonths(10))
                                ->orderBy('fromdate', 'desc')->get();

        $l  =   0;
        $lv_list    =   [];
        foreach($leave as $lv){
            $lv_list[$l]['lv_id']        =   $lv->id;
            $lv_list[$l]['empno']        =   $lv->empno;
            $lv_list[$l]['name']         =   $this->emp_name($lv->empno) == null ? 'N/A' : $this->emp_name($lv->empno)->fname . ' ' . $this->emp_name($lv->empno)->lname;
            $lv_list[$l]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
            $lv_list[$l]['reason']       =   $lv->reason;

            $lv_list[$l]['fromdate']     =   $lv->fromdate;
            $lv_list[$l]['todate']       =   $lv->todate;
            $lv_list[$l]['days']         =   $lv->total_day;

            $lv_list[$l]['lv_code']      =   $lv->leavecode;
            $lv_list[$l]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;

            $lv_list[$l]['app_fromdate'] =   $lv->app_fromdate;
            $lv_list[$l]['app_todate']   =   $lv->app_todate;
            $lv_list[$l]['app_days']     =   $lv->app_days;

            $lv_list[$l]['app_code']     =   $lv->app_code;
            $lv_list[$l]['pdf_file_leave']   =   $lv->pdf_file_leave;

            $lv_list[$l]['w_pay_or_wo_pay']  =   $lv->wp_wop;
            $lv_list[$l]['date_file']       =   date('d-M-Y', strtotime($lv->created_at));

            $lv_list[$l]['status']       =   '';
            if($lv->status == 'Approved'){
                $lv_list[$l]['status']       =   'Approved';
            }
            elseif($lv->status == 'Disapproved'){
                $lv_list[$l]['status']       =   'Disapproved';
            }
            elseif($lv->status == 'Cancelled'){
                $lv_list[$l]['status']       =   'Cancelled';
            }
            else{
                $lv_list[$l]['status']       =   'Pending';
            }

            $l++;
        }

        return Response::json(array('data'=>$lv_list));
    }

    // REPORT
    public function generate_pdf_lv_summary(Request $request){

        $empno      =   $request->empno_name;
        $entity01   =   $request->cocode;
        $entity02   =   $request->distcode;
        $entity03   =   $request->brchcode;
        $status     =   $request->status;
     
        $lv_summary =   Leave::select('leave.*', 'emp_master.empno', 
                                    'emp_master.fname', 'emp_master.lname',
                                    'emp_master.entity01', 'emp_master.entity02', 'emp_master.entity03', 
                                    'leave_type.lv_desc')
                                ->leftJoin('emp_master', 'emp_master.empno', 'leave.empno')
                                ->leftJoin('leave_type', 'leave_type.lv_code', 'leave.leavecode')
                                // ->whereRaw('leave.created_at > NOW() - INTERVAL 11 MONTH')
                                ->whereBetween('leave.fromdate', [Input::get('fromdate'), Input::get('todate')]);

        // // NAME OR ID No.
        $lv_summary->when($empno != NULL, function($leave) use ($empno){
            return $leave->where('leave.empno', 'like', $empno . '%');
        });

        // COMPANY || DISTRICT || BRANCH
        if(!empty($entity01)){
            $lv_summary->where('emp_master.entity01', $entity01);
        }
        if(!empty($entity02)){
            $lv_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02);
        }
        if(!empty($entity03)){
            $lv_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02)->where('emp_master.entity03', $entity03);
        }
        
        // // STATUS
        if($status == 'Approved_With_Pay'){
            $lv_summary->where('leave.wp_wop', 'wp')
                    ->where('leave.status', 'Approved');
        }elseif($status == 'Approved'){
            $lv_summary->where('leave.status', 'Approved');
        }
        elseif($status == 'Disapproved'){
            $lv_summary->where('leave.status', 'Disapproved');
        }
        
        $lv_summary = $lv_summary->get();

        $lv_empnos =   [];
        $x  =   0;
        foreach($lv_summary as $lv_summ){
            $branch     =   Branch::where('entity01', $lv_summ->entity01)->where('entity03', $lv_summ->entity03)->first()->entity03_desc;
            if(!array_key_exists($lv_summ->empno, $lv_empnos)){
                $lv_empnos[$lv_summ->empno] =   array('lv'=>$lv_summary->where('empno', $lv_summ->empno), 
                                                        'branch'=>$branch, 
                                                        'total_days'=>$lv_summary->where('empno', $lv_summ->empno)->sum('app_days'));
                $x++;
            }
        }

        $pdf = PDF::loadView('summary.leave.lv_pdf_report', compact('lv_summary', 'lv_empnos'))->setPaper('a4', 'landscape');
        return $pdf->stream('Leave-Summary.pdf');
    }

    public function lv_csv(Request $request){

        return Excel::download(new LeaveSummaryCSV($request->empno_name, $request->fromdate, $request->todate, $request->entity01, $request->entity02, $request->entity03,  $request->status),  'Leave-Summary.csv');
    }
}
