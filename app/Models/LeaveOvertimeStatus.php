<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveOvertimeStatus extends Model
{
    //
    protected $table = 'leave_overtime_status';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'otlv_id', 'approver', 'remarks', 'txndate', 'txntime'
    ];

}
