<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leave Ledger</title>
</head>
<body>
<?php
	if($_GET['from_date'] != null  && $_GET['to_date'] != null ){
        echo "<table>" .
                "<tr>" . 
                    "<td>From date - To date</td>" . 
                    "<td>" . date('F d, Y', strtotime($_GET['from_date'])) . " - " . date('F d, Y', strtotime($_GET['to_date'])) . "</td>" .
                "</tr>" .
            "</table>" ;
	}
?>
    @if($lv_code != 'All')
    <table>
        <tr>
            <td>Name</td>
            <td>{{ Auth::user()->lname }}, {{ Auth::user()->fname }}</td>
        </tr>
        <tr>
            <td>Leave Description</td>
            <td>{{ $lv_type->lv_desc }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Leave Credit</th>
                <th>Leave Used</th>
                <th>Leave Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $lv)
                <tr>
                    <td width=20%>{{ date('F d, Y', strtotime($lv->txndate)) }}</td>
                    <td width=15%>{{ number_format($lv->lv_credit, 2) }}</td>
                    <td width=15%>{{ number_format($lv->lv_used, 2) }}</td>
                    <td width=15%>{{ number_format($lv->lv_balance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else

        <table id="table1" width=100%>
            <tbody>
                <tr>
                    <td width=10%><h5 style="padding:0; margin:0;">Name</h5></td>
                    <td><h6 style="padding:0; margin:0;">: &nbsp;&nbsp;&nbsp;{{ Auth::user()->lname }}, {{ Auth::user()->fname }}</h6></td>
                </tr>
                
            </tbody>
        </table>
        <br>
        <table id="table" width="100%">
            <thead>
                <tr>
                    <th width=10%>Date</th>
                    @foreach($leave_header as $lv_hdr)
                        <th colspan=3 style="text-align: center;">{{ $lv_hdr->lv_desc }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($leave_header as $lv_header)
                        <th style="text-align: center;">Credit</th>
                        <th style="text-align: center;">Used</th>
                        <th style="text-align: center;">Balance</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $lv)
                    <tr>
                        <td width=20%>{{ date('F d, Y', strtotime($lv->txndate)) }}</td>
                        @foreach($leave_header as $lv_head)
                            @if($lv->lv_code == $lv_head->lv_code)
                                <td style="text-align: right;">{{ $lv->lv_credit }}</td>
                                <td style="text-align: right;">{{ $lv->lv_used }}</td>
                                <td style="text-align: right;">{{ $lv->lv_balance }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif
</body>
</html>