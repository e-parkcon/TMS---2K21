<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Latest compiled and minified CSS -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}

    <!-- Optional theme -->
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> --}}

    <!-- Latest compiled and minified JavaScript -->
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}

    <style>
        @page {
            margin-left: 1%;
            padding: 5%;
        }

        * {
            box-sizing: border-box;
        }

        /* Create two equal columns that floats next to each other */
        .column1 {
            float: left;
            width: 50%;
            padding: 10px;
            /* height: 300px; Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row1:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
            .column1 {
                width: 100%;
            }
        }

        #table tr td{
            font-size: 13px;
            padding: 5px;
        }

        #table1 tr td{
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
            padding: 5px;
        }

        .justify{
            font-size: 12px;
            text-align: justify;
            text-justify: inter-word;
        }

        .page-break {
            page-break-after: always;
        }
        </style>
</head>
<body>

    <div class="row1">
        @foreach($employees as $key => $visit)
            @if($key%2)
                <div class="column1">
                    <div class="col-md-12">
                        <small>Health Checklist</small> <small style="color:gray; font-size: 10px;"> [Employee]</small>
                    </div>
                    <br>
                    
                    <table width=100% id="table">
                        <tr>
                            <td width=10%><small style="float: left;">Name</small> <span style="float: right;">:</span></td>
                            <td width=25% style="border-bottom: 1px solid black;"><small style="text-transform: uppercase;">{{ $visit['name'] }}</small></td>
                            <td width=10%><small style="float: left;">Temperature</small> <span style="float: right;">:</span></td>
                            <td width=10% style="border-bottom: 1px solid black; text-align: center;"><small>{{ $visit['temperature'] }}</small></td>
                        </tr>
                        <tr>
                            <td width=10%><small style="float: left;">Company</small> <span style="float: right;">:</span></td>
                            <td width=25% style="border-bottom: 1px solid black;"><small style="text-transform: capitalize;">{{ $visit['company'] }}</small></td>
                            <td width=10%><small style="float: left;">Phone #</small> <span style="float: right;">:</span></td>
                            <td width=10% style="border-bottom: 1px solid black;"><small>{{ $visit['phone'] }}</small></td>
                        </tr>
                    </table>

                    <br>
                    <br>
                    <table id="table1" style="width:100%;">
                        <tbody>
                            <tr>
                                <td colspan=2><small></small></td>
                                <td width=2% style="text-align: center;"><small>Yes</small></td>
                                <td width=2% style="text-align: center;"><small>No</small></td>
                            </tr>
                            <tr>
                                <td rowspan="4" width=10%><small>&nbsp; 1. Are you experiencing : (nakararanas ka ba ng:)</small></td>
                                <td width=15%><small>&nbsp; a. Sore Throat (pananakit ng lalamunan/ masakit lumunok)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_a'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_a'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; b. Body Pains (pananakit ng katawan)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_b'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_b'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; c. Headache (pananakit ng ulo)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_c'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_c'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; d. Fever for the past few days (lagnat sa nakalipas ng mga araw)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_d'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_d'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 2. Have you worked together or stayed in the same close environment of a confirmed COVID-19 case? (May nakasama ka ba o nakatrabahong tao na kumpirmadong may COVID-19?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q2'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q2'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 3. Have you had any contact with anyone with fever, cough, colds, and sore throat in the past 2 weeks? (Mayroon ka bang nakasama na may lagnat, ubo, sipon, o sakit ng lalamunan sa nakalipas na dalawang linggo?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q3'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q3'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 4. Have you travelled outside of the Philippines in the last 14 days? (Ikaw ba ay nagbyahe sa labas ng Pilipinas sa nakalipas na 14 na araw?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q4'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q4'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 5. Have you travelled to any area in NCR aside from your home? 
                                        (Ikaw ba ay nagpunta sa iba pang parte ng NCR o Metro Manila bukod sa iyong bahay?) 
                                        <br>Specify (Sabihin kung saan): </small>
                                        <small style="text-transform: uppercase;">"{{ $visit['health_declaration']['q5'] == 'Y' ? $visit['health_declaration']['other_place'] : '' }}"</small>
                                </td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q5'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q5'] == 'N' ? '*' : '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="justify">
                        <small>I hereby authorize <b>Ideaserv Systems, Inc., Phillogix Systems, Inc., ApSoft Inc., and NuServ Solutions, Inc.
                        </b>, to collect and process the data indicated herein for the purpose of effecting control of the COVID-19 infection. I understand that my personal information is protected by <b>RA 10173</b>
                        , Data Privacy Act of 2012, and that I am required by <b>RA 11469</b>, Bayanihan to Heal as One Act, to provide truthful information.</small>
                    </div>
        
                    <br>
                    
                    <table width=100% id="table">
                        <tr>
                            <td></td>
                            <td style="text-align: center; border-bottom: 1px solid black;"><small> {{ $visit['name'] }}</small></td>
                            <td></td>
                            <td style="text-align: center; border-bottom: 1px solid black;"><small style="text-transform: capitalize;"> {{ date('F d, Y', strtotime($visit['txndate'])) }}</small> <small style="text-transform: capitalize;"> {{ date('h:i A', strtotime($visit['txntime'])) }}</small></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center"><small>Signature over printed name</small></td>
                            <td></td>
                            <td style="text-align: center"><small>Date</small></td>
                        </tr>
                    </table>
                </div>

                <div class="page-break"></div>
            @else
                <div class="column1">
                    <div class="col-md-12">
                        <small>Health Checklist</small> <small style="color: gray; font-size: 10px;">[Employee]</small>
                    </div>
                    <br>
                    
                    <table width=100% id="table">
                        <tr>
                            <td width=10%><small style="float: left;">Name</small> <span style="float: right;">:</span></td>
                            <td width=25% style="border-bottom: 1px solid black;"><small style="text-transform: uppercase;">{{ $visit['name'] }}</small></td>
                            <td width=10%><small style="float: left;">Temperature</small> <span style="float: right;">:</span></td>
                            <td width=10% style="border-bottom: 1px solid black; text-align: center;"><small>{{ $visit['temperature'] }}</small></td>
                        </tr>
                        <tr>
                            <td width=10%><small style="float: left;">Company</small> <span style="float: right;">:</span></td>
                            <td width=25% style="border-bottom: 1px solid black;"><small style="text-transform: capitalize;">{{ $visit['company'] }}</small></td>
                            <td width=10%><small style="float: left;">Phone #</small> <span style="float: right;">:</span></td>
                            <td width=10% style="border-bottom: 1px solid black;"><small>{{ $visit['phone'] }}</small></td>
                        </tr>
                    </table>

                    <br>
                    <br>
                    <table id="table1" style="width:100%;">
                        <tbody>
                            <tr>
                                <td colspan=2><small></small></td>
                                <td width=2% style="text-align: center;"><small>Yes</small></td>
                                <td width=2% style="text-align: center;"><small>No</small></td>
                            </tr>
                            <tr>
                                <td rowspan="4" width=10%><small>&nbsp; 1. Are you experiencing : (nakararanas ka ba ng:)</small></td>
                                <td width=15%><small>&nbsp; a. Sore Throat (pananakit ng lalamunan/ masakit lumunok)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_a'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_a'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; b. Body Pains (pananakit ng katawan)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_b'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_b'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; c. Headache (pananakit ng ulo)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_c'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_c'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td><small>&nbsp; d. Fever for the past few days (lagnat sa nakalipas ng mga araw)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_d'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q1_d'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 2. Have you worked together or stayed in the same close environment of a confirmed COVID-19 case? (May nakasama ka ba o nakatrabahong tao na kumpirmadong may COVID-19?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q2'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q2'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 3. Have you had any contact with anyone with fever, cough, colds, and sore throat in the past 2 weeks? (Mayroon ka bang nakasama na may lagnat, ubo, sipon, o sakit ng lalamunan sa nakalipas na dalawang linggo?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q3'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q3'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 4. Have you travelled outside of the Philippines in the last 14 days? (Ikaw ba ay nagbyahe sa labas ng Pilipinas sa nakalipas na 14 na araw?)</small></td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q4'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q4'] == 'N' ? '*' : '' }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><small>&nbsp; 5. Have you travelled to any area in NCR aside from your home? 
                                        (Ikaw ba ay nagpunta sa iba pang parte ng NCR o Metro Manila bukod sa iyong bahay?) 
                                        <br>Specify (Sabihin kung saan): </small>
                                        <small style="text-transform: uppercase;">"{{ $visit['health_declaration']['q5'] == 'Y' ? $visit['health_declaration']['other_place'] : '' }}"</small>
                                </td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q5'] == 'Y' ? '*' : '' }}</td>
                                <td style="text-align:center;">{{  $visit['health_declaration']['q5'] == 'N' ? '*' : '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="justify">
                        <small>I hereby authorize <b>Ideaserv Systems, Inc., Phillogix Systems, Inc., ApSoft Inc., and NuServ Solutions, Inc.
                        </b>, to collect and process the data indicated herein for the purpose of effecting control of the COVID-19 infection. I understand that my personal information is protected by <b>RA 10173</b>
                        , Data Privacy Act of 2012, and that I am required by <b>RA 11469</b>, Bayanihan to Heal as One Act, to provide truthful information.</small>
                    </div>
        
                    <br>
                    
                    <table width=100% id="table">
                        <tr>
                            <td></td>
                            <td style="text-align: center; border-bottom: 1px solid black;"><small> {{ $visit['name'] }}</small></td>
                            <td></td>
                            <td style="text-align: center; border-bottom: 1px solid black;"><small style="text-transform: capitalize;"> {{ date('F d, Y', strtotime($visit['txndate'])) }}</small> <small style="text-transform: capitalize;"> {{ date('h:i A', strtotime($visit['txntime'])) }}</small></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center"><small>Signature over printed name</small></td>
                            <td></td>
                            <td style="text-align: center"><small>Date</small></td>
                        </tr>
                    </table>
                </div>
            @endif
        @endforeach
    </div>

</body>
</html>