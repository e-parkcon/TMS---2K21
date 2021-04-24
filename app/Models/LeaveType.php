<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    //
    protected $table = 'leave_type';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'lv_code', 'lv_desc', 'total_days', 'availment', 'no_days'
    ];
}
