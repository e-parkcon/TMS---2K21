<?php

namespace App\Exports;

use App\Models\LeaveCredits;
use App\Models\LeaveLedger;
use App\Models\LeaveType;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;

class LeaveLedgerCSV implements FromView
{
    protected $lv_code;
    protected $from_date;
    protected $to_date;

    function __construct($lv_code, $from_date, $to_date){
        $this->lv_code = $lv_code;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
    /**
    * @return \Illuminate\Support\Collection    
    */
    public function view() : View
    {
        //
        $lv_code = $this->lv_code;
        
        $leave_header   =   LeaveCredits::where('emp_leave.empno', Auth::user()->empno)
                                                ->leftJoin('leave_type', 'leave_type.lv_code', 'emp_leave.lv_code')->get();
        $leaves =   LeaveLedger::select('leave_ledger.*', 'leave_type.lv_desc')
                                                ->leftJoin('leave_type', 'leave_type.lv_code', 'leave_ledger.lv_code')
                                                ->where('leave_ledger.empno', Auth::user()->empno)
                                                ->orderBy('leave_ledger.lv_code', 'asc');

        $lv_type    =   LeaveType::where('lv_code', $this->lv_code)->first();

        if($this->lv_code != '' && $this->from_date == '' && $this->to_date == ''){
            $leaves = $leaves->where('leave_ledger.lv_code', $this->lv_code);
        }
        elseif($this->lv_code == 'All' && $this->from_date != '' && $this->to_date != ''){
            $leaves = $leaves->whereBetween('leave_ledger.txndate', [$this->from_date, $this->to_date]);
        }
        elseif($this->lv_code != '' && $this->from_date != '' && $this->to_date != ''){
            $leaves = $leaves->where('leave_ledger.lv_code', $this->lv_code)
                            ->whereBetween('leave_ledger.txndate', [$this->from_date, $this->to_date]);
        }

        $leaves = $leaves->get();
        // dd($this->lv_code);
        return view('leave.lv_ledger_csv')->with('leaves', $leaves)
                                            ->with('lv_type', $lv_type)
                                            ->with('lv_code', $lv_code)
                                            ->with('leave_header', $leave_header);
    }
}
