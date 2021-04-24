<?php

namespace App\Http\Controllers;

use App\Imports\ImportLeaveCredits;
use App\Imports\ImportSchedule;
use App\Models\DTR;
use App\Models\LeaveCredits;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Session;
use App\User;
use Auth;

class ImportingFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // IMPORT SCHEDULE
        $imp_schedule   =   UploadLog::where('type', 'sch')
                                    ->join('emp_master', 'emp_master.empno', '=', 'upload_log.name_uploader')
                                    ->orderBy('date', 'desc');

        // IMPORT LEAVe
        $imp_lv =   UploadLog::where('type', 'lv')
                            ->join('emp_master', 'emp_master.empno', '=', 'upload_log.name_uploader')
                            ->orderBy('date', 'desc');

        $imp_schedule = $imp_schedule->paginate(10);
        $imp_lv = $imp_lv->paginate(10);
        return view('utilies.import_files.import_files')->with('imp_schedule', $imp_schedule)
                                                        ->with('imp_lv', $imp_lv);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'csv_file_sched'  =>  'required|mimes:csv,txt'
        ]);

        $row = (new ImportSchedule)->toArray($request->file('csv_file_sched'));

        $file = Input::file('csv_file_sched');
        $filename = $file->getClientOriginalName();
        $file->move('imported_files/', $filename);

        // $x = 1;
        foreach($row as $key => $value){

            // $startDate  =   $row[$key][0][1];
            // $endDate    =   end($row[$key][0]);
            // dd($startDate, $endDate);
            // $start      =   date('Y-m-d', strtotime($startDate));
            // $end        =   date('Y-m-d', strtotime($endDate));
            $txndate    =   array_slice($row[$key][0], 1);
            $row_keys   =   array_keys($value);
            $first_key  =   $row_keys[1];
            $last_key   =   end($row_keys);
            
            // if(count($txndate) == 0){
            //     Session::flash('error', 'Error!');
            //     return back();
            // }

            $count      =   0;
            $sched      =   [];
            for($i=$first_key; $i <= $last_key; $i++){

                $value_key = array_slice($value[$i], 1);
                
                for($x=0; $x < count($value_key); $x++){

                    if(!is_numeric($value[$i][0])){
                        Session::flash('error', 'Employee Column is not numeric!');
                        return back();
                    }
                    else{
                        $employee = User::where('empno', trim($value[$i][0]))->exists();
                    }

                    if($employee == true){
                        $shift   =  $value_key[$x];

                        $sched[$count]['empno']     =   trim($value[$i][0]);
                        $sched[$count]['txndate']   =   trim($txndate[$x]);
                        $sched[$count]['shift']     =   trim(strtoupper($shift));
    
                        $count++;
                    }
                }
                
            }
            
            $file = fopen('schedule/'.$filename, "w");
            foreach ($sched as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
        }

        $csv = file('schedule/'.$filename);
        
        $datas   =   (array_chunk($csv, 2000));
        foreach($datas as $index => $data){
            $filename1  =   resource_path('csv-schedule-files/' . $index . '.csv');
            
            file_put_contents($filename1, $data);
        }

        (new DTR())->importToDB();

        UploadLog::insert([
                    'filename'      =>  $filename,
                    'name_uploader' =>  Auth::user()->empno,
                    'date'          =>  date('Y-m-d'),
                    'time'          =>  date('H:i:s'),
                    'type'          =>  'sch',
                    // 'entity01'      =>  $request->cocode
                ]);

        // Excel::import(new ImportSchedule(), $filename . '.csv');
        Session::flash('success', 'Schedule Successfully Imported!');
        return back();
    }

    public function import_lv_credits(Request $request){

        $this->validate($request,[
            'file-leave'  =>  'required|mimes:csv,txt'
        ]);

        if($request->hasFile('file-leave')){

            $leaves = (new ImportLeaveCredits)->toArray($request->file('file-leave'));
            // dd($leaves);

            $file = Input::file('file-leave');
            $filename = $file->getClientOriginalName();
            // $file->move('imported_files/', $filename);

            // dd($leaves); 

            foreach($leaves as $key => $value){

                $lv_credit    =   array_slice($leaves[$key][0], 1);

                $row_keys   =   array_keys($value);
                $first_key  =   $row_keys[1];
                $last_key   =   end($row_keys);

                $count      =   0;
                $emp_leave      =   [];
                for($i=0; $i <= $last_key; $i++){

                    $value_key = array_slice($value[$i], 1);
                    
                    for($x=0; $x < count($value_key); $x++){

                        if(!is_numeric(trim($value[$i]['empno']))){
                            Session::flash('error', 'Employee Column is not numeric!');
                            return back();
                        }

                        if(!User::where('empno', trim($value[$i]['empno']))->exists()){
                            Session::flash('error', trim($value[$i]['empno']) . ' does not exists!');
                            return back();
                        }

                        $lv_code    =   array_keys($value_key)[$x];
                        $lv_balance =   $value_key[array_keys($value_key)[$x]];

                        $emp_leave[$count]['empno']     =   trim($value[$i]['empno']);
                        $emp_leave[$count]['lv_code']   =   $lv_code;
                        $emp_leave[$count]['lv_balance']     =  $lv_balance;

                        $count++;
                    }
                    // dd($emp_leave);
                }

                $file = fopen('leave_credits/'.$filename, "w");
                foreach ($emp_leave as $line) {
                    fputcsv($file, $line);
                }
                fclose($file);

            }

            $csv = file('leave_credits/'.$filename);
        
            $datas   =   (array_chunk($csv, 2000));
            foreach($datas as $index => $data){
                $filename1  =   resource_path('csv-schedule-files/' . $index . '.csv');
                
                file_put_contents($filename1, $data);
            }

            (new LeaveCredits())->importLeaveCredits();

            // UPLOAD LOG
            UploadLog::insert([
                        'filename'      =>      $filename,
                        'name_uploader' =>      Auth::user()->empno,
                        'date'          =>      date('Y-m-d'),
                        'time'          =>      date('H:i:s'),
                        'type'          =>      'lv',
                        // 'entity01'        =>      $request->cocode
                    ]);

            Session::flash('success', 'Leave Credits Successfully Imported!');
            return back();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
