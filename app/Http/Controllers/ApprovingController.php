<?php

namespace App\Http\Controllers;

use App\Models\ApprovingGroup;
use App\Models\ApprovingMember;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ApprovingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    // public function approving_officers_dropdown(Request $request){ // DROPDOWN

    //     try {
    //         $app_group  =   User::where('level', '>=', 1)->where('active', 'Y')->orderBy('fname', 'asc')->get();

    //         return view('components.approving_officers_dropdown', ['officer_lists' => $app_group])->render(); 
    //     } catch (\Exception $e) {
    //         return response()->json(0);
    //     }
    // }

    public function approving_groups(Request $request){ // DROPDOWN

        try {
            $app_group  =   ApprovingGroup::where('otlv', $request->otlv)->orderBy('app_desc', 'asc')->get();

            return view('components.approving_group_dropdown', ['app_group' => $app_group])->render(); 
        } catch (\Exception $e) {
            return response()->json(0);
        }
    }


    public function approving_officers(Request $request){

        try {
            $user_app_group  =   ApprovingGroup::user_approving_group($request->empno, $request->otlv);
            $app_officers    =   ApprovingGroup::user_approvingOfficers($request->empno, $request->otlv);
    
            $officers   =   [];
            $x  =   0;
            foreach($app_officers as $officer){
                $name   =   User::where('empno', $officer->empno)->first();
                
                $officers[$x]['name']   =   $name->fname . ' ' . $name->lname;
                $officers[$x]['email']  =   $name->email;

                $x++;
            }

            return view('components.approving_officer_lists', ['user_app_group' => $user_app_group, 
                                                                    'app_officers' => $officers])->render();
        } catch (\Exception $e) {

            return response()->json('No approving group assigned');
        }

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
    public function update(Request $request, $empno)
    {
        //
        try {
            $appmember  =   ApprovingMember::where('empno', $empno)->where('otlv', $request->lv_ot);

            if(!$appmember->exists()){
                ApprovingMember::insert([
                    'empno' =>  $empno,
                    'otlv'  =>  $request->lv_ot,
                    'app_code'  =>  $request->app_code
                ]);
            }
            else{
                $appmember->where('empno', $empno)
                        ->where('otlv', $request->lv_ot)
                        ->update(['app_code' => $request->app_code]);
            }
    
            return response()->json('success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }

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
