<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DTR extends Model
{
    //
    protected $table = 'dtr';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 'txndate', 'shift', 'lv_code'
    ];

    public function importToDB(){

        $csv    =   resource_path('csv-schedule-files/*.csv');
        
        $g  =   glob($csv);
        
        foreach(array_slice($g, 0, 1) as $file){
            
            $data   =   array_map('str_getcsv', file($file));
            // dd($data);
            foreach($data as $row){
                
                $sched = self::where('empno', $row[0])->where('txndate', date('Y-m-d', strtotime($row[1])))->first();
                
                if ($sched != null){
                    $sched->where('empno', $row[0])
                            ->where('txndate', date('Y-m-d', strtotime($row[1])))
                            ->update(['shift' => $row[2]]);
                }
                else{
                    
                    $sched = Dtr::create([
                        'empno'     =>  $row[0],
                        'txndate'   =>  date('Y-m-d', strtotime($row[1])),
                        'shift'     =>  $row[2]
                    ]);
                }
                
            }       
            
            unlink($file);
        }

    }
}
