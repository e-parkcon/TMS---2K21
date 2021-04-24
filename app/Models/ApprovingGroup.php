<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ApprovingGroup extends Model
{
    //
    protected $table = 'app_group';
    protected $primaryKey = 'app_code';
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'otlv', 'app_code', 'app_desc'
    ];


    public static function appgroup_officers($app_code, $otlv){

        $app_officers   =   ApprovingOfficers::where('app_code', $app_code)->where('otlv', $otlv)->orderBy('seqno', 'asc')->get();

        $officers   =   [];
        $x  =   0;
        foreach($app_officers as $officer){
            $name   =   User::where('empno', $officer->empno)->first();
            
            $officers[$x]['name']   =   $name->fname . ' ' . $name->lname;
            $officers[$x]['email']  =   $name->email;
            $officers[$x]['empno']  =   $officer->empno;
            $officers[$x]['app_code']  =   $officer->app_code;
            $officers[$x]['otlv']   =   $officer->otlv;
            $officers[$x]['seqno']  =   $officer->seqno;

            $x++;
        }

        return  $officers;
    }

    public static function appgroup_members($app_code, $otlv){

        return ApprovingMember::where('app_code', $app_code)->where('otlv', $otlv)->get();
    }

    public static function user_approving_group($empno, $otlv){
        $app_member =   ApprovingMember::where('empno', $empno)
                                    ->where('otlv', $otlv)->first();

        $app_group  =   self::where('app_code', $app_member->app_code)
                            ->where('otlv', $app_member->otlv)->first();

        return $app_group;
    }

    public static function user_approvingOfficers($empno, $otlv){

        $app_group  =   self::user_approving_group($empno, $otlv);
        
        $officers   =   ApprovingOfficers::where('app_code', $app_group->app_code)
                                ->where('otlv', $otlv)
                                ->leftJoin('emp_master', 'emp_master.empno', 'app_group1.empno')
                                ->get();

        return  $officers;
    }

    public static function approvingMembers($app_code, $otlv){
        return ApprovingMember::where('app_code', $app_code)
                                ->where('otlv', $otlv)->get();
    }

    // return response()->json();
}
