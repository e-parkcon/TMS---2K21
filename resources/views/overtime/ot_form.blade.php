<!DOCTYPE html>
<html>
<head>
	<title>Overtime Slip Form</title>

	<style type="text/css">
		table{
			width: 100%;
		}
		#table{
			border: 1px solid black;
			border-collapse: collapse;
		}
		#table tr th{
			border: 1px solid black;
			padding: 5px;
			font-size: 11px;
		}
		#table tr td{
			border: 1px solid black;
			padding: 5px;
			font-size: 10px;
		}

		#table1{
			font-size: 12px;
		}
		/*#table1 tr td{
			font-size: 12px;
			border: 1px solid
		}*/

		#table2{
			border: 1px solid black;
			border-collapse: collapse;
		}
		#table2 tr td{
			/*border: 1px solid black;*/
			padding: 5px;
			font-size: 11px;
		}

		#table1 tr td input[type="text"]{
			font-size: 11px;
			border: none;
		}
		small{
			font-size: 10px;
		}
	</style>
</head>
<body>

	<h4 style="text-align: center; text-transform: uppercase; margin-bottom: -1px;">{{ $company }}</h4>
	<br>
	<h5 style="margin: 0; padding: 0; text-align: center;">CALL CENTER SOFTWARE/HARDWARE SUPPORT GROUP</h5>
	<h5 style="margin: 0; padding: 8px; text-align: center;">OVERTIME SLIP FORM</h5>

	<table id="table1">
		<tr>
			<td><b>Name </b></td>
			<td width=2%>: &nbsp; <input type="text" value="{{ $ot_form->fname }} {{ $ot_form->lname }}"></td>
			<td width=5%></td>
			<td></td>
			<td></td>
			<td><b>Date </b></td>
			<td>: &nbsp; <input type="text" value="{{ date('F d, Y', strtotime($ot_form->created_at)) }}"></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>Department </b></td>
			<td>: &nbsp; {{ $ot_form->deptdesc }}</td>
		</tr>
	</table>
	<br>
	<table id="table">
		<thead>
			<tr>
				<th>Date</th>
				<th>Client's Name</th>
				<th>Work Done</th>
				<th>Regular Schedule</th>
				<th>Time Started</th>
				<th>Time Finished</th>
				<th>No. of Hours</th>
			</tr>
		</thead>
		<tbody>
			@foreach($ot_details as $ot)
				<tr>
					<td>{{ date('M. d, Y', strtotime($ot->dateot)) }}</td>
					<td>{{ $ot->clientname }}</td>
					<td>{{ $ot->workdone }}</td>

					@if($ot->regsched_start == "-" && $ot->regsched_end == "-")
						<td class="td">Day Off / Rest Day</td>
					@else
						<td class="td">{{ date('h:i a' ,strtotime($ot->regsched_start)) }} to {{ date('h:i a' ,strtotime($ot->regsched_end)) }}</td>
					@endif

					<td>{{ date('h:i a' ,strtotime($ot->timestart)) }}</td>
					<td>{{ date('h:i a' ,strtotime($ot->timefinish)) }}</td>
					<td>{{ $ot->appr_hours }} Hr(s)</td>
				</tr>
			@endforeach
				<tr>
					<td colspan=6 style="text-align: right;"><b>Total Hour(s)</b></td>
					<td><b>{{ $ot_hrs }} Hour(s)</b></td>
				</tr>
		</tbody>
	</table>

	<br>

	<small>Date :</small>
	<table id="table2">
		<tr>
			<td></td>
			<td style="text-align: center; padding: 5px;"></td>
			<td></td>
			<td style="text-align: center; padding: 5px;"></td>
			<td></td>
			<td style="text-align: center; padding: 5px;"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center; padding: 5px;">{{ $ot_form->fname }} {{ $ot_form->lname }}</td>
			<td></td>
			<td style="text-align: center; padding: 5px;"></td>
			<td></td>
			<td style="text-align: center; padding: 5px;"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center; border-top: 1px solid black; font-size: 10px;"><b>EMPLOYEE NAME</b></td>
			<td></td>
			<td style="text-align: center; border-top: 1px solid black; font-size: 10px;"><b>NOTED BY</b></td>
			<td></td>
			<td style="text-align: center; border-top: 1px solid black; font-size: 10px;"><b>APPROVED BY</b></td>
			<td></td>
		</tr>
	</table>
	<small style="font-size: 11px;">Note: This form should be submitted before the CUT-OFF period</small>

</body>
</html>