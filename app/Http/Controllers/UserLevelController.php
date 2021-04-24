<?php

namespace App\Http\Controllers;

use App\Models\UserLevel;
use App\Models\UserLevel_Menu;
use App\Models\Web_Menu;
use App\User;
use Illuminate\Http\Request;

use DB;
use Exception;
use Session;

class UserLevelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $userlevel  =   UserLevel::orderBy('code', 'asc')->get();
        
        return view('utilies.user_level.user_level', ['userlevel' => $userlevel]);
    }

    public function role_menus($level){

        $menus  =   UserLevel_Menu::user_menus($level);
        
        return view('components.user_role_menus', ['user_menus' => $menus])->render();
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


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($level)
    {
        //
        
        $menus  =   UserLevel_Menu::user_menus($level);

        $webMenu    =   Web_Menu::select(DB::raw('menu_code AS menus_code'), 
                                        DB::raw('code AS grp_code'),
                                        DB::raw('menu_desc AS menus_desc'))
                                ->orderBy('menu_code', 'asc')->get();

        return view('utilies.user_level.edit_user_menus', ['user_menus' => $menus, 'web_menu' => $webMenu, 'level' => $level]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
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
    public function update(Request $request, $level)
    {
        //
        $this->validate($request, [
            'menu'  =>  'required'
        ]);

        $menu   =   $request->menu;
        $code   =   $request->code;

        try{
            UserLevel_Menu::insert([
                            'user_level'    =>  $level,
                            'menu_code'     =>  $menu,
                            'code'          =>  $code
                        ]);

            Session::flash('success', 'Menu Added!');
            return back();
        }
        catch(Exception $e){
            // dd($e->errorInfo[2]);
            // Session::flash('error', substr($e->errorInfo[2], 0, 15) . '!');
            Session::flash('error', 'Selected menu is already exists.');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $level)
    {
        //
        UserLevel_Menu::where('code', $request->code)->where('menu_code', $request->menu_code)->where('user_level', $level)->delete();

        // Session::flash('success', 'Menu Deleted Successfully!');
        // return back();
        // return response()->json();
    }
}