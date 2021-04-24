@extends('layouts.app')

@section('title', ' - Health Declaration')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            QR Code for Health Declaration
        @endslot
    @endcomponent
    
    <div class="col-md-12">

        <div class="row flex-column-reverse flex-md-row">
            <div class="col-xs-12 col-md-8">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label text-uppercase text-primary">Health Declaration</label>
                                    </div>
                                </div>
                                
                                <form action="" id="health_qr_form">
                                    <div class="table-responsive">
                                        <table class="table table-sm" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td width="5%" class="text-center"><small>Yes</small></td>
                                                    <td width="5%" class="text-center"><small>No</small></td>
                                                </tr>
                                                <tr>
                                                    <td rowspan=4><small>&nbsp; 1. Are you experiencing : <br>(nakararanas ka ba ng:)</small></td>
                                                    <td><small>&nbsp; a. Sore Throat (pananakit ng lalamunan/ masakit lumunok)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-a" name="q1_a" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-a" name="q1_a" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td><small>&nbsp; b. Body Pains (pananakit ng katawan)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-b" name="q1_b" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-b" name="q1_b" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td><small>&nbsp; c. Headache (pananakit ng ulo)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-c" name="q1_c" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-c" name="q1_c" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td><small>&nbsp; d. Fever for the past few days (lagnat sa nakalipas ng mga araw)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-d" name="q1_d" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q1-d" name="q1_d" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td colspan=2><small>&nbsp; 2. Have you worked together or stayed in the same close environment of a confirmed COVID-19 case? <br> (May nakasama ka ba o nakatrabahong tao na kumpirmadong may COVID-19?)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q2" name="q2" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q2" name="q2" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td colspan=2><small>&nbsp; 3. Have you had any contact with anyone with fever, cough, colds, and sore throat in the past 2 weeks? (Mayroon ka bang nakasama na may lagnat, ubo, sipon, o sakit ng lalamunan sa nakalipas na dalawang linggo?)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q3" name="q3" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q3" name="q3" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td colspan=2><small>&nbsp; 4. Have you travelled outside of the Philippines in the last 14 days? (Ikaw ba ay nagbyahe sa labas ng Pilipinas sa nakalipas na 14 na araw?)</small> <i class="text-danger font-weight-bold float-right">*</i> </td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q4" name="q4" value="Y"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q4" name="q4" value="N" ></small></td>
                                                </tr>
                                                <tr>
                                                    <td colspan=2><small>&nbsp; 5. Have you travelled to any area in NCR aside from your home? (Ikaw ba ay nagpunta sa iba pang parte ng NCR o Metro Manila bukod sa iyong bahay?) <br>
                                                    Specify (Sabihin kung saan): </small> <i class="text-danger font-weight-bold float-right">*</i> <input type="text" class="form-control form-control-sm ncrPlace" name="ncrPlace" id="ncrPlace" disabled/></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q5" name="q5" value="Y" onclick="enableTextbox()"></small></td>
                                                    <td class="text-center"><small><input type="radio" class="icheck-primary d-inline q5" name="q5" value="N" onclick="enableTextbox()"></small></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group row mt-0 mb-0">
                                        <div class="col-xs-8 col-md-12 text-sm"><small>I hereby authorize <b>Ideaserv Systems, Inc., Phillogix Systems, Inc., ApSoft Inc., and NuServ Solutions, Inc.
                                        </b>, to collect and process the data indicated herein for the purpose of effecting control of the COVID-19 infection. I understand that my personal information is protected by <b>RA 10173</b>
                                        , Data Privacy Act of 2012, and that I am required by <b>RA 11469</b>, Bayanihan to Heal as One Act, to provide truthful information.</small></div>
                                    </div>

                                    <div class="col-md-12 p-0 text-right">
                                        <a href="#" id="health_qr_btn" class="btn btn-sm btn-primary"><small>Generate QR Code</small></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <label class="form-label text-uppercase text-primary">Generated QR Code</label>
                                <div class="row">
                                    <div id="generated_qrcode" class="text-center">
                                        <img src="{{ asset('generated_qrCode/'. Auth::user()->empno) }}.png" class="img-fluid" height="250px" width="250px" alt="{{ Auth::user()->empno }}_qrCode.png">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <a href="{{ route('download_qr') }}" class="btn btn-sm btn-block btn-primary"><small>Download QR Code as PDF</small></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/generate_qrcode.js') }}"></script>
@endsection