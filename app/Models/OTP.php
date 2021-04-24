<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    //
    protected $table = 'otp_history';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
    	'empno', 'txndate', 'txntime', 'otp', 'refno', 'IsUsed'
    ];
}
