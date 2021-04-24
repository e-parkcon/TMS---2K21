<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitors_HealthDecl extends Model
{
    //
    protected $connection = 'mysql4';
    protected $table = 'visitors_checklists';
    protected $primaryKey = 'refno';
    public $incrementing = false;
    public $timestamps = false;
}