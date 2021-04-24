<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovingOfficers extends Model
{
    //
    protected $table = 'app_group1';
    protected $primaryKey = 'app_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'app_code',
        'otlv', 
        'seqno', 
        'empno',
        'name',
        'email',
        'app_crypt'
    ];
}
