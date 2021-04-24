<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCredits extends Model
{
    //
    protected $table = 'emp_leave';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 'lv_code', 'lv_balance'
    ];

    public function importLeaveCredits(){

        $csv    =   resource_path('csv-schedule-files/*.csv');
        
        $g  =   glob($csv);
        
        foreach(array_slice($g, 0, 1) as $file){
            
            $data   =   array_map('str_getcsv', file($file));
            // dd($data);
            foreach($data as $row){
                
                $emp_leave = self::where('empno', $row[0])->where('lv_code', $row[1])->exists();
                // dd($emp_leave);

                if (!$emp_leave){
                    // dd('updated');
                    self::create([
                                    'empno'     =>  $row[0],
                                    'lv_code'   =>  $row[1],
                                    'lv_balance'     =>  $row[2]
                                ]);
                }
                else{
                    // dd('created');
                    self::where('empno', $row[0])
                                ->where('lv_code', $row[1])
                                ->increment('lv_balance', $row[2]);
                }
                
            }       
            
            unlink($file);
        }

    }
}
