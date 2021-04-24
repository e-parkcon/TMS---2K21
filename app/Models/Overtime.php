<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    //
    protected $table = 'overtime';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'empno',
        'dateot',
        'seqno',
        'clientname',
        'workdone',
        'regsched_start',
        'regsched_end',
        'timestart',
        'timefinish',
        'hours',
        'appr_hours',
        'refno',
        'stage',
        'status',
        // 'entity01',
        // 'entity02',
        // 'entity03',
        'ot_crypt',
        'pdf_file_ot',
        'created_at',
        'updated_at',
        'app_code',
    ];

}
