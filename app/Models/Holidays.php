<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    //
    protected $table = 'holiday';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;
}
