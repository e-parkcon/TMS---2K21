<?php

namespace App\Http\Controllers\traits;

use App\Models\LeaveType;

/**
 * 
 */
trait Leave_Type
{
    
    public function leave_type(){
        $lv_type    =   LeaveType::get();

        return $lv_type;
    }


}
