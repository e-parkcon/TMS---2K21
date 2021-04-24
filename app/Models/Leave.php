<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    protected $table = 'leave';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empno',
        'fromdate',
        'todate',
        'leavecode',
        'total_day',
        'app_fromdate',
        'app_todate',
        'app_days',
        'status',
        'stage',
        // 'entity01',
        // 'entity02',
        // 'entity03',
        'reason',
        'created_at',
        'updated_at',
        'pdf_file_leave',
        'leave_crypt',
        'app_code',
        'wp_wop'
    ];
}
