<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OBApplication extends Model
{
    //
    protected $table = 'ob_application';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno',
        'date_filed',
        'txndate',
        'in',
        'break_out',
        'break_in',
        'out',
        'remarks'
    ];
}
