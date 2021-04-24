<!DOCTYPE html>
<html>
<title>Leave Ledger</title>
<style>
	@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
	body{
		font-family: 'Poppins', sans-serif;
	}
    #table{
        border-collapse: collapse;
    }
	#table tr th{
		font-size: 10px;
		background-color: #2467b4;
        letter-spacing: 1px;
		color: #fff;
		border: 1px solid black;
		padding: 8px;
	}
    #table tr td{
		font-size: 9px;
		border: 1px solid black;
		padding: 8px;
	}
    #table1 tr td{
        padding: 0;
        margin: 0;
    }
</style>

<body>
    <h5 style="padding: 0; margin: 0; text-transform: uppercase; text-align: center;">Leave Ledger Report</h5>
    <br>
    <?php
        if($_GET['from_date'] != null  && $_GET['to_date'] != null ){
            echo "<h5 style='text-align: center; padding: 0; margin: 0;'>" . date('F d, Y', strtotime($_GET['from_date'])) . " - " . date('F d, Y', strtotime($_GET['to_date'])) . "</h5>";
        }
    ?>
    <br>
    @if($lv_code != 'All')
        <table id="table1" width=100%>
            <tbody>
                <tr>
                    <td width=20%><h6 style="padding:0; margin:0;">Name</h6></td>
                    <td><h6 style="padding:0; margin:0;">: &nbsp;&nbsp;&nbsp;{{ Auth::user()->lname }}, {{ Auth::user()->fname }}</h6></td>
                </tr>
                <tr>
                    <td><h6 style="padding:0; margin:0;">Leave Description</h6></td>
                    <td><h6 style="padding:0; margin:0; text-transform: uppercase;">: &nbsp;&nbsp;&nbsp;{{ $lv_type->lv_desc }}</h6></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table id="table" width="100%">
            <thead>
                <tr>
                    <th width=10% style="text-align: left;">Date</th>
                    <th width=10% style="text-align: center;">Leave Credits</th>
                    <th width=10% style="text-align: center;">Leave Used</th>
                    <th width=10% style="text-align: center;">Leave Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leave_ledger as $lv_ledger)
                    <tr>
                        <td>{{ date('F d, Y', strtotime($lv_ledger->txndate)) }}</td>
                        <td style="text-align: right;">{{ number_format($lv_ledger->lv_credit, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($lv_ledger->lv_used, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($lv_ledger->lv_balance, 2) }}</td>
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
                        <th colspan=3>{{ $lv_hdr->lv_desc }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($leave_header as $lv_header)
                        <th>Credit</th>
                        <th>Used</th>
                        <th>Balance</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($leave_ledger as $lv_ledger)
                    <tr>
                        <td>{{ date('F d, Y', strtotime($lv_ledger->txndate)) }}</td>
                        @foreach($leave_header as $lv_head)
                            @if($lv_ledger->lv_code == $lv_head->lv_code)
                                <td style="text-align: right;">{{ number_format($lv_ledger->lv_credit, 2) }}</td>
                                <td style="text-align: right;">{{ number_format($lv_ledger->lv_used, 2) }}</td>
                                <td style="text-align: right;">{{ number_format($lv_ledger->lv_balance, 2) }}</td>
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