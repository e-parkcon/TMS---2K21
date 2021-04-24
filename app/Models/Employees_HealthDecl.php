<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees_HealthDecl extends Model
{
    //
    protected $connection = 'mysql4';
    protected $table = 'employees_checklist';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 'qr_id', 'health_declarations'
    ];
}
