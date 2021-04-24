<?php

namespace App\Http\Controllers\traits;

use App\Models\Branch;
use App\Models\Company;
use App\Models\District;
use App\Models\LeaveCredits;
use App\Models\LeaveType;
use App\User;

/**
 * 
 */
trait Employee
{
    
    public function emp_name($empno){

        $emp    =   User::where('empno', $empno)->first();

        return $emp;
    }

    public function emp_branch($empno){

        $emp_branch =   Branch::where('entity01', $this->emp_name($empno)->entity01)
                            ->where('entity02', $this->emp_name($empno)->entity02)
                            ->where('entity03', $this->emp_name($empno)->entity03)->first();

        return $emp_branch;
    }


    public function employeeLists(){

        $all_employees  =   User::get();

        $x  =   0;
        $employees  =   [];
        foreach($all_employees as $employee){

            $company    =   Company::where('entity01', $employee->entity01)->first();
            $district   =   District::where('entity01', $employee->entity01)->where('entity02', $employee->entity02)->first();
            $branch =   Branch::where('entity01', $employee->entity01)->where('entity02', $employee->entity02)->where('entity03', $employee->entity03)->first();

            $employees[$x]['name']      =   $employee->lname . ', ' . $employee->fname;
            $employees[$x]['empno']     =   $employee->empno;
            $employees[$x]['entity01']  =   $employee->entity01;
            $employees[$x]['entity01_desc']  =   $company == '' ? '--' : $company->entity01_desc;
            $employees[$x]['entity02']  =   $employee->entity02;
            $employees[$x]['entity02_desc']  =   $district == '' ? '--' : $district->entity02_desc;
            $employees[$x]['entity03']  =   $employee->enttity03;
            $employees[$x]['entity03_desc']  =   $branch == '' ? '--' : $branch->entity03_desc;
            $employees[$x]['is_active']  =   $employee->active == 'Y' ? 'Active' : 'Inactive';

            $x++;
        }

        return $employees;
    }

    public function empl_lv_credits($empno){

        $lv_credits =   LeaveCredits::where('empno', $empno)->get();

        $x  =   0;
        $emp_lv  =   [];
        foreach($lv_credits as $lv){
            $emp_lv[$x]['lv_code']  =   $lv->lv_code;
            $emp_lv[$x]['lv_desc']  =   LeaveType::where('lv_code', $lv->lv_code)->first()->lv_desc;
            $emp_lv[$x]['no_days']  =   $lv->no_days;
            $emp_lv[$x]['lv_balance']  =   $lv->lv_balance;

            $x++;
        }

        return $emp_lv;
    }
}
