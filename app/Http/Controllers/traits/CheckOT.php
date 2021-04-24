<?php

namespace App\Http\Controllers\traits;

use App\Models\Overtime;
use Auth;

trait CheckOT
{
    
    private function ot_exists($timestart){
        $ot =   Overtime::where(function ($approved) use ($timestart){
                            $approved->where('empno', Auth::user()->empno)
                                    ->where('dateot', date('Y-m-d', strtotime($timestart)))
                                    ->where('timestart', $timestart)
                                    ->where('status', 'Approved')
                                    ->where('submitted', 'Y');
                        })
                        ->orWhere(function ($blank) use ($timestart){
                            $blank->where('empno', Auth::user()->empno)
                                ->where('dateot', date('Y-m-d', strtotime($timestart)))
                                ->where('timestart', $timestart)
                                ->where('status', '')
                                ->where('submitted', 'Y');
                        })
                        ->orWhere(function ($blank) use ($timestart){
                            $blank->where('empno', Auth::user()->empno)
                                ->where('dateot', date('Y-m-d', strtotime($timestart)))
                                ->where('timestart', $timestart)
                                ->where('status', '')
                                ->where('submitted', 'N');
                        })->exists();

        return $ot;
    }

}
