<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Leave;
use DB;

class LeaveSummaryCSV implements FromView
{

    protected $empno_name;
    protected $fromdate;
    protected $todate;
    protected $entity01;
    protected $entity02;
    protected $entity03;
    protected $status;

    function __construct($empno_name, $fromdate, $todate, $entity01, $entity02, $entity03, $status){
        $this->empno_name = $empno_name;
        $this->fromdate = $fromdate;
        $this->todate = $todate;
        $this->entity01 = $entity01;
        $this->entity02 = $entity02;
        $this->entity03 = $entity03;
        $this->status = $status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
        //
        $lv_summary =   Leave::select('leave.empno',  
                                'leave.leavecode',
                                DB::raw('date_format(leave.created_at, "%m/%d/%y") as cdate'),
                                DB::raw('date_format(leave.app_fromdate, "%m/%d/%y") as fdate'),
                                DB::raw('date_format(leave.app_todate, "%m/%d/%y") as tdate'),
                                'leave.app_days')
                            ->leftJoin('emp_master', 'emp_master.empno', '=', 'leave.empno')
                            ->orderBy('created_at', 'desc')
                            ->whereBetween('leave.fromdate', [$this->fromdate, $this->todate]);

        if(!empty($this->empno_name)){
            $lv_summary->where('leave.empno', $this->empno_name);
        }

        // COMPANY || DISTRICT || BRANCH
        if(!empty($this->entity01)){
            $lv_summary->where('emp_master.entity01', $this->entity01);
        }   
        if(!empty($entity02)){
            $lv_summary->where('emp_master.entity01', $this->entity01)->where('emp_master.entity02', $this->entity02);
        }
        if(!empty($entity03)){
            $lv_summary->where('emp_master.entity01', $this->entity01)->where('emp_master.entity02', $this->entity02)->where('emp_master.entity03', $this->entity03);
        }
        
        // // STATUS
        if($this->status == 'Approved_With_Pay'){
            $lv_summary->where('leave.wp_wop', 'wp')
                    ->where('leave.status', 'Approved');
        }elseif($this->status == 'Approved'){
            $lv_summary->where('leave.status', 'Approved');
        }
        elseif($this->status == 'Disapproved'){
            $lv_summary->where('leave.status', 'Disapproved');
        }

        // dd($this->entity01);
        $lv_summary = $lv_summary->get();
        return view('summary.leave.lv_summary_csv', ['lv_summary' => $lv_summary]);
    }

}
