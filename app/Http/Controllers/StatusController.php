<?php

namespace App\Http\Controllers;

use App\Http\Controllers\traits\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    //

    use Status;

    public function status($otlv_id){

        $lv_ot_status   =   $this->leave_overtime_status($otlv_id);

        return view('components.status', ['otlv_status' => $lv_ot_status])->render();
    }
}
