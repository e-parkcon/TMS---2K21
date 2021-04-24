<?php

namespace App\Http\Controllers;

use App\Http\Controllers\traits\CheckOT;
use App\Http\Controllers\traits\OvertimeTrait;

use App\Http\Requests\OvertimeRequest;
use App\Http\Requests\UpdateOT;
use App\Jobs\SendOTMail;
use App\Models\Company;
use App\Models\DTR;
use App\Models\Overtime;
use App\Models\Shift;
use Response;
use Illuminate\Http\Request;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;
use PDF;

class OvertimeController extends Controller
{
    //
    use CheckOT;
    use OvertimeTrait;

    public function ot_dashboard(){

        return view('overtime.ot_dashboard');
    }

    public function ot_header(){

        $ot_list    =   Overtime::select(DB::raw('count(*) as no_of_ot, refno, date_format(overtime.created_at, "%d-%b-%Y") as date_file,
                                submitted'))
                                ->where('empno', Auth::user()->empno)
                                ->orderBy('created_at', 'desc')
                                ->groupBy('refno')
                                ->get();

        return Response::json(array('data'=>$ot_list));
    }

    public function overtime_lists($refno){

        $overtime   =   $this->overtime_list($refno, Auth::user()->empno);

        return view('overtime.overtime_lists')->with('ot_lists', $overtime);
    }


    public function overtime_post(OvertimeRequest $request){

        $app_group = Auth::user()->app_group('O'); // CHECK IF USER DOES HAVE APPROVING GROUP
        if(!$app_group->exists()){
            Session::flash('error', 'No Approving Group.');
            return back();
        }

        if(Auth::user()->app_officer('O') == null){ // CHECK IF USER DOES HAVE APPROVING OFFICER
            Session::flash('error', 'No Approving Officer.');
            return back();
        }

        if($this->ot_exists($request->timestart)){ // CHECK IF OVERTIME EXISTS
            Session::flash('error', 'Overtime already exists.');
            return back();
        }

        // SAVE OT TO DB
        $ot =   new Overtime;
        $ot->id         =   $this->ot_id();
        $ot->empno      =   Auth::user()->empno;
        $ot->dateot     =   $request->dateot;
        $ot->seqno      =   0;
        $ot->clientname =   $request->clientname;
        $ot->workdone   =   $request->workdone;
        $ot->timestart  =   $request->timestart;
        $ot->timefinish =   $request->timefinish;
        $ot->hours      =   $request->hours;
        $ot->refno      =   $this->ot_refno();
        $ot->app_code   =   $app_group->first()->app_code;
        $ot->ot_crypt   =   Auth::user()->app_officer('O')['app_crypt'];

        $ot->created_at =   date('Y-m-d H:i:s');
        $ot->save();

        // SAVE PDF OR IMAGE
        $this->upload_file($request, $ot);

        return redirect()->route('add_ot_form', ['refno' => $this->ot_refno()]);
    }


    public function add_new_ot($refno){

        try{

            $ot_unsub =   Overtime::where('refno', $refno)->where('overtime.empno', Auth::user()->empno)->where('submitted', 'N')->get();

            $x  =   0;
            $ots    =   [];
            foreach($ot_unsub as $ot){

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
                $ots[$x]['pdf_file_ot'] =   $ot->pdf_file_ot;
                $ots[$x]['shift']       =   $shift->shift;
                $ots[$x]['shift_desc']  =   Shift::where('shift', $shift->shift)->first()->desc;

                $x++;
            }

            // dd($ots);
            return view('overtime.add_ot_form')->with('refno', $refno)
                                            ->with('overtime', $ots);
        }
        catch(\Exception $e){
            
            return back()->withErrors('Something went wrong! Try to delete your overtime then file again.');
        }

    }

    public function post_new_ot(OvertimeRequest $ot_req, $refno){

        if($this->ot_exists($ot_req->timestart)){ // // CHECK IF OVERTIME EXISTS
            Session::flash('error', 'Overtime already exists!');
            return back();
        }

        $ot =   new Overtime;
        $ot->id         =   $this->ot_id();
        $ot->empno      =   Auth::user()->empno;
        $ot->seqno      =   $this->ot_seqno($refno);
        $ot->dateot     =   $ot_req->dateot;
        $ot->clientname =   $ot_req->clientname;
        $ot->workdone   =   $ot_req->workdone;
        $ot->timestart  =   $ot_req->timestart;
        $ot->timefinish =   $ot_req->timefinish;
        $ot->hours      =   $ot_req->hours;
        $ot->refno      =   $refno;
        $ot->app_code   =   Auth::user()->app_group('O')->first()->app_code;
        $ot->ot_crypt   =   Auth::user()->app_officer('O')['app_crypt'];

        $ot->created_at =   date('Y-m-d H:i:s');
        $ot->save();

        // SAVE PDF OR IMAGE
        $this->upload_file($ot_req, $ot);

        Session::flash('succes', 'New overtime added');
        return back();
    }

    public function update_overtime(UpdateOT $req, $refno){

        // if($this->ot_exists($update_req->timestart_edit)){
        //     Session::flash('error', 'Overtime already exists!');
        //     return back();
        // }

        $ot =   Overtime::where('id', $req->ot_id)
                        ->where('refno', $refno)->where('empno', Auth::user()->empno)
                        ->update([
                            'dateot'            =>      $req->dateot_edit,
                            'clientname'        =>      $req->clientname_edit,
                            'workdone'          =>      $req->workdone_edit,
                            'regsched_start'    =>      $req->regsched_start_edit,
                            'regsched_end'      =>      $req->regsched_end_edit,
                            'timestart'         =>      $req->timestart_edit,
                            'timefinish'        =>      $req->timefinish_edit,
                            'hours'             =>      $req->hours_edit,
                            // 'pdf_file_ot'       =>      $pdfName,
                        ]);

        $this->update_uploaded_file($req, Overtime::where('id', $req->ot_id)->where('refno', $refno)->where('empno', Auth::user()->empno)->first());

        Session::flash('success', 'Overtime updated!');
        return back();
    }

    private function update_uploaded_file(Request $req, $ot){

        $this->delete_file_uploaded($ot->refno, $req->ot_id); // DELETE UPLOADED FILE IN DIRECTORY

        $year       = date('Y', strtotime($req->timestart_edit));
        $month      = date('m', strtotime($req->timestart_edit));
        $day        = date('d', strtotime($req->timestart_edit));

        $files = $req->file('upload1');
        if($req->hasFile('upload1')){
            foreach($files as $key => $file){

                $dir   =   public_path() . '/uploaded_files/Overtime/' . $year .'/' . $month . '/' . $day; // DIRECTORY
                $fileName   =   date('YmdHisA') . '_' . $ot->seqno . '.' . $file->getClientOriginalExtension();

                $filees[] =   '/uploaded_files/Overtime/' . $year .'/' . $month . '/' . $day . '/' . $fileName;
                $file->move($dir, $fileName);
            }

            // UPDATE OT DB FOR pdf_file_ot column
            $ot->update([
                'pdf_file_ot'   =>  json_encode($filees, JSON_FORCE_OBJECT)
            ]);
        }
    }

    private function upload_file(Request $request, $ot){

        $year       = date('Y', strtotime($request->timestart));
        $month      = date('m', strtotime($request->timestart));
        $day        = date('d', strtotime($request->timestart));

        $files = $request->file('upload');
        if($request->hasFile('upload')){
            foreach($files as $key => $file){

                $dir   =   public_path() . '/uploaded_files/Overtime/' . $year .'/' . $month . '/' . $day; // DIRECTORY
                $fileName   =   date('YmdHisA') . '_' . $ot->seqno . '.' . $file->getClientOriginalExtension();

                $filees[] =   '/uploaded_files/Overtime/' . $year .'/' . $month . '/' . $day . '/' . $fileName;
                $file->move($dir, $fileName);
            }

            // UPDATE OT DB FOR pdf_file_ot column
            $ot->update([
                'pdf_file_ot'   =>  json_encode($filees, JSON_FORCE_OBJECT)
            ]);
        }

    }

    public function submit_overtime(Request $req){

        try{
            dispatch(new SendOTMail(Auth::user(), $req->refno)); // SEND TO APPROVER via EMAIL

            Overtime::where('refno', $req->refno)
                    ->update([
                        'submitted'     =>      'Y'
                    ]);

            // dispatch(new SendOTMail(Auth::user(), $req->refno))->delay(Carbon::now()->addSeconds(5)); //Change QUEUE_CONNECTION to database

            Session::flash('success', 'Successfully Filed!');
            return redirect()->route('ot_dashboard');
        }
        catch(Exception $e){
            Session::flash('error', $e->getMessage() . '. Application not proceed!');
            return back();
        }

    }

    public function delete_overtime(Request $request){

        $refno  =   $request->refno;
        $ot_id  =   $request->ot_id;

        $count_ot   =   Overtime::where('refno', $refno)->count();

        if($count_ot == 1){
            $this->delete_file_uploaded($refno, $ot_id);
            
            Overtime::where('id', $ot_id)->where('refno', $refno)->delete();

            return Response::json(1);
        }
        else{
            $this->delete_file_uploaded($refno, $ot_id);

            Overtime::where('id', $ot_id)->where('refno', $refno)->delete();

            return Response::json(0);
        }
    }

    public function delete_all_ot($refno){

        $overtime =   Overtime::where('refno', $refno)->get();

        foreach ($overtime as $ot) {
            if ($ot->pdf_file_ot != '') {
                $this->delete_file_uploaded($refno, $ot->id);
            }
        }

        Overtime::where('empno', Auth::user()->empno)
                            ->where('refno', $refno)->delete();        

        return Response::json('Overtime deleted!');
    }

    public function delete_file_uploaded($refno, $ot_id){
        
        $uploaded_file  =   Overtime::where('refno', $refno)->where('id', $ot_id)->first();

        if($uploaded_file->pdf_file_ot != ''){
            $files = json_decode($uploaded_file->pdf_file_ot, true);
            foreach($files as $file){
                unlink(public_path($file));
            }   
    
            $uploaded_file->update([
                'pdf_file_ot'   =>   ''
            ]);
        }

    }

    public function ot_refno(){
        $refno =   Auth::user()->entity01 . date('YmdHisA');

        return $refno;
    }

    public function ot_id(){
        $id  =   'OT-'.uniqid(Auth::user()->empno);

        return $id;
    }

    public function ot_seqno($refno){

        $ot_seqno   =   0;
        $seqno_no   =   Overtime::select('seqno')->where('empno', Auth::user()->empno)->where('refno', $refno)->orderBy('seqno', 'desc')->first();

        // return $seqno_no;
        if(empty($seqno_no)){
            return $ot_seqno;
        }else{
            return $ot_seqno = $seqno_no->seqno + 1;   
        }
    }

    public function todayShift(Request $request){

        $shift  =   DTR::select('shift.shift', 'shift.desc', 'shift.in', 'shift.out', 'shift.nextday_out')
                        ->where('empno', Auth::user()->empno)
                        ->where('txndate', $request->dateot)
                        ->leftJoin('shift', 'shift.shift', 'dtr.shift')
                        ->first();

        return Response::json($shift);
    }


    public function ot_form($refno, $ot_id, $empno){ // OT FORM PDF

        $ot_form    =   Overtime::select('overtime.*', 'emp_master.*', 'department.*')
                                ->where('overtime.refno', $refno)
                                ->join('emp_master', 'emp_master.empno', '=', 'overtime.empno')
                                // ->join('entity01', 'entity01.entity01', '=', 'emp_master.entity01')
                                ->join('department', function($dept){
                                    $dept->on('department.entity01', '=', 'emp_master.entity01')
                                        ->on('department.deptcode', '=', 'emp_master.deptcode');
                                })
                                ->first();

        $company    =   Company::where('entity01', $ot_form->entity01)->first()->entity01_desc;

        $ot_details =   Overtime::where('refno', $refno)->where('status', 'Approved')->orderBy('seqno', 'asc')->get();

        $ot_hrs =   Overtime::where('refno', $refno)->where('status', 'Approved')->sum('appr_hours');

        $pdf = PDF::loadView('overtime.ot_form', compact('ot_form', 'ot_details', 'ot_hrs', 'company'));
        return $pdf->stream('Overtime-Slip.pdf');
    }

}
