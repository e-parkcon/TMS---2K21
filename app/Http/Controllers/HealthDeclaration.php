<?php

namespace App\Http\Controllers;

use App\Exports\Employees_HealthDeclarationCSV;
use App\Exports\Visitors_HealthDeclarationCSV;
use App\Models\Company;
use App\Models\QRCodeDB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Response;
use Auth;
use Exception;
use PDF;

class HealthDeclaration extends Controller
{

    protected   $qrcode_format  =   'png';

    public function health_declaration_page(){

        return view('health_declaration.qr_health_declaration_page');
    }

    public function gen_qrcode_info(Request $request){

        unlink(public_path('generated_qrCode/' . Auth::user()->empno.'.'.$this->qrcode_format));

        $empno          =   Auth::user()->empno;
        $name           =   Auth::user()->fname . ' ' . Auth::user()->lname;
        $phone          =   Auth::user()->user_mobile_no();
        $company        =   Company::where('entity01', Auth::user()->entity01)->first()->entity01_desc;
        $q1_a           =   $request->q1_a;
        $q1_b           =   $request->q1_b;
        $q1_c           =   $request->q1_c;
        $q1_d           =   $request->q1_d;
        $q2             =   $request->q2;
        $q3             =   $request->q3;
        $q4             =   $request->q4;
        $q5             =   $request->q5;
        $ncrPlace       =   $request->ncrPlace;

        $info    =   array(
                        'empno'     =>  $empno, 
                        'name'      =>  $name,
                        'phone'     =>  $phone,
                        'company'   =>  $company, 
                        'q1_a'      =>  $q1_a,
                        'q1_b'      =>  $q1_b,
                        'q1_c'      =>  $q1_c,
                        'q1_d'      =>  $q1_d,
                        'q2'        =>  $q2,
                        'q3'        =>  $q3,
                        'q4'        =>  $q4,
                        'q5'        =>  $q5, 'other_place'  =>  $ncrPlace
                    );

        $qr_id  =   'E-'.uniqid(Auth::user()->empno);

        // Create Qr Code
        QrCode::size(300)
                    ->errorCorrection('L')
                    ->format($this->qrcode_format)
                    ->generate($qr_id, public_path('generated_qrCode/' . Auth::user()->empno.'.'.$this->qrcode_format));
                    // ->generate(json_encode($info, JSON_FORCE_OBJECT), public_path('generated_qrCode/'.str_slug(Auth::user()->empno).'.png'));

        // Save details to DB
        QRCodeDB::updateOrCreate(['empno' => Auth::user()->empno],
            [
            'qr_id'     =>      $qr_id,
            'health_declarations'   =>      json_encode($info, JSON_FORCE_OBJECT)
        ]);

        return Response::json('ok');
    }

    public function download_qrcode_pdf(){

        try{
            $empno  =   Auth::user()->empno;
            $name   =   Auth::user()->fname . ' ' . Auth::user()->lname .$empno;
    
            // A few settings
            $image  = public_path('generated_qrCode/'.Auth::user()->empno.'.'.$this->qrcode_format);
    
            // Read image path, convert to base64 encoding
            $imageData  =   base64_encode(file_get_contents($image));
    
            // Format the image SRC:  data:{mime};base64,{data};
            $src    =   'data:'.mime_content_type($image).';base64,'.$imageData;
            
            $pdf    =   PDF::loadView('health_declaration.download_qr_as_pdf', [
                                'empno' =>  $empno,
                                'src'   =>  $src
                            ]);
    
            // $pdf = PDF::loadView('qr_code.download_qr_as_pdf', compact(['empno', 'src']))->setPaper('a4', 'portrait');
            // return $pdf->stream('QR Code.pdf');
            return  $pdf->download($name.' QR Code.pdf');
            // return view('qr_code.download_qr_as_pdf', compact(['empno', 'src']));
        }
        catch(Exception $e){
            return  back()->withErrors('QR Code not found. Please fill up health declaration form and click generate qr code.');
        }

    }

    //
    public function health_declaration_report(Request $request){

        $txndate    =   $request->txndate;
        $healthOf   =   $request->healthOf;

        if($healthOf == 'Visitors'){

            return Excel::download(new Visitors_HealthDeclarationCSV($txndate), 'Visitors-Health Declaration.csv');
        }
        elseif($healthOf == 'Employees'){
            return Excel::download(new Employees_HealthDeclarationCSV($txndate), 'Employees-Health Declaration.csv');
        }

    }
}
