<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveLedger extends Model
{
    //
    protected $table = 'leave_ledger';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno',
        'txndate',
        'lv_frmdate',
        'lv_toodate',
        'lv_period',
        'lv_code',
        'lv_credit',
        'lv_used',
        'lv_balance',
        'remarks'
    ];
}
