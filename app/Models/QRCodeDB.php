<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCodeDB extends Model
{
    //
    protected $connection = 'mysql4';
    protected $table = 'qrcode';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 'qr_id', 'health_declarations'
    ];
}
