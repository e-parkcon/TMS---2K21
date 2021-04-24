<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee_Image extends Model
{
    //
    protected $connection = 'mysql3';
    protected $table = 'emp_image';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 
        'emp_image', 
        'image_name'
    ];
}
