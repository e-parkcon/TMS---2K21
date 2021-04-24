<?php

namespace App\Imports;

use App\Models\LeaveCredits;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use tmsNew\Models\Leave;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');


class ImportLeaveCredits implements ToModel, WithHeadingRow
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {   

        $lv_credits =   LeaveCredits::where('empno', $row[0])
                                        ->where('lv_code', $row[1])
                                        ->exists();
        // try{
        if(!$lv_credits){
            LeaveCredits::create([
                            'empno' => $row[0], 
                            'lv_code' => $row[1],
                            'lv_balance' => $row[2]
                        ]);
        }
        else{
            LeaveCredits::where('empno', $row[0])
                        ->where('lv_code', $row[1])
                        ->update([
                            'lv_balance' => $row[2]
                        ]);
        }

    }
}
