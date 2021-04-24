<?php

namespace App\Http\Controllers;

use App\Exports\LeaveLedgerCSV;
use App\Http\Requests\LeaveRequest;

use App\Models\Leave;
use App\Models\LeaveCredits;
use App\Models\LeaveType;
use Illuminate\Http\Request;

use App\Http\Controllers\traits\Approving;
use App\Http\Controllers\traits\CheckLeave;
use App\Http\Controllers\traits\Leave_Type;
use App\Http\Controllers\traits\Employee;

use App\Jobs\SendLeaveMail;
use App\Mail\LeaveMailable;
use App\Models\LeaveLedger;
use Auth;
use Exception;
use Illuminate\Support\Facades\Mail;
use Response;
use Session;
use Swift_TransportException;
use PDF;
use Excel;

class LeaveController extends Controller
{
    //
    use Approving;
    use CheckLeave;
    use Leave_Type;
    use Employee;

    public function leave_dashboard(){

        $emp_lv_credits =   $this->empl_lv_credits(Auth::user()->empno); // LEAVE CREDITS

        return view('leave.leave_dashboard')->with('lv_credits', $emp_lv_credits);
    }

    public function leave_list(){

        $leave  =   Leave::where('empno', Auth::user()->empno)->orderBy('created_at', 'desc')->get();

        $x  =   0;
        $emp_lv =   [];
        foreach($leave as $lv){
            $emp_lv[$x]['lv_id']        =   $lv->id;
            $emp_lv[$x]['empno']        =   $lv->empno;
            $emp_lv[$x]['lv_period']    =   date('d-M-Y', strtotime($lv->fromdate)) . ' to ' . date('d-M-Y', strtotime($lv->todate));
            $emp_lv[$x]['reason']       =   $lv->reason;

            $emp_lv[$x]['fromdate']     =   $lv->fromdate;
            $emp_lv[$x]['todate']       =   $lv->todate;
            $emp_lv[$x]['days']         =   $lv->total_day;

            $emp_lv[$x]['lv_code']      =   $lv->leavecode;
            $emp_lv[$x]['lv_desc']      =   $this->leave_type()->where('lv_code', $lv->leavecode)->first()->lv_desc;

            $emp_lv[$x]['app_fromdate'] =   $lv->app_fromdate;
            $emp_lv[$x]['app_todate']   =   $lv->app_todate;
            $emp_lv[$x]['app_days']     =   $lv->app_days;

            $emp_lv[$x]['pdf_file_leave']   =   $lv->pdf_file_leave != null || !empty($lv->pdf_file_leave) ? $lv->pdf_file_leave : '';

            $emp_lv[$x]['w_pay_or_wo_pay']  =   $lv->wp_wop;
            $emp_lv[$x]['date_file']       =   date('d-M-Y', strtotime($lv->created_at));

            $emp_lv[$x]['status']       =   '';
            if($lv->status == 'Approved'){
                $emp_lv[$x]['status']       =   'Approved';
            }
            elseif($lv->status == 'Disapproved'){
                $emp_lv[$x]['status']       =   'Disapproved';
            }
            elseif($lv->status == 'Cancelled'){
                $emp_lv[$x]['status']       =   'Cancelled';
            }
            else{
                $emp_lv[$x]['status']       =   'Pending';
            }

            $x++;
        }

        return Response::json(array('data'=>$emp_lv));
    }

