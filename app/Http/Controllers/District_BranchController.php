<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\District;
use Illuminate\Http\Request;

class District_BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('utilies.district_branch.district');
    }

    public function district_list(){

        $dist_list   =   District::orderby('entity01', 'asc')->get();

        $d  =   0;
        $district   =   [];
        foreach($dist_list as $list){
            $district[$d]['entity01']   =   $list->entity01;
            $district[$d]['entity01_desc']   =  Company::where('entity01', $list->entity01)->first()->entity01_desc;
            $district[$d]['entity02']   =   $list->entity02;
            $district[$d]['entity02_desc']   =   $list->entity02_desc;

            $d++;
        }

        return response()->json(array('data'=>$district));
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
