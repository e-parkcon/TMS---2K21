<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\District;
use App\Models\DTR;
use App\Models\InOutRaw;
use Illuminate\Http\Request;
use DB;
use Response;

class InquiryController extends Controller
{
    //
    public function attendance_inquiry(){

        $company = Company::select('entity01', 'entity01_desc')
                            ->groupBy('entity01_desc')
                            ->orderBy('entity01_desc', 'asc')->get();

        $district = District::select('entity02.entity02', 'entity02.entity02_desc')
                        ->groupBy('entity02.entity02_desc')
                        ->orderBy('entity02.entity02_desc', 'asc')->get();

        $branch = Branch::select('entity03.entity03', 'entity03.entity03_desc')
                        ->groupBy('entity03.entity03_desc')
                        ->orderBy('entity03.entity03_desc', 'asc')->get();

        $department = Department::select('department.deptcode', 'department.deptdesc')
                        ->groupBy('department.deptdesc')
                        ->orderBy('department.deptdesc', 'asc')->get();

        return view('logs.inquiry.inquiry_attendance', ['company'     => $company,
                                                        'district'    => $district, 
                                                        'branch'      => $branch,
                                                        'department'  => $department]);
    }

    public function attendance_inquiry_json(Request $request){

        $txndate    =   $request->txndate;
        $company    =   $request->company;
        $district   =   $request->district;
        $branch     =   $request->branch;
        $department =   $request->department;

        $select     =   $request->select1;

        if ($request->select1 == 'Absent') {
            $attendance =   DTR::select(DB::raw("'Absent' AS absent"),
                                        'dtr.txndate',
                                        'entity01.entity01_desc',
                                        'entity03.entity03_desc',
                                        'dtr.empno',
                                        DB::raw("concat(emp_master.lname,', ', emp_master.fname,' ', emp_master.mname) as empname"),
                                        'dtr.shift',
                                        'dtr.in',
                                        DB::raw("TIME_FORMAT(shift.in,'%h:%i %p') as sin"))
                                ->leftJoin('emp_master','emp_master.empno','=','dtr.empno')
                                ->leftJoin('entity01','entity01.entity01','=','emp_master.entity01')
                                ->leftJoin('entity02','entity02.entity02','=','emp_master.entity02')
                                ->leftJoin('entity03','entity03.entity03','=','emp_master.entity03')
                                ->leftJoin('shift','shift.shift','=','dtr.shift')
                                ->where('txndate','=', $txndate)
                                ->where('entity01.entity01', 'like', $company)
                                ->where('entity02.entity02_desc', 'like', $district)
                                ->where('entity03.entity03_desc', 'like', $branch)
                                ->where('emp_master.deptcode', 'like', $department)
                                ->whereRaw("dtr.in is null")
                                ->whereRaw("dtr.break_in is null")
                                ->whereRaw("dtr.break_out is null")
                                ->whereRaw("dtr.out is null")
                                ->where('dtr.shift', '<>', 'X')
                                ->whereRaw("NOT EXISTS(SELECT empno FROM `leave` WHERE `leave`.empno = dtr.empno AND fromdate >= '".$txndate."' AND todate <= '".$txndate."' AND `status` IN ('Approved', ''))")
                                ->groupBy('emp_master.empno');
                                // ->orderBy('entity01.entity01','entity03.entity03','emp_master.empno');

        } 
        elseif ($request->select1 == 'Present') {
            $attendance =   DTR::select(DB::raw("'Present' AS absent"),
                                        'dtr.txndate',
                                        'entity01.entity01_desc',
                                        'entity03.entity03_desc',
                                        'dtr.empno',
                                        DB::raw("concat(emp_master.lname,', ', emp_master.fname,' ', emp_master.mname) as empname"),
                                        'dtr.shift',
                                        'dtr.in',
                                        DB::raw("TIME_FORMAT(shift.in,'%h:%i %p') as sin"))
                                ->leftJoin('emp_master','emp_master.empno','=','dtr.empno')
                                ->leftJoin('entity01','entity01.entity01','=','emp_master.entity01')
                                ->leftJoin('entity02','entity02.entity02','=','emp_master.entity02')
                                ->leftJoin('entity03','entity03.entity03','=','emp_master.entity03')
                                ->leftJoin('shift','shift.shift','=','dtr.shift')
                                ->where('txndate','=', $txndate)
                                ->where('entity01.entity01', 'like', $company)
                                ->where('entity02.entity02_desc', 'like', $district)
                                ->where('entity03.entity03_desc', 'like', $branch)
                                ->where('emp_master.deptcode', 'like', $department)
                                ->whereRaw("(dtr.in is NOT null OR dtr.break_out is NOT null OR dtr.break_in is NOT null OR dtr.out is NOT null)")
                                ->groupBy('emp_master.empno');
                                // ->orderBy('entity01.entity01','entity03.entity03','emp_master.empno');
        } 
        elseif($request->select1 == 'On-Leave') {
            $attendance =   DTR::select(DB::raw("'On-Leave' AS absent"),
                                        'dtr.txndate',
                                        'entity01.entity01_desc',
                                        'entity03.entity03_desc',
                                        'dtr.empno',
                                        DB::raw("concat(emp_master.lname,', ', emp_master.fname,' ', emp_master.mname) as empname"),
                                        'dtr.shift',
                                        'dtr.in',
                                        DB::raw("TIME_FORMAT(shift.in,'%h:%i %p') as sin"))
                                ->leftJoin('emp_master','emp_master.empno','=','dtr.empno')
                                ->leftJoin('entity01','entity01.entity01','=','emp_master.entity01')
                                ->leftJoin('entity02','entity02.entity02','=','emp_master.entity02')
                                ->leftJoin('entity03','entity03.entity03','=','emp_master.entity03')
                                ->leftJoin('shift','shift.shift','=','dtr.shift')
                                ->where('txndate','=', $txndate)
                                ->where('entity01.entity01', 'like', $company)
                                ->where('entity02.entity02_desc', 'like', $district)
                                ->where('entity03.entity03_desc', 'like', $branch)
                                ->where('emp_master.deptcode', 'like', $department)
                                ->whereRaw("dtr.in is null")
                                ->whereRaw("dtr.break_in is null")
                                ->whereRaw("dtr.break_out is null")
                                ->whereRaw("dtr.out is null")
                                ->where('dtr.shift', '<>', 'X')
                                ->whereRaw("EXISTS(SELECT empno FROM `leave` WHERE `leave`.empno = dtr.empno AND fromdate >= '".$txndate."' AND todate <= '".$txndate."' AND `status` IN ('Approved', ''))")
                                ->groupBy('emp_master.empno');
                                // ->orderBy('entity01.entity01','entity03.entity03','emp_master.empno');
        } 
        else {
            $attendance =   DTR::select('*')->where('entity01', '=', '');
        }

        $attendance =   $attendance->get();
        return response()->json(array('data'=>$attendance));
    }

