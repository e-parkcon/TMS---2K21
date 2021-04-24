<?php

namespace App\Http\Controllers;

use App\Models\ApprovingGroup;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\District;
use App\Models\Overtime;
use App\Models\Shift;
use Illuminate\Http\Request;

use App\Models\UserLevel;

class AjaxController extends Controller
{
    //
    public function approving_group_json_list(){

        $appgrp_list    =   ApprovingGroup::get();

        return response()->json(array('data' => $appgrp_list));
    }

    public function user_level(Request $request){
        
        // $userlevel  =   UserLevel::pluck('description', 'code');
        
        $user_level  =   UserLevel::query();
        
        $user_level->when(request('level', false), function($user, $level){
            return $user->where('code', $level);
        });

        $userlevel  =   $user_level->pluck('description', 'code');
        return response()->json($userlevel);
    }

    public function company(){

        $cocode =   Company::orderBy('entity01', 'asc')->pluck('entity01_desc', 'entity01');
        
        return response()->json($cocode);
    }

    public function district(Request $request){

        $distcode   =   District::where('entity01', $request->entity01)->pluck('entity02_desc', 'entity02');

        return response()->json($distcode);
    }

    public function branch(Request $request){

        $brchcode   =   Branch::where('entity01', $request->entity01)->where('entity02', $request->entity02)->pluck('entity03_desc', 'entity03');

        return response()->json($brchcode);
    }

    public function department(Request $request){

        $deptcode   =   Department::where('entity01', $request->entity01)->pluck('deptdesc', 'deptcode');

        return response()->json($deptcode);
    }

    public function shift(){
        $shifts     =   Shift::orderBy('shift', 'asc')->pluck('desc', 'shift');
    
        return response()->json($shifts);
    }

    public function view_overtime_attachments($refno, $ot_id, $empno){

        $pdf    =   Overtime::where('refno', $refno)->where('id', $ot_id)->where('empno', $empno)->first();
        $url    =   json_decode($pdf->pdf_file_ot, true);

        return response()->json($url);
    }

    public function pdf_display($url){

        try{
            return response()->file(public_path($url));
        }
        catch(Exception $e){

            return abort('404');
        }        
    }
}
