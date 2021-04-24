<?php

namespace App\Http\Controllers;

use App\Models\BranchHolidays;
use App\Models\DTR;
use App\Models\Holidays;
use App\Models\InOutRaw;
use Illuminate\Http\Request;
    
use App\Models\Leave;
use Auth;
use Response;
use App\User;
use App\Http\Controllers\traits\custom_pagination;
use App\Models\Branch;
use Illuminate\Support\Facades\Input;
use PDF;

class DailyLogsController extends Controller
{
    //
    use custom_pagination;

    public function daily_logs(Request $request){

        if(Auth::user()->level == 2 || Auth::user()->level == 3){

			$empno 	= 	User::where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})->where('active', 'Y')->orderBy('empno', 'asc')->get();

			$name 	= 	User::where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})->where('active', 'Y')->orderBy('fname', 'asc')->get();
		}
		
		if(Auth::user()->level == 5){
			$empno 	= 	User::where('entity01', Auth::user()->entity01)
							->where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})->where('active', 'Y')->orderBy('empno', 'asc')->get();

			$name 	= 	User::where('entity01', Auth::user()->entity01)
							->where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})->where('active', 'Y')->orderBy('fname', 'asc')->get();
		}

		if(Auth::user()->level == 6){
			$empno 	= 	User::where('entity01', Auth::user()->entity01)
							->where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})
							->where('entity03', Auth::user()->entity03)->where('active', 'Y')->orderBy('empno', 'asc')->get();

			$name 	= 	User::where('entity01', Auth::user()->entity01)
							->where(function($brch){
								$brch->where('entity03', Auth::user()->entity03);
								$brch->orWhere('entity03', 'P'.Auth::user()->entity03);
								$brch->orWhere('entity03', 'M'.Auth::user()->entity03);
							})
							->where('entity03', Auth::user()->entity03)->where('active', 'Y')->orderBy('fname', 'asc')->get();
		}
		
    	if (Auth::user()->level == 0 || Auth::user()->level == 1 || Auth::user()->level == 7 || Auth::user()->level == 8 || Auth::user()->level >= 9) {
			$empno 	= 	User::where('active', 'Y')->orderBy('empno', 'asc')->get();
			$name 	= 	User::where('active', 'Y')->orderBy('fname', 'asc')->get();
		}

		// $txn = $this->daily_txn(Auth::user()->empno, Auth::user()->entity01, Auth::user()->entity03);
		// $daily_logs = $txn;
		// $custom_paginate = $this->custom_paginate($request, $daily_logs, count($daily_logs));

		$holiday_brch 	= 	$this->holiday_branch($request->entity01, $request->entity03);
		$daily_logs = 	Dtr::select('dtr.empno', 'dtr.txndate', 
									'dtr.in', 'dtr.in_manual',
									'dtr.break_out', 'dtr.break_out_manual',
									'dtr.break_in', 'dtr.break_in_manual',
									'dtr.out', 'dtr.out_manual',
									'dtr.nextday_out',
									'dtr.hrs_work', 'dtr.am_late', 'dtr.pm_late',
									'dtr.am_undertime', 'dtr.pm_undertime', 'dtr.shift',
									'dtr.lv_code'
									, 'leave_type.*'
									)
							->leftJoin('leave_type', 'leave_type.lv_code', '=', 'dtr.lv_code')
							->orderBy('dtr.txndate', 'asc');

		// // NAME OR ID No.
		if(!empty($request->empno)){
			$daily_logs->where('dtr.empno', $request->empno)
								->whereBetween('dtr.txndate', [$request->fromdate, $request->todate]);

			$branch	=	Branch::where('entity01', $request->entity01)->where('entity03', $request->entity03)->first()->entity03_desc;
		}
		else{
			
			if(Auth::user()->level == 0 && !empty($request->fromdate)){
				$logs	=	$daily_logs->where('dtr.empno', Auth::user()->empno)
										->whereBetween('dtr.txndate', [$request->fromdate, $request->todate]);
			}
			else{
				$logs	=	$daily_logs->where(function ($dtrs){
								$dtrs->whereRaw('MONTH(dtr.txndate) = ?', [date('m')])
									->whereRaw('YEAR(dtr.txndate) = ?', [date('Y')]);
							})
							->where('dtr.empno', Auth::user()->empno);
			}

			$branch	=	'';
			$daily_logs	=	$logs;
		}
		
		$daily_logs	=	$daily_logs->get();
		$txn = [];
		$x = 0;
		foreach($daily_logs as $logs){
			$txn[$x]['empno']	        =	$logs->empno;
			$txn[$x]['txndate']	        =	$logs->txndate;
			$txn[$x]['shift']	        =	$logs->shift;
			$txn[$x]['in']		        =	$logs->in;
			$txn[$x]['in_manual']		=	$logs->in_manual;
			$txn[$x]['break_out']	    =	$logs->break_out;
			$txn[$x]['break_out_manual']	=	$logs->break_out_manual;
			$txn[$x]['break_in']	    =	$logs->break_in;
			$txn[$x]['break_in_manual']	=	$logs->break_in_manual;
			$txn[$x]['out']		        =	$logs->out;
			$txn[$x]['out_manual']		=	$logs->out_manual;
			$txn[$x]['nextday_out']	    =	$logs->nextday_out;
			$txn[$x]['hrs_work']	    =	$logs->hrs_work;
			$txn[$x]['am_late']		    =	$logs->am_late;
			$txn[$x]['pm_late']		    =	$logs->pm_late;
			$txn[$x]['am_undertime']	=	$logs->am_undertime;
			$txn[$x]['pm_undertime']	=	$logs->pm_undertime;
			$txn[$x]['lv_code']		    =	$logs->lv_code;
			$txn[$x]['lv_desc']		    =	$logs->lv_desc;
			$txn[$x]['hol_date']	    =	'';
			$txn[$x]['hol_desc']	    =	'';

			foreach($holiday_brch as $hol_brch){
				$holiday    =   Holidays::where('hol_date', $hol_brch)->first();

				if($logs->txndate == $hol_brch){
					$txn[$x]['hol_date']	=	$holiday->hol_date;
					$txn[$x]['hol_desc']	=	$holiday->hol_desc;
				}
			}

			$x++;
		}
		
		$daily_logs = 	$txn;

		$custom_paginate 	= 	$this->custom_paginate($request, $daily_logs, count($daily_logs));

        return view('logs.daily_logs.daily_logs', ['daily_logs' => $custom_paginate[0]->appends(Input::except('page'))])
                                            ->with('empno', $empno)
											->with('name', $name)
											->with('branch', $branch);
    }

    public function dayoff(){

        $dayoff =   DTR::select('txndate')->where('empno', Auth::user()->empno)
                                        ->whereRaw('YEAR(txndate) = ?', [date('Y')])
                                        ->where('shift', 'X')->pluck('txndate');

        return Response::json($dayoff);
    }

    public function in_out_logs($txndate, $empno) {
        $timeIn =   InOutRaw::select('txndate', 'txntime', 'empno')
                       ->where('txndate', '=', $txndate)
                       ->where('empno', '=', $empno)
                       ->orderBy('txntime')->get();

        return Response::json($timeIn);
    }

    public function holidays(){

        $hol_national   =   Holidays::select('hol_date')->whereRaw('YEAR(hol_date) = ?', [date('Y')])
                                                    ->where('hol_national', 'Y')
                                                    ->orderBy('hol_date', 'asc')
                                                    ->pluck('hol_date');

        $brch_holdays   =   BranchHolidays::select('hol_date')
                                            ->whereRaw('YEAR(hol_date) = ?', [date('Y')])
                                            ->where('entity01', Auth::user()->entity01)
                                            ->where('entity03', Auth::user()->entity03)
                                            ->orderBy('hol_date', 'asc')
                                            ->pluck('hol_date');

        $arr_holidays = [];
        foreach($hol_national as $hol_nat){
            array_push($arr_holidays, $hol_nat);
        }

        foreach($brch_holdays as $hol_brch){
            array_push($arr_holidays, $hol_brch);
        }

        return Response::json($arr_holidays);
    }

    private function holiday_branch($cocode, $brchcode){

        $hol_national   =   Holidays::select('hol_date')
                                            // ->whereRaw('YEAR(hol_date) = ?', [date('Y')])
                                            ->where('hol_national', 'Y')
                                            ->orderBy('hol_date', 'asc')
                                            ->pluck('hol_date');

        $hol_branch =   BranchHolidays::select('hol_date')
                                            // ->whereRaw('YEAR(hol_date) = ?', [date('Y')])
                                            ->where('entity01', $cocode)
                                            ->where('entity03', $brchcode)
                                            ->orderBy('hol_date', 'asc')
                                            ->pluck('hol_date');
        $arr_hol = [];
        foreach($hol_national as $hol_nat){
            array_push($arr_hol, $hol_nat);
        }

        foreach($hol_branch as $hol_brch){
            array_push($arr_hol, $hol_brch);
        }
    
        usort($arr_hol, array($this, 'array_date_sort'));

        return $arr_hol;
    }

    private function array_date_sort($a, $b) {
        return strtotime($a) - strtotime($b);
    }

    public function pdf_logs(Request $request){
        // dd($request);
        $empno  =   Auth::user()->level >= 2 ? $request->empno : Auth::user()->empno;

        $entity01  =   Auth::user()->level >= 2 ? $request->entity01 : Auth::user()->entity01;
        $entity03  =   Auth::user()->level >= 2 ? $request->entity03 : Auth::user()->entity03;

        $holiday_brch 	= 	$this->holiday_branch($request->entity01, $request->entity03);
		$daily_logs = 	DTR::select('dtr.empno', 'dtr.txndate', 
									'dtr.in', 'dtr.in_manual',
									'dtr.break_out', 'dtr.break_out_manual',
									'dtr.break_in', 'dtr.break_in_manual',
									'dtr.out', 'dtr.out_manual',
									'dtr.nextday_out',
									'dtr.hrs_work', 'dtr.am_late', 'dtr.pm_late',
									'dtr.am_undertime', 'dtr.pm_undertime', 'dtr.shift',
									'dtr.lv_code'
									, 'leave_type.*'
									)
                            ->leftJoin('leave_type', 'leave_type.lv_code', '=', 'dtr.lv_code')
                            ->where('dtr.empno', $empno)
						    ->whereBetween('dtr.txndate', [$request->fromdate, $request->todate])
							->orderBy('dtr.txndate', 'asc');

		$daily_logs	=	$daily_logs->get();

		$txn = [];
		$x = 0;
		foreach($daily_logs as $logs){
			$txn[$x]['empno']	        =	$logs->empno;
			$txn[$x]['txndate']	        =	$logs->txndate;
			$txn[$x]['shift']	        =	$logs->shift;
			$txn[$x]['in']		        =	$logs->in;
			$txn[$x]['in_manual']		=	$logs->in_manual;
			$txn[$x]['break_out']	    =	$logs->break_out;
			$txn[$x]['break_out_manual']	=	$logs->break_out_manual;
			$txn[$x]['break_in']	    =	$logs->break_in;
			$txn[$x]['break_in_manual']	=	$logs->break_in_manual;
			$txn[$x]['out']		        =	$logs->out;
			$txn[$x]['out_manual']		=	$logs->out_manual;
			$txn[$x]['nextday_out']	    =	$logs->nextday_out;
			$txn[$x]['hrs_work']	    =	$logs->hrs_work;
			$txn[$x]['am_late']		    =	$logs->am_late;
			$txn[$x]['pm_late']		    =	$logs->pm_late;
			$txn[$x]['am_undertime']	=	$logs->am_undertime;
			$txn[$x]['pm_undertime']	=	$logs->pm_undertime;
			$txn[$x]['lv_code']		    =	$logs->lv_code;
			$txn[$x]['lv_desc']		    =	$logs->lv_desc;
			$txn[$x]['hol_date']	    =	'';
			$txn[$x]['hol_desc']	    =	'';

			foreach($holiday_brch as $hol_brch){
				$holiday    =  Holidays::where('hol_date', $hol_brch)->first();

				if($logs->txndate == $hol_brch){
					$txn[$x]['hol_date']	=	$holiday->hol_date;
					$txn[$x]['hol_desc']	=	$holiday->hol_desc;
				}
			}

			$x++;
        }

        $daily_logs =   $txn;

        $branch	=	Branch::where('entity01', $entity01)->where('entity03', $entity03)->first()->entity03_desc;
        $name   =   Auth::user()->level >= 2 ? $empno . ' ' . $request->name : $empno . ' ' . Auth::user()->fname . ' ' . Auth::user()->lname;
        $fromdate_todate    =   'From : ' . date('F d, Y', strtotime($request->fromdate)) . ' To : ' . date('F d, Y', strtotime($request->todate)); 

        $pdf = PDF::loadView('logs.daily_logs.logs-report', compact('daily_logs', 'branch', 'name', 'fromdate_todate'))->setPaper('a4', 'portrait');
        return $pdf->stream('Daily Logs.pdf');
        // return view('daily_logs.logs-report')->with('daily_logs', $txn);
    }

}