    public function branch_log(){

        $company = Company::select('entity01', 'entity01_desc')
                            ->groupBy('entity01_desc')
                            ->orderBy('entity01_desc', 'asc')->get();

        $branch = Branch::select('entity03.entity03', 'entity03.entity03_desc')
                            ->groupBy('entity03.entity03_desc')
                            ->orderBy('entity03.entity03_desc', 'asc')->get();

        return view('logs.inquiry.branch_log', ['company' => $company, 'branch' => $branch]);
    }

    public function branch_log_json(Request $request){
        $fromdate   =   $request->fromdate;
        $todate     =   $request->todate;
        $company    =   $request->company;
        $branch     =   $request->branch;

        if (!empty($fromdate) && !empty($todate)) {
            $branchLog  =   DB::table('entity03 AS e3')
                                ->select('e3.entity01',
                                         'e3.entity03',
                                         'e3.entity03_desc',
                                         'entity01.entity01_desc',
                                         DB::raw("(select count(*) FROM
                                (
                                select * from (select
                                ('squareGreen') AS color,
                                dtr.empno,
                                CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                                entity01_desc,
                                dtr.txndate,
                                dtr.shift,
                                TIME_FORMAT(dtr.in, '%h:%i %p') AS time_in,
                                inout_raw.serialno AS unit,
                                inout_raw.seqno,
                                inout_raw.status,
                                dtr.in as date_time,
                                entity03_desc
                                
                                FROM dtr

                                LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                                LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                                LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                                LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                                WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                                AND inout_raw.txndate = DATE(dtr.in)
                                AND TIME_FORMAT(dtr.in, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                                AND dtr.in IS NOT NULL
                                AND emp_master.entity01 like '".$company."' 
                                AND emp_master.active = 'Y'
                                AND dtr.shift != 'X'
                                GROUP BY empno, txndate, time_in

                                UNION ALL

                                select
                                ('squareYellow') AS color,
                                dtr.empno,
                                CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                                entity01_desc,
                                dtr.txndate,
                                dtr.shift,
                                TIME_FORMAT(dtr.break_out, '%h:%i %p') AS time_in,
                                inout_raw.serialno AS unit,
                                inout_raw.seqno,
                                inout_raw.status,
                                dtr.break_out as date_time,
                                entity03_desc
                                
                                FROM dtr

                                LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                                LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                                LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                                LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                                WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                                AND inout_raw.txndate = DATE(dtr.break_out)
                                AND TIME_FORMAT(dtr.break_out, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                                AND dtr.break_out IS NOT NULL
                                AND emp_master.entity01 like '".$company."' 
                                AND emp_master.active = 'Y'
                                AND dtr.shift != 'X'
                                GROUP BY empno, txndate, time_in

                                UNION ALL

                                select
                                ('squareGreen') AS color,
                                dtr.empno,
                                CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                                entity01_desc,
                                dtr.txndate,
                                dtr.shift,
                                TIME_FORMAT(dtr.break_in, '%h:%i %p') AS time_in,
                                inout_raw.serialno AS unit,
                                inout_raw.seqno,
                                inout_raw.status,
                                dtr.break_in as date_time,
                                entity03_desc
                                
                                FROM dtr

                                LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                                LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                                LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                                LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                                WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                                AND inout_raw.txndate = DATE(dtr.break_in)
                                AND TIME_FORMAT(dtr.break_in, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                                AND dtr.break_in IS NOT NULL
                                AND emp_master.entity01 like '".$company."' 
                                AND emp_master.active = 'Y'
                                AND dtr.shift != 'X'
                                GROUP BY empno, txndate, time_in

                                UNION ALL

                                select
                                ('squareYellow') AS color,
                                dtr.empno,
                                CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                                entity01_desc,
                                dtr.txndate,
                                dtr.shift,
                                TIME_FORMAT(dtr.out, '%h:%i %p') AS time_in,
                                inout_raw.serialno AS unit,
                                inout_raw.seqno,
                                inout_raw.status,
                                dtr.out as date_time,
                                entity03_desc
                                
                                FROM dtr

                                LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                                LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                                LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                                LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                                WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                                AND inout_raw.txndate = DATE(dtr.out)
                                AND TIME_FORMAT(dtr.out, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                                AND dtr.out IS NOT NULL
                                AND emp_master.entity01 like '".$company."' 
                                AND emp_master.active = 'Y'
                                AND dtr.shift != 'X'
                                GROUP BY empno, txndate, time_in

                                UNION ALL

                                select 
                                IF((@row_number:=CASE WHEN @empno = dtr.empno THEN @row_number + 1 ELSE 1 END) %2 !=0 , 'squareGreen','squareYellow') AS color,
                                @empno := dtr.empno as empno, 
                                concat(emp_master.lname, ' ' , emp_master.fname,' ', emp_master.mname) as empname,
                                entity01_desc,
                                dtr.txndate, 
                                dtr.shift,
                                TIME_FORMAT(txntime, '%h:%i %p') AS time_in, 
                                serialno AS unit, 
                                seqno, 
                                status,
                                concat(inout_raw.txndate, ' ', txntime) as date_time,
                                entity03_desc

                                FROM dtr

                                LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                                LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                                LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                                LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                                WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                                AND inout_raw.txndate = dtr.txndate
                                AND dtr.in IS NULL
                                AND emp_master.entity01 like '".$company."' 
                                AND emp_master.active = 'Y'
                                AND dtr.shift != 'X'
                                GROUP BY empno, txndate, time_in) as b1

                                group by empno, txndate, time_in

                                ) AS brchlog

                                WHERE entity03_desc = e3.entity03_desc) as notxn")
                                       )
                                ->leftJoin('entity01', 'entity01.entity01', '=', 'e3.entity01')
                                ->where('e3.entity01', 'like', $company )
                                ->where('e3.entity03_desc', 'like', $branch )
                                ->groupBy('entity03_desc')
                                ->orderBy('e3.entity03_desc')->get();

            } else {
                $branchLog =    DB::table('entity03 AS e3')
                                ->select('e3.entity01',
                                         'e3.entity03',
                                         'e3.entity03_desc',
                                         'entity01.entity01_desc',
                                         DB::raw("( select count(*) FROM inout_raw WHERE entity03 = e3.entity03 AND txndate BETWEEN '' AND '') AS notxn"))
                                ->leftJoin('entity01', 'entity01.entity01', '=', 'e3.entity01')
                                ->where('e3.entity01', 'like', '' )
                                ->where('e3.entity03_desc', 'like', '' )
                                ->orderBy('e3.entity03_desc')->get();

            }

        return response()->json(array('data' => $branchLog));
    }


    public function showBrchlog($fromdate, $todate, $company, $branch) {

        // $fromdate   =   $request->fromdate;
        // $todate     =   $request->todate;
        // $company    =   $request->company;
        // $branch     =   $request->branch;
        
        $brchlist   =   DB::table(DB::raw("(select * FROM
                        (
                        select
                        (CASE WHEN dtr.shift != 'X' THEN 'squareGreen' ELSE 'squareBlue' END) AS color,
                        dtr.empno,
                        CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                        entity01_desc,
                        dtr.txndate,
                        dtr.shift,
                        TIME_FORMAT(dtr.in, '%h:%i %p') AS time_in,
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.in as date_time
                        
                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND inout_raw.txndate = DATE(dtr.in)
                        AND TIME_FORMAT(dtr.in, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                        AND dtr.in IS NOT NULL
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'

                        UNION ALL

                        select
                        (CASE WHEN dtr.shift != 'X' THEN 'squareYellow' ELSE 'squareBlue' END) AS color,
                        dtr.empno,
                        CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                        entity01_desc,
                        dtr.txndate,
                        dtr.shift,
                        TIME_FORMAT(dtr.break_out, '%h:%i %p') AS time_in,
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.break_out as date_time
                        
                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND inout_raw.txndate = DATE(dtr.break_out)
                        AND TIME_FORMAT(dtr.break_out, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                        AND dtr.break_out IS NOT NULL
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'

                        UNION ALL

                        select
                        (CASE WHEN dtr.shift != 'X' THEN 'squareGreen' ELSE 'squareBlue' END) AS color,
                        dtr.empno,
                        CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                        entity01_desc,
                        dtr.txndate,
                        dtr.shift,
                        TIME_FORMAT(dtr.break_in, '%h:%i %p') AS time_in,
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.break_in as date_time
                        
                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND inout_raw.txndate = DATE(dtr.break_in)
                        AND TIME_FORMAT(dtr.break_in, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                        AND dtr.break_in IS NOT NULL
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'

                        UNION ALL

                        select
                        (CASE WHEN dtr.shift != 'X' THEN 'squareYellow' ELSE 'squareBlue' END) AS color,
                        dtr.empno,
                        CONCAT(emp_master.lname,' ',emp_master.fname,' ',emp_master.mname) AS empname,
                        entity01_desc,
                        dtr.txndate,
                        dtr.shift,
                        TIME_FORMAT(dtr.out, '%h:%i %p') AS time_in,
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.out as date_time
                        
                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND inout_raw.txndate = DATE(dtr.out)
                        AND TIME_FORMAT(dtr.out, '%h:%i %p') = TIME_FORMAT(inout_raw.txntime, '%h:%i %p')
                        AND dtr.out IS NOT NULL
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'

                        UNION ALL

                        select 
                        
                        (CASE WHEN dtr.shift != 'X' THEN IF((@row_number:=CASE WHEN @empno = dtr.empno THEN @row_number + 1 ELSE 1 END) %2 !=0 , 'squareGreen','squareYellow')
                        ELSE 'squareBlue' END) AS color,
                        @empno := dtr.empno as empno, 
                        concat(emp_master.lname, ' ' , emp_master.fname,' ', emp_master.mname) as empname,
                        entity01_desc,
                        dtr.txndate, 
                        dtr.shift,
                        TIME_FORMAT(txntime, '%h:%i %p') AS time_in, 
                        serialno AS unit, 
                        seqno, 
                        status,
                        concat(inout_raw.txndate, ' ', txntime) as date_time

                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND inout_raw.txndate = dtr.txndate
                        AND dtr.in IS NULL
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'

                        UNION ALL

                        select 
                        ('squareRed') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        IFNULL(TIME(dtr.in), '---') AS time_in, 
                        IFNULL(dtr.in, '---') AS unit, 
                        IFNULL(dtr.in, '---') AS seqno,
                        ('---') AS status,
                        ('---') as date_time

                        FROM dtr

                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.in IS NULL
                        AND break_out IS NULL
                        AND break_in IS NULL
                        AND dtr.out IS NULL
                        AND dtr.shift != ''
                        AND NOT EXISTS(select empno FROM inout_raw WHERE empno = dtr.empno) = 0
                        AND dtr.shift != 'X'

                        UNION ALL

                        select 
                        ('squareBlue') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        ('---') AS time_in, 
                        ('---') AS unit, 
                        ('---') AS seqno,
                        ('---') AS status,
                        ('---') as date_time

                        FROM dtr

                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01 
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."' 
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.shift = 'X'
                        AND dtr.in IS NULL

                        UNION ALL

                        select 
                        ('squareBlue') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        TIME_FORMAT(dtr.in, '%h:%i %p') AS time_in, 
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.in as date_time

                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01 
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."'
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.shift = 'X'
                        AND dtr.in IS NOT NULL

                        UNION ALL

                        select 
                        ('squareBlue') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        TIME_FORMAT(dtr.break_out, '%h:%i %p') AS time_in, 
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.break_out as date_time

                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01 
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."'
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.shift = 'X'
                        AND dtr.break_out IS NOT NULL

                        UNION ALL

                        select 
                        ('squareBlue') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        TIME_FORMAT(dtr.break_in, '%h:%i %p') AS time_in, 
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.break_in as date_time

                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01 
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."'
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.shift = 'X'
                        AND dtr.break_in IS NOT NULL

                        UNION ALL

                        select 
                        ('squareBlue') AS color,
                        emp_master.empno,
                        CONCAT(lname, ' ', fname, ' ', mname) AS empname, 
                        entity01_desc, 
                        dtr.txndate,
                        dtr.shift, 
                        TIME_FORMAT(dtr.out, '%h:%i %p') AS time_in, 
                        inout_raw.serialno AS unit,
                        inout_raw.seqno,
                        inout_raw.status,
                        dtr.out as date_time

                        FROM dtr

                        LEFT JOIN inout_raw ON dtr.empno = inout_raw.empno
                        LEFT JOIN emp_master ON dtr.empno = emp_master.empno
                        LEFT JOIN entity01 ON emp_master.entity01 = entity01.entity01 
                        LEFT JOIN entity03 ON emp_master.entity03 = entity03.entity03 

                        WHERE dtr.txndate >= '".$fromdate."' AND dtr.txndate <= '".$todate."'
                        AND emp_master.entity01 like '".$company."'
                        AND entity03.entity03_desc like '".$branch."'
                        AND emp_master.active = 'Y'
                        AND dtr.shift = 'X'
                        AND dtr.out IS NOT NULL

                        ) AS brchlog

                        GROUP BY empno, txndate, time_in
                        ORDER BY empname, txndate, date_time, CAST(seqno AS UNSIGNED)) as b"))
                         ->select('*')->get();

        // dd($brchlist);

        return Response::json($brchlist);
    }

    
    public function clickComp(Request $request) {

        if ($request->company == "%") {
            $distlist   =   District::where('entity01', 'like', $request->company)
                                ->groupBy('entity02.entity02_desc')
                                ->pluck('entity02_desc', 'entity02_desc');
        } else {
            $distlist   =   District::where('entity01', 'like', $request->company)
                                ->pluck('entity02_desc', 'entity02_desc');
        }

        return Response::json($distlist);             
    }
        public function clickBrchLog(Request $request) {

        if ($request->company == "%") {
            $brchlog    =   Branch::where('entity01', 'like', $request->company)
                                ->groupBy('entity03.entity03_desc')
                                ->pluck('entity03_desc', 'entity03_desc');
        } else {
            $brchlog    =   Branch::where('entity01', 'like', $request->company)
                                ->pluck('entity03_desc', 'entity03_desc');
        }

        return Response::json($brchlog);               
    }
        public function clickDept(Request $request) {

        if ($request->company == "%") {
            $department =   Department::where('entity01', 'like', $request->company)
                                        ->groupBy('department.deptdesc')
                                        ->pluck('deptdesc', 'deptcode');
        } else {
            $department =   Department::where('entity01', 'like', $request->company)->pluck('deptdesc', 'deptcode');
        }

        return Response::json($department);               
    }

        public function clickDist(Request $request) {

        if ($request->district == "%") {
            $branch =   Brnach::where('entity01', 'like', $request->company)
                           ->groupBy('entity03.entity03_desc')
                           ->pluck('entity03_desc', 'entity03_desc');
        } else {
            $branch =   Branch::leftJoin('entity02', 'entity02.entity02', '=', 'entity03.entity02')
                           ->where('entity02.entity02_desc', 'like', $request->district)
                           ->where('entity03.entity01', 'like', $request->company)
                           ->pluck('entity03_desc', 'entity03_desc');
        }
        return Response::json($branch);             
    }

    
}
