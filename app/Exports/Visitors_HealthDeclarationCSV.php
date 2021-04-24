<?php

namespace App\Exports;

use App\Models\Visitors_HealthDecl;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class Visitors_HealthDeclarationCSV implements FromView
{
    protected $txndate;

    function __construct($txndate)
    {
        $this->txndate  =   $txndate;   
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $health_checklists   =   Visitors_HealthDecl::where('txndate', $this->txndate)->get();

        $x  =   0;
        $visitors   =   [];

        foreach($health_checklists as $list){
            
            $visitors[$x]['name']   =   $list->name;
            $visitors[$x]['temperature']   =   $list->temperature;
            $visitors[$x]['company']   =   $list->company;
            $visitors[$x]['phone']   =   $list->mobile_no;
            $visitors[$x]['nature_of_visit']   =   $list->nature_of_visit;
            $visitors[$x]['person_to_visit']   =   $list->person_to_visit;
            $visitors[$x]['health_declaration']   =   json_decode($list->health_declaration, true);
            $visitors[$x]['txndate']   =   $list->txndate;
            $visitors[$x]['txntime']   =   $list->txntime;

            $x++;
        }

        return view('health_declaration.visitors', ['visitors' => $visitors]);
    }
}