    public function leave_post(LeaveRequest $request){
        
        $app_group = Auth::user()->app_group('L'); // CHECK IF USER DOES HAVE APPROVING GROUP
        if(!$app_group->exists()){
            Session::flash('error', 'No Approving Group.');
            return back();
        }
        
        if(Auth::user()->app_officer('L') == null){ // CHECK IF USER DOES HAVE APPROVING OFFICER
            Session::flash('error', 'No Approving Officer.');
            return back();
        }

        if($this->leave_exists(Auth::user()->empno, $request->fromdate, $request->todate)){ // CHECK IF LEAVE EXISTS
            Session::flash('error', 'Leave already exists!');
            return back();
        }
            

        $date_range =   $this->dateRange($request->todate); // CHECK IF SL LATE FILED
        if($date_range > 3 && $request->leavecode == 'SL'){
        // if(){
            Session::flash('error', 'Filing of ' . $this->leave_type()->where('lv_code', $request->leavecode)->first()->lv_desc . ' is atleast 3 days after you get sick.');
            return back();
        }
        
        $fromDate   =   strtotime($request->fromdate);
        if($fromDate < strtotime('+2 days') && $request->leavecode == 'VL'){ // CHECK IF VL IS LATE FILED
            Session::flash('error', 'Filing of '. $this->leave_type()->where('lv_code', $request->leavecode)->first()->lv_desc .' is at least three days before your actual leave date.');
            return back();
        }

        try{

            // dispatch(new SendLeaveMail(Auth::user()))->delay(Carbon::now()->addSeconds(5)); //Change QUEUE_CONNECTION to database

            // SAVE LEAVE TO DB
            $lv =   new Leave;
            $lv->id             =   $this->leave_id();
            $lv->empno          =   Auth::user()->empno;
            $lv->fromdate       =   $request->fromdate;
            $lv->todate         =   $request->todate;
            $lv->total_day      =   $request->no_days;
            $lv->app_fromdate   =   $request->fromdate;
            $lv->app_todate     =   $request->todate;
            $lv->app_days       =   $request->no_days;
            $lv->leavecode      =   $request->leavecode;
            $lv->reason         =   $request->reason;
            $lv->app_code       =   $app_group->first()->app_code;
            $lv->leave_crypt    =   Auth::user()->app_officer('L')['app_crypt'];
            $lv->created_at     =   date('Y-m-d H:i:s');
            $lv->save();
            
            // EMAIL TO APPROVING OFFICER
            dispatch(new SendLeaveMail(Auth::user(), $lv)); // EMAIL TO APPROVING OFFICER

            // SAVE PDF
            $this->save_pdf_file($request, $lv);

            Session::flash('success', 'Leave Successfully Filed!');
            return back();

        }
        catch(Swift_TransportException $err){
            return back()->withErrors('Mail Error. Application not proceed!');
        }
        catch(Exception $e){
            Session::flash('error', 'Something went wrong. Application not proceed!');
            return back();
        }
    }

    public function leave_update(Request $request){

        $date_range =   $this->dateRange($request->edt_todate); // CHECK IF SL IS LATE FILED
        if($date_range > 3 && $request->edt_lv_type == 'SL'){
            Session::flash('error', 'Filing of ' . $this->leave_type()->where('lv_code', $request->edt_lv_type)->first()->lv_desc . ' is atleast 3 days after you get sick.');
            return back();
        }
        
        $fromDate   =   strtotime($request->edt_fromdate); // CHECK IF VL IS LATE FILED
        if($fromDate < strtotime('+2 days') && $request->edt_lv_type == 'VL'){
            Session::flash('error', 'Filing of '. $this->leave_type()->where('lv_code', $request->edt_lv_type)->first()->lv_desc .' is at least three days before your actual leave date.');
            return back();
        }

        // UPDATE RECORDS IN DB
        Leave::where('id', $request->lv_id)->where('empno', Auth::user()->empno) 
                                        ->update([
                                            'fromdate'        =>    $request->edt_fromdate,
                                            'todate'          =>    $request->edt_todate,
                                            'total_day'       =>    $request->edt_no_days,
                                            'app_fromdate'    =>    $request->edt_fromdate,
                                            'app_todate'      =>    $request->edt_todate,
                                            'app_days'        =>    $request->edt_no_days,
                                            'reason'          =>    $request->edt_reason,
                                            // 'pdf_file_leave'  =>    $pdfName,
                                            'leavecode'       =>    $request->edt_lv_type,
                                        ]);

        Session::flash('success', 'Successfully Save Changes!');
        return back();

    }

    public function leave_cancel(Request $request){
        
        // $this->delete_leave_file($request->lv_id, Auth::user()->empno);

        Leave::where('id', $request->lv_id)->where('empno', Auth::user()->empno)->delete(); // DELETE LEAVE WHEN CANCEL
        return Response::json('Leave Successfully Cancelled!');
    }

    public function leave_id(){

        $lv_id  =   'LV-' . uniqid(Auth::user()->empno);

        return $lv_id;
    }

    private function save_pdf_file(Request $request, $lv){

        // PDF File Formatting.
        $year       = date('Y', strtotime($request->fromdate));
        $month      = date('m', strtotime($request->fromdate));
        $day        = date('d', strtotime($request->fromdate));

        $files = $request->file('pdf');
        if($request->hasFile('pdf')){
            foreach($files as $key => $file){

                $dir   =   public_path() . '/uploaded_files/Leave/' . $year .'/' . $month . '/' . $day; // DIRECTORY
                $fileName   =   date('YmdHisA') . '_' . $lv->seqno . '.' . $file->getClientOriginalExtension();

                // $filees[] =   '/uploaded_files/Leave/' . $year .'/' . $month . '/' . $day . '/' . $fileName;
                $filees =   '/uploaded_files/Leave/' . $year .'/' . $month . '/' . $day . '/' . $fileName;
                $file->move($dir, $fileName);
            }

            // UPDATE OT DB FOR pdf_file_ot column
            $lv->update([
                // 'pdf_file_leave'   =>  json_encode($filees, JSON_FORCE_OBJECT)
                'pdf_file_leave'    =>  $filees
            ]);
        }
    }

    private function delete_leave_file($lv_id, $empno){
        
        $uploaded_file  =   Leave::where('id', $lv_id)->where('empno', $empno)->first();

        unlink(public_path($uploaded_file->pdf_file_leave));
    }

    public function leave_ledger(Request $request){
        $emp_leave  =   LeaveCredits::where('empno', Auth::user()->empno)
                            ->select('emp_leave.*', 'leave_type.lv_desc')
                            ->leftJoin('leave_type', 'leave_type.lv_code', 'emp_leave.lv_code')->get();
        
        $lv_ledger  =   LeaveLedger::select('leave_ledger.*', 'leave_type.lv_desc')
                            ->leftJoin('leave_type', 'leave_type.lv_code', 'leave_ledger.lv_code')
                            ->where('leave_ledger.empno', Auth::user()->empno)
                            ->orderBy('leave_ledger.txndate', 'desc');
                            // ->get();

        if(!empty($request->lv_code) && $request->from_date == '' && $request->to_date == ''){
            $lv_ledger = $lv_ledger->where('leave_ledger.lv_code', $request->lv_code);
        }
        elseif($request->lv_code == 'All' && $request->from_date != '' && $request->to_date != ''){
            $lv_ledger = $lv_ledger->whereBetween('leave_ledger.txndate', [$request->from_date, $request->to_date]);
        }
        elseif($request->lv_code != '' && $request->from_date != '' && $request->to_date != ''){
            $lv_ledger = $lv_ledger->where('leave_ledger.lv_code', $request->lv_code)
                                    ->whereBetween('leave_ledger.txndate', [$request->from_date, $request->to_date]);
        }

        $lv_ledger = $lv_ledger->paginate(10);
        return view('leave.leave_ledger')->with('lv_ledger', $lv_ledger)
                                                    ->with('emp_leave', $emp_leave)
                                                    ->with('from_date', $request->from_date)
                                                    ->with('to_date', $request->to_date);
    }

    //Leave Ledger
    public function lv_ledger_pdf(Request $request){
        

        $lv_code = $request->lv_code;
        $leave_header   =   LeaveCredits::where('emp_leave.empno', Auth::user()->empno)
                                                ->leftJoin('leave_type', 'leave_type.lv_code', 'emp_leave.lv_code')->get();
        $lv_type    =   LeaveType::where('lv_code', $request->lv_code)->first(); 

        $leave_ledger   =   LeaveLedger::select('leave_ledger.*', 'leave_type.lv_desc')
                                                ->leftJoin('leave_type', 'leave_type.lv_code', 'leave_ledger.lv_code')
                                                ->where('leave_ledger.empno', $request->empno)
                                                ->orderBy('leave_ledger.lv_code', 'asc');
        
        if($request->lv_code == '' && $request->from_date == '' && $request->to_date == ''){
            $leave_ledger = $leave_ledger->where('leave_ledger.lv_code', $request->lv_code);
        }
        elseif($request->lv_code == 'All' && $request->from_date != '' && $request->to_date != ''){
            $leave_ledger = $leave_ledger->whereBetween('leave_ledger.txndate', [$request->from_date, $request->to_date]);
        }
        elseif($request->lv_code != '' && $request->from_date != '' && $request->to_date != ''){
            $leave_ledger = $leave_ledger->where('leave_ledger.lv_code', $request->lv_code)
                                ->whereBetween('leave_ledger.txndate', [$request->from_date, $request->to_date]);
        }

        $paper = 'portrait';
        if($request->lv_code == 'All'){
            $paper = 'landscape';
        }

        $leave_ledger = $leave_ledger->get();
        // return view('reports.lv_ledger-pdf')->with('leave_ledger', $leave_ledger)->with('lv_type', $lv_type)
        //                                     ->with('lv_code', $lv_code)
        //                                     ->with('leave_header'. $leave_header);
        $pdf = PDF::loadView('leave.lv_ledger-pdf', compact(['leave_ledger', 'lv_type', 'lv_code', 'leave_header']))->setPaper('a4', $paper);
        return $pdf->stream(Auth::user()->empno . '-Leave Ledger.pdf');
    }


    public function leave_ledger_csv(Request $request){
        return Excel::download(new LeaveLedgerCSV($request->lv_code, $request->from_date, $request->to_date), Auth::user()->empno . '-Leave Ledger' . '.csv');
    }

    public function lv_form($id, $empno){ // LEAVE FORM PDF

        $lv_form    =   Leave::where('id', $id)
                            ->join('emp_master', 'emp_master.empno', '=', 'leave.empno')
                            ->join('leave_type', 'leave_type.lv_code', '=', 'leave.leavecode')->first();

        $pdf = PDF::loadView('leave.lv_form', compact('lv_form'));
        return $pdf->stream('Leave Form.pdf');
    }

}
