<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Overtime;
use DB;

class OvertimeSummaryCSV implements FromView
{
    protected $empno_name;
    protected $fromdate;
    protected $todate;
    protected $entity01;
    protected $entity02;
    protected $entity03;
    protected $status;

    function __construct($empno_name, $fromdate, $todate, $entity01, $entity02, $entity03, $status){
        $this->empno_name   =   $empno_name;
        $this->fromdate     =   $fromdate;
        $this->todate       =   $todate;
        $this->entity01     =   $entity01;
        $this->entity02     =   $entity02;
        $this->entity03     =   $entity03;
        $this->status       =   $status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
        //
        // $ot_summary = $this->local_connection()->table('overtime')
        //                                 ->select(
        //                                     'overtime.empno',
        //                                     DB::raw('date_format(overtime.created_at, "%m/%d/%y") as cdate'),
        //                                     DB::raw('date_format(overtime.dateot, "%m/%d/%y") as otdate'),
        //                                     'overtime.appr_hours')
        //                                 // ->whereRaw('YEAR(overtime.created_at) = ?', [date('Y')])
        //                                 ->where('submitted', 'Y')
        //                                 ->where('status', '!=', 'Cancelled')
        //                                 ->leftJoin('emp_master', 'emp_master.empno', '=', 'overtime.empno')
        //                                 ->orderBy('created_at', 'desc'); 
        // //NAME && ID No.
        // if(is_numeric($this->empno_name)){
        //     $ot_summary->where('overtime.empno', 'like', '%' . $this->empno_name . '%');
        // }
        // else{
        //     $ot_summary->where(DB::raw('concat(emp_master.fname, " ", emp_master.lname)'), 'like', '%' . $this->empno_name . '%');
        // }        
        
        // //STATUS
        // if ($this->status != "") {

        //     if ($this->status == "All") {
        //         $ot_summary->whereBetween('overtime.dateot', [$this->fromdate, $this->todate]);
        //     }
        //     else{
        //         $ot_summary->where('status', $this->status)
        //                     ->whereBetween('overtime.dateot', [$this->fromdate, $this->todate]);
        //     }
            
        // }
        
        $ot_summary =   Overtime::select(
                                    'overtime.empno',
                                    DB::raw('date_format(overtime.created_at, "%m/%d/%y") as cdate'),
                                    DB::raw('date_format(overtime.dateot, "%m/%d/%y") as otdate'),
                                    'overtime.appr_hours')
                                // ->whereRaw('YEAR(overtime.created_at) = ?', [date('Y')])
                                ->where('submitted', 'Y')
                                ->where('status', '!=', 'Cancelled')
                                ->leftJoin('emp_master', 'emp_master.empno', '=', 'overtime.empno')
                                ->orderBy('created_at', 'desc')
                                ->whereBetween('overtime.dateot', [$this->fromdate, $this->todate]);
        
        // // NAME OR ID No.
        // $ot_summary->when($empno_name != NULL, function($overtime) use ($empno_name){
        //     return $overtime->where('overtime.empno', 'like', $empno_name . '%');
        //     // return $overtime->where(function($ot) use ($empno_name){
        //     //             $ot->where('overtime.empno', 'like', $empno_name . '%')
        //     //                 ->orWhere(DB::raw('CONCAT(emp_master.fname, " ", emp_master.lname)'), 'like', '%' . $empno_name . '%');
        //     //         });
        // });
        
        if(!empty($this->empno_name)){
            $ot_summary->where('overtime.empno', $this->empno_name);
        }

        // COMPANY || DISTRICT || BRANCH
        if(!empty($this->entity01)){
            $ot_summary->where('emp_master.entity01', $this->entity01);
        }
        if(!empty($entity02)){
            $ot_summary->where('emp_master.entity01', $this->entity01)->where('emp_master.entity02', $this->entity02);
        }
        if(!empty($entity03)){
            $ot_summary->where('emp_master.entity01', $this->entity01)->where('emp_master.entity02', $this->entity02)->where('emp_master.entity03', $this->entity03);
        }

        // // STATUS
        if($this->status != 'All'){
            $ot_summary->where('status', $this->status);
        }
        
        $ot_summary = $ot_summary->orderBy('overtime.empno', 'asc')->orderBy('dateot', 'asc')->get();
        // dd($ot_summary);
        // $ot_summary = $ot_summary->get();
        return view('summary.overtime.ot_summary_csv', ['ot_summary' => $ot_summary]);
    }
}
