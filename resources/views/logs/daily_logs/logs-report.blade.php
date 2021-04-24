<!DOCTYPE html>
<html>
<head>
	<title>Daily Logs</title>

	<style type="text/css">
	@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
	body{
		font-family: 'Poppins', sans-serif;
	}
	#table{
		width: 100%;
		border-collapse: collapse;
	}
	#table tr th{
		font-size: 10px;
		padding: 5px;
		border: 1px solid black;
	}
	#table tr td{
		/*text-align: center;*/
		font-size: 9px;
		padding: 3px;
		border: 1px solid black;
	}
	#table1{
		font-size: 12px;
	}
	#woot{
		width: 50%;
		/* font-size: 11px; */
		text-indent: 2%;
		/* text-align: justify; */
	}
	#hr{
		margin-left: 2%;
		width: 40%;
		border: 1px solid black;
	}
	</style>
</head>
<body>

<h4 style="padding: 0; margin: 0; text-transform: uppercase; text-align: center;">Daily Logs</h4>
<div style="text-align: center; font-size: 12px;">
	<small>{{ $fromdate_todate }}</small>
</div>
<div style="width: 100%; position: absolute;">
	<h6 style="float: left;">Branch : {{ $branch }}</h6>
	<h6 style="float: right;">Date: {{ date('m/d/Y h:i A') }}</h6>
</div>

<br>
<br>
<table id="table">
	<thead>
		<tr>
			<th width=15%>Date</th>
			<th width=5%>Shift</th>
			<th width=10% style="text-align: center;">In</th>
			<th width=10% style="text-align: center;">Break Out</th>
			<th width=10% style="text-align: center;">Break In</th>
			<th width=10% style="text-align: center;">Out</th>
			<th width=20% style="text-align: left;">Remarks</th>
		</tr>
	</thead>
	<tbody>
		@foreach($daily_logs as $logs)
			<tr>
				<td>&nbsp;{{ date('Y-m-d', strtotime($logs['txndate'])) }} &nbsp;&nbsp;&nbsp;<span style="text-transform: uppercase; float: right">{{ date('D', strtotime($logs['txndate'])) }}</span>&nbsp;&nbsp;</td>
				<td style="text-align: center;">{{ $logs['shift'] }}</td>
				<td style="text-align: center;">{{ $logs['in'] == "" ? "" : date('H:i a', strtotime($logs['in'])) }}</td>
				<td style="text-align: center;">{{ $logs['break_out'] == "" ? "" : date('H:i a', strtotime($logs['break_out'])) }}</td>
				<td style="text-align: center;">{{ $logs['break_in'] == "" ? "" : date('H:i a', strtotime($logs['break_in'])) }}</td>
				<td style="text-align: center;">{{ $logs['out'] == "" ? "" : date('H:i a', strtotime($logs['out'])) }}</td>
				@if($logs['shift'] == "X")
					<td >DAY OFF</td>
				@elseif($logs['txndate'] == $logs['hol_date'])
					<td >{{ $logs['hol_desc'] }}</td>
				@elseif($logs['lv_code'] == 'VL' || $logs['lv_code'] == 'SL' || $logs['lv_code'] == 'PL' || $logs['lv_code'] == 'ML' || $logs['lv_code'] == 'BL')
					<td >{{ $logs['lv_desc'] }}</td>
				@else
					<td></td>
				@endif
			</tr>
		@endforeach
		{{-- @foreach($daily_logs as $logs)
			<tr>
				<td style="text-align: center;">{{ date('d', strtotime($logs->txndate)) }}</td>
				<td style="text-align: center;">{{ date('D', strtotime($logs->txndate)) }}</td>

				@if($logs->shift == "X")
					<td >DAY OFF</td>
				@elseif($logs->txndate == $logs->hol_date)
					<td >{{ $logs->hol_desc }}</td>
				@elseif($logs->lv_code == 'VL' || $logs->lv_code == 'SL' || $logs->lv_code == 'PL' || $logs->lv_code == 'ML' || $logs->lv_code == 'BL')
					<td >{{ $logs->lv_desc }}</td>
				@else
					<td>{{ $logs->shift }} &nbsp;-&nbsp; {{ $logs->desc }}</td>
				@endif

				<td  style="text-align: center;">
					@if($logs->in == "")
					@else
						{{ date('H:i a', strtotime($logs->in)) }}
					@endif
				</td>
				<td style="text-align: center;">
					@if($logs->break_out == "")
					@else
						{{ date('H:i a', strtotime($logs->break_out)) }}
					@endif
				</td>
				<td style="text-align: center;">
					@if($logs->break_in == "")
					@else
						{{ date('H:i a', strtotime($logs->break_in)) }}
					@endif
				</td>
				<td style="text-align: center;">
					@if($logs->out == "")
					@else
						{{ date('H:i a', strtotime($logs->out)) }}
					@endif
				</td>
				@if($logs->shift == "X")
					<td >DAY OFF</td>
				@elseif($logs->txndate == $logs->hol_date)
					<td >{{ $logs->hol_desc }}</td>
				@elseif($logs->lv_code == 'VL' || $logs->lv_code == 'SL' || $logs->lv_code == 'PL' || $logs->lv_code == 'ML' || $logs->lv_code == 'BL')
					<td >{{ $logs->lv_desc }}</td>
				@else
					<td></td>
				@endif
			</tr>
		@endforeach --}}
	</tbody>
</table>

<br>
<br>
<br>

<div id="woot">
	<h6 style="text-align: center; text-transform: uppercase; padding: 0; margin: 0;">{{ $name }}</h6>
	<hr>
	<p style="text-align: justify; font-size: 11px;">I CERTIFY on my honor that the above is a true and correct
		report of the hours of work performed, record of which
		was made daily at the time of arrival at and departure from office.
	</p>
</div>
<br>

</body>
</html>