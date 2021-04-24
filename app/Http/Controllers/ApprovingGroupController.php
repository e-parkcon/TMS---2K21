<?php

namespace App\Http\Controllers;

use App\Models\ApprovingGroup;
use App\Models\ApprovingMember;
use App\Models\ApprovingOfficers;
use App\User;
use Illuminate\Http\Request;

class ApprovingGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('utilies.approving.approving_group');
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
        $apprgrp    =   ApprovingGroup::where('otlv', $request->category)->orderBy('app_code', 'desc')->get();
        $app_code   =   count($apprgrp) == 0 ? '' : str_pad($apprgrp->first()->app_code+1, 3, 0, STR_PAD_LEFT);

        $new_apprgrp    =   new ApprovingGroup;
        $new_apprgrp->app_code  =   $app_code;
        $new_apprgrp->app_desc  =   $request->app_desc;
        $new_apprgrp->otlv      =   $request->category;
        $new_apprgrp->save();

        return response()->json($new_apprgrp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req, $app_code)
    {
        //
        $approving_group        =   new ApprovingGroup;
        
        $officer_list   =   User::where('level', '>=', 1)->where('active', 'Y')->orderBy('fname', 'asc')->get();
        $member_list    =   User::where('active', 'Y')->orderBy('fname', 'asc')->get();
        $empno_list     =   User::select('empno', 'fname', 'lname')->where('active', 'Y')->orderBy('empno', 'asc')->get();

        $officers           =   $approving_group->appgroup_officers($app_code, $req->category);
        $members            =    $approving_group->appgroup_members($app_code, $req->category);

        $m  =   0;
        $appMembers =   [];
        foreach($members as $member){
            $name   =   User::where('empno', $member->empno)->first();

            $appMembers[$m]['app_code'] =   $member->app_code;
            $appMembers[$m]['category'] =   $member->otlv;
            $appMembers[$m]['empno']    =   $member->empno;
            $appMembers[$m]['name']     =   $name == null ? '--' : $name->fname . ' ' . $name->lname;
            $m++;
        }

        return view('utilies.approving.app_group_details')->with('approving_group', $approving_group->where('app_code', $app_code)->where('otlv', $req->category)->first())
                                                        ->with('officers', $officers)
                                                        ->with('members', $appMembers)
                                                        ->with('officer_lists', $officer_list)
                                                        ->with('member_lists', $member_list)
                                                        ->with('empno_lists', $empno_list);
    }

    public function add_new_officer(Request $request, $app_code){

        $category   =   $request->category;
        $empno      =   $request->empno;

        $officer    =   ApprovingOfficers::where('app_code', $app_code)->where('otlv', $category);
        $seqno      =   is_null($officer->orderBy('seqno', 'desc')->first()) ? 0 : $officer->orderBy('seqno', 'desc')->first()->seqno + 1;
        
        $off_exists =   $officer->where('empno', $empno)->exists();
        
        if($off_exists){
            return response()->json(1);
        }
        else{
            
            $new_officer =   new ApprovingOfficers;
            $new_officer->app_code  =   $app_code;
            $new_officer->otlv      =   $category;
            $new_officer->seqno     =   $seqno;
            $new_officer->empno     =   $empno;
            // $new_officer->name      =   $request->app_officers;
            // $new_officer->email     =   $request->app_email;
            // $new_officer->app_crypt =   $request->app_crypt;
            $new_officer->save();
        }

        return response()->json($new_officer);
    }

    public function change_officer(Request $req, $app_code){

        $app_officer    =   ApprovingOfficers::where('app_code', $app_code)->where('otlv', $req->category)
                                            ->where('empno', $req->new_app_empno_officer);
        
        if($app_officer->exists()){
            return response()->json(1);
        }    

        ApprovingOfficers::where('app_code', $app_code)
                                            ->where('otlv', $req->category)
                                            ->where('seqno', $req->seqno)
                                            ->update([
                                                'empno'     =>      $req->new_app_empno_officer,
                                                // 'name'      =>      $req->new_app_officers,
                                                // 'email'     =>      $req->new_app_email,
                                                // 'app_crypt' =>      $req->new_app_crypt
                                            ]);
                            
        return response()->json($app_officer->first());
    }

    public function remove_officer(Request $req, $app_code){
        $app_officer  =   ApprovingOfficers::where('app_code', $app_code)
                                            ->where('otlv', $req->category)
                                            ->where('empno', $req->empno)->delete();

        return response()->json($app_officer);
    }

    public function add_new_member(Request $req, $app_code, $otlv){

        $member     =   ApprovingMember::where('otlv', $otlv)->where('empno', $req->empno)->exists();

        if($member){
            return response()->json(1);
        }

        $new_member =   new ApprovingMember;
        $new_member->app_code   =   $app_code;
        $new_member->otlv       =   $otlv;
        $new_member->empno      =   $req->empno;
        $new_member->save();

        return response()->json($new_member);
    }

    public function remove_member(Request $req, $app_code){
        $appmember  =   ApprovingMember::where('app_code', $app_code)->where('otlv', $req->category)
                                        ->where('empno', $req->empno)->delete();

        return response()->json($appmember);
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
    public function update(Request $request, $app_code)
    {
        //
        $approving_group    =   ApprovingGroup::where('app_code', $app_code)
                                                ->where('otlv', $request->category)
                                                ->update([
                                                    'app_desc'  =>  $request->app_desc
                                                ]);

        return response()->json($approving_group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req, $app_code)
    {
        //
        $app_group  =   ApprovingGroup::where('app_code', $app_code)->where('otlv', $req->category)->delete();
        
        ApprovingOfficers::where('app_code', $app_code)->where('otlv', $req->category)->delete();
        ApprovingMember::where('app_code', $app_code)->where('otlv', $req->category)->delete();
        
        return response()->json($app_group);
    }
}
