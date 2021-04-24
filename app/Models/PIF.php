<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PIF extends Model
{
    //
    protected $connection = 'mysql3';
    protected $table = 'emppif';
    protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'empno', 
        'lname', 
        'fname', 
        'mname', 
        'namesuffix', 
        'no',
        'street',
        'brgy_village',
        'city_municipality',
        'province',
        'region',
        'zipcode',
        'sex',
        'civil_status',
        'emp_type',
        'active', 
        'birth_date',
        'home_no', 'mobile_no', 
        'email', 'otp', 
        'blood_type',
        'nationality',
        'empl_date',
        'term_date',
        'username',
        'password',
        'cocode',
        'brchcode',
        'deptcode'
    ];
}
