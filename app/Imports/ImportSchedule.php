<?php

namespace App\Imports;

use App\Models\DTR;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportSchedule implements ToModel
{
    use Importable;
    // /**
    // * @param Collection $collection
    // */
    // public function collection(Collection $collection)
    // {
    //     //
    // }

    public function model(array $row)
    {

        $dtr = DTR::where('empno', $row[0])
                                        ->where('txndate', date('Y-m-d', strtotime($row[1])))
                                        ->exists();
        // try{
        if(!$dtr){
            DTR::insert([
                                        'empno' => $row[0], 
                                        'txndate' => date('Y-m-d', strtotime($row[1])),
                                        'shift' => $row[2]
                                    ]);
        }
        else{
            DTR::where('empno', $row[0])
                                    ->where('txndate', date('Y-m-d', strtotime($row[1])))
                                    ->update([
                                        'shift' => $row[2]
                                    ]);
        }

    }
}
