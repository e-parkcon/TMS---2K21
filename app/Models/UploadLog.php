<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class UploadLog extends Model
{
    //
    protected $table = 'upload_log';
    // protected $primaryKey = 'app_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'filename', 
        'name_uploader', 
        'date',
        'time',
        'type',
        'cocode',
        'entity01'
    ];
}
