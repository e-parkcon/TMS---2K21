<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovingMember extends Model
{
    //
    protected $table = 'app_code_member';
    protected $primaryKey = 'app_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 'otlv', 'app_code'
    ];
}
