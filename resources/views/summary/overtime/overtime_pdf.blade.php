<!DOCTYPE htl>
<html>
<head>
	<title>Overtime Summary</title>

	<style type="text/css">
	@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
	body{
		font-family: 'Poppins', sans-serif;
	}

	i{
		font-size: 10px;
	}

	.table{
		width: 100%;
		border-collapse: collapse;
	}

	.table tr th{
		font-size: 11px;
		/* background-color: #2467b4; */
		/* color: #fff; */
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		padding: 5px;
	}
	.table tr td{
		font-size: 10px;
		/* border: 1px solid black; */
		padding: 3px 3px 3px 3px;
	}

	.page-break {
		page-break-after: always;
	}
	</style>
</head>

<body>

@foreach($empnos as $empno)

	<h4 style="padding: 0; margin: 0; text-transform: uppercase; text-align:center;"> Overtime Application Summary</h4>
	<div style="text-align: center; font-size: 12px;">
		<?php
			if($_GET['fromdate'] != null  && $_GET['todate'] != null ){
				echo "<small>From: " . date('F d, Y', strtotime($_GET['fromdate'])) . " &nbsp;&nbsp;To: " . date('F d, Y', strtotime($_GET['todate'])) . "</small>" ;
			}
		?>
	</div>

	<div style="width: 100%; position: absolute;">
		<h6 style="float: left;">Branch : {{ $empno['branch'] }}</h6>

		<h6 style="float: right;">Date: {{ date('m/d/Y h:i A') }}</h6>
	</div>
	<br>
	<br>
	<table class="table">
		<thead>
			<tr>
				<th width=10%>EmpNo</th>
				<th width=10% style="text-align: left;">Name</th>
				<th width=10%>Date Filed</th>
				<th width=10%>Date OT</th>
				<th width=15%>Time Started</th>
				<th width=15%>Time Finished</th>
				<th width=5%># Hrs</th>
				<th width=25% style="text-align: left; padding-left: 10px;">Remarks</th>
			</tr>
		</thead>
		<tbody>
			@foreach($empno['ot'] as $ot)
				<tr>
					<td style="text-align: center;">{{ $ot->empno }}</td>
					<td>{{ $ot->fname }} {{ $ot->lname }}</td>
					<td style="text-align: center;">{{ date('m/d/Y', strtotime($ot->created_at)) }}</td>
					<td style="text-align: center;">{{ date('m/d/Y', strtotime($ot->dateot)) }}</td>
					<td style="text-align: center;">{{ $ot->timestart }}</td>
					<td style="text-align: center;">{{ $ot->timefinish }}</td>
					<td style="text-align: right;">{{ $ot->appr_hours }}</td>
					<td style="padding-left: 10px;">{{ $ot->workdone }}</td>
				</tr>
			@endforeach
				<tr>
					<td colspan="6"></td>
				</tr>
				<tr>
					<td colspan="6"></td>
				</tr>
				<tr>
					<td colspan="6" style="text-align: right;">Total Hours</td>
					<td style="text-align: right; border-top: 1px solid black;">{{ $empno['total_hours'] }}</td>
				</tr>
		</tbody>
	</table>


	<div class="page-break"></div>
@endforeach

	<!-- <div class="page-break"></div> -->

<!-- <h4 style="padding: 0; margin: 0; text-transform: uppercase; text-align:center;"> Overtime Application Summary</h4>

<div style="text-align: center; font-size: 12px;">
	<?php
		if($_GET['fromdate'] != null  && $_GET['todate'] != null ){
			echo "<small>From: " . date('F d, Y', strtotime($_GET['fromdate'])) . " &nbsp;&nbsp;To: " . date('F d, Y', strtotime($_GET['todate'])) . "</small>" ;
		}
		else{
		}
	?>
</div>


<table id="table">
	<thead>
		<tr>
			<th>EmpNo</th>
			<th style="text-align: left;">Name</th>
			<th>Date Filed</th>
			<th>Date OT</th>
			<th># Hrs</th>
			<th>Remarks</th>
		</tr>
	</thead>
	<tbody>
		@foreach($ot_summary as $ot)
			<tr>
				<td style="text-align: center;">{{ $ot->empno }}</td>
				<td>{{ $ot->fname }} {{ $ot->lname }}</td>
				<td style="text-align: center;">{{ date('m/d/Y', strtotime($ot->created_at)) }}</td>
				<td style="text-align: center;">{{ date('m/d/Y', strtotime($ot->dateot)) }}</td>
				<td style="text-align: right;">{{ $ot->appr_hours }}</td>
				<td>{{ $ot->remarks }}</td>
			</tr>
		@endforeach
	</tbody>
</table> -->

</body>
</html>