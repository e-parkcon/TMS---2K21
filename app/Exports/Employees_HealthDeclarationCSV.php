<?php

namespace App\Exports;

use App\Models\Employees_HealthDecl;
use App\Models\PIF;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class Employees_HealthDeclarationCSV implements FromView
{
    protected $txndate;


    function __construct($txndate)
    {
        $this->txndate  =   $txndate;   
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view() : View
    {
        //

        $health_checklists   =  Employees_HealthDecl::where('txndate', $this->txndate)->get();

        $x  =   0;
        $employees   =   [];

        foreach($health_checklists as $list){
            $user   =   PIF::where('empno', $list->empno)->first();
            $employees[$x]['name']   =   $user->fname . ' ' . $user->lname;
            $employees[$x]['temperature']   =   $list->temperature;
            $employees[$x]['company']   =   $list->company;
            $employees[$x]['phone']   =   (string)$list->mobile_no;
            $employees[$x]['health_declaration']   =   json_decode($list->health_declaration, true);
            $employees[$x]['txndate']   =   $list->txndate;
            $employees[$x]['txntime']   =   $list->txntime;

            $x++;
        }

        return view('health_declaration.employee')->with('employees', $employees);
    }
}
