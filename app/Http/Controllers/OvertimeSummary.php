<?php

namespace App\Http\Controllers;

use App\Exports\OvertimeSummaryCSV;
use App\Models\Overtime;
use Illuminate\Http\Request;

use App\Http\Controllers\traits\Employee;
use App\Http\Controllers\traits\OvertimeTrait;
use App\Models\Branch;
use App\Models\Company;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Response;
use PDF;

class OvertimeSummary extends Controller
{
    //
    use Employee;
    use OvertimeTrait;

    public function ot_summary_dashboard(Request $request){

        $company    =   Company::get();
        $idNo       =   User::select('empno', 'lname', 'fname')->where('active', 'Y')->orderBy('empno', 'asc')->get();
        $name       =   User::select('empno', 'lname', 'fname')->where('active', 'Y')->orderBy('fname', 'asc')->get();

        $empno      =   $request->empno;
        $entity01   =   $request->cocode;
        $entity02   =   $request->distcode;
        $entity03   =   $request->brchcode;
        $status     =   $request->status;   

        $ot_summary =   Overtime::select('overtime.created_at', 
                                'overtime.refno', 
                                'emp_master.empno', 
                                'emp_master.fname', 'emp_master.lname',
                                'emp_master.entity01', 'emp_master.entity02', 'emp_master.entity03')
                                ->leftJoin('emp_master', 'emp_master.empno', 'overtime.empno')
                                ->where('overtime.submitted', 'Y')
                                ->where('overtime.status', '!=', 'Cancelled')
                                // ->whereRaw('overtime.created_at > NOW() - INTERVAL 11 MONTH')
                                ->whereBetween('overtime.dateot', [$request->fromdate, $request->todate])
                                ->groupBy('overtime.refno');

        // // NAME OR ID No.
        $ot_summary->when($empno != NULL, function($overtime) use ($empno){
            return $overtime->where('overtime.empno', 'like', $empno . '%');
            // return $overtime->where(function($ot) use ($empno_name){
            //             $ot->where('overtime.empno', 'like', $empno_name . '%')
            //                 ->orWhere(DB::raw('CONCAT(emp_master.fname, " ", emp_master.lname)'), 'like', '%' . $empno_name . '%');
            //         });
        });

        // COMPANY || DISTRICT || BRANCH
        if(!empty($entity01)){
            $ot_summary->where('emp_master.entity01', $entity01);
        }

        if(!empty($entity02)){
            $ot_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02);
        }

        if(!empty($entity03)){
            $ot_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02)->where('emp_master.entity03', $entity03);
        }

        // // STATUS
        if($status != 'All'){
            $ot_summary->where('status', $request->status);
        }

        $ot_summary = $ot_summary->orderBy('overtime.created_at', 'asc')->paginate(10);

        return view('summary.overtime.overtime_summary', compact('ot_summary', $ot_summary->appends(Input::except('page'))))->with('idNo', $idNo)
                                                        ->with('name', $name)
                                                        ->with('company', $company);
    }

    public function ot_summ_list(){
        $pending_ot    =   Overtime::select
                                    (DB::raw('count(*) as no_of_ot, refno, created_at,
                                        submitted, empno'))
                                    ->where('submitted', 'Y')
                                    ->where('status', '!=' ,'Cancelled')
                                    ->whereRaw('overtime.created_at > NOW() - INTERVAL 5 MONTH')
                                    ->orderBy('created_at', 'desc')
                                    ->groupBy('refno')->get();

        $x  =   0;
        $overtime   =   [];
        foreach($pending_ot as $ot){
            $overtime[$x]['empno']  =   $ot->empno;
            $overtime[$x]['name']   =   $this->emp_name($ot->empno) == null ? 'N/A' : $this->emp_name($ot->empno)->fname . ' ' . $this->emp_name($ot->empno)->lname;
            $overtime[$x]['refno']  =   $ot->refno;
            $overtime[$x]['date_filed']  =   date('d-M-Y', strtotime($ot->created_at));
            
            $x++;
        }

        return Response::json(array('data' => $overtime));
    }

    public function ot_summ_details($refno, $empno){

        $emp_name       =   $this->emp_name($empno)->fname . ' ' . $this->emp_name($empno)->lname;
        $emp_branch     =   $this->emp_branch($empno);
        $overtime_dtls  =   $this->overtime_list($refno, $empno);
        
        return view('summary.overtime.ot_summ_details')->with('ot_lists', $overtime_dtls)
                                                    ->with('emp_name', $emp_name)
                                                    ->with('emp_branch', $emp_branch);
    }

    //OVERTIME
    public function overtime_summary_pdf(Request $request){

        $empno      =   $request->empno_name;
        $entity01   =   $request->cocode;
        $entity02   =   $request->distcode;
        $entity03   =   $request->brchcode;
        $status     =   $request->status;

        $company    =   Company::get();
        $ot_summary =   Overtime::select('overtime.*', 'emp_master.empno', 'emp_master.fname', 'emp_master.lname',
                                        'emp_master.entity01', 'emp_master.entity02', 'emp_master.entity03')
                                        ->leftJoin('emp_master', 'emp_master.empno', 'overtime.empno')
                                        ->where('overtime.submitted', 'Y')
                                        ->where('overtime.status', '!=', 'Cancelled')
                                        // ->whereRaw('overtime.created_at > NOW() - INTERVAL 11 MONTH')
                                        ->whereBetween('overtime.dateot', [$request->fromdate, $request->todate]);

        // // NAME OR ID No.
        $ot_summary->when($empno != NULL, function($overtime) use ($empno){
            return $overtime->where('overtime.empno', 'like', $empno . '%');
        });

        // COMPANY || DISTRICT || BRANCH
        if(!empty($entity01)){
            $ot_summary->where('emp_master.entity01', $entity01);
        }
        if(!empty($entity02)){
            $ot_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02);
        }
        if(!empty($entity03)){
            $ot_summary->where('emp_master.entity01', $entity01)->where('emp_master.entity02', $entity02)->where('emp_master.entity03', $entity03);
        }

        // // STATUS
        if($status != 'All'){
            $ot_summary->where('status', $request->status);
        }

        $ot_summary = $ot_summary->orderBy('overtime.empno', 'asc')->orderBy('dateot', 'asc')->get();

        $empnos =   [];
        $x      =   0;
        foreach($ot_summary as $ot_empno){
            $branch     =   Branch::where('entity01', $ot_empno->entity01)->where('entity03', $ot_empno->entity03)->first()->entity03_desc;
            if(!array_key_exists($ot_empno->empno, $empnos)){
                $empnos[$ot_empno->empno]    =   array('ot' =>  $ot_summary->where('empno', $ot_empno->empno), 
                                                        'total_hours'   =>  $ot_summary->where('empno', $ot_empno->empno)->sum('appr_hours'),
                                                        'branch'    =>  $branch);

                $x++;
            }
        }

        $pdf = PDF::loadView('summary.overtime.overtime_pdf', compact('ot_summary', 'empnos'))->setPaper('a4', 'landscape');
        return $pdf->stream('Overtime-Summary.pdf');
    }

    public function ot_summ_csv(Request $request){
        
        return Excel::download(new OvertimeSummaryCSV($request->empno_name, $request->fromdate, $request->todate, $request->entity01, $request->entity02, $request->entity03, $request->status), 'Overtime-Summary.csv');
    }
}
