<!DOCTYPE html>
<html>
<head>
	<title>Leave Summary Report</title>
	
	<style type="text/css">
	@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
	body{
		font-family: 'Poppins', sans-serif;
	}

	i{
		font-size: 12px;
	}

	#table{
		width: 100%;
		border-collapse: collapse;
	}

	#table tr th{
		/* background-color: #2467b4; */
		/* color: #fff; */
		font-size: 11px;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
		padding: 5px;
	}
	#table tr td{
		font-size: 11px;
		/* border: 1px solid black; */
		padding: 8px;
	}
	.page-break {
		page-break-after: always;
	}
	</style>
</head>

<body>

@foreach($lv_empnos as $empno)

	<h4 style="padding: 0; margin: 0; text-transform: uppercase; text-align:center;"> Leave Application Summary</h4>
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
	<table id="table">
		<thead>
			<tr>
				<th width=8%>EmpNo</th>
				<th width=20% style="text-align: left;">Name</th>
				<th width=10%>Date Filed</th>
				<th width=10%>From</th>
				<th width=10%>To</th>
				<th width=10% style="text-align: left;">Leave Desc</th>
				<th width=10%>Equiv. Days</th>
				<th width=25% style="text-align: left;">Reason</th>
			</tr>
		</thead>
		<tbody>
			@foreach($empno['lv'] as $lv_empno)
				<tr>
					<td style="text-align: center;">{{ $lv_empno->empno }}</td>
					<td>{{ $lv_empno->lname }}, {{ $lv_empno->fname }}</td>
					<td style="text-align: center;">{{ date('m/d/Y', strtotime($lv_empno->created_at)) }}</td>
					<td style="text-align: center;">{{ date('m/d/Y', strtotime($lv_empno->app_fromdate)) }}</td>
					<td style="text-align: center;">{{ date('m/d/Y', strtotime($lv_empno->app_todate)) }}</td>
					<td>{{ $lv_empno->lv_desc }}</td>
					<td style="text-align: right;">{{ $lv_empno->app_days }}</td>
					<td>{{ $lv_empno->reason }}</td>
				</tr>
			@endforeach
				<tr>
					<td colspan="6" style="text-align: right;">Total Days</td>
					<td style="text-align: right;">{{ $empno['total_days'] }}</td>
				</tr>
		</tbody>
	</table>

	<div class="page-break"></div>
@endforeach

<!-- <h4 style="padding: 0; margin: 0; text-transform: uppercase;"><?php echo $_GET['status']; ?> Leave Application Summary</h4>

<?php
	if($_GET['fromdate'] != null  && $_GET['todate'] != null ){
		echo "<i>For the period of " . date('F d, Y', strtotime($_GET['fromdate'])) . " - " . date('F d, Y', strtotime($_GET['todate'])) . "</i>" ;
	}
	else{

	}
	
?>

<table id="table">
	<thead>
		<tr>
			<th width=7%>ID #</th>
			<th width=20%>Name</th>
			<th width=10%>Date Filed</th>
			<th width=10%>From date</th>
			<th width=10%>To date</th>
			<th width=5%>Day(s)</th>
			<th width=5%>Code</th>
			<th>Reason</th>
		</tr>
	</thead>
	<tbody>
		@foreach($lv_summary as $lv)
			<tr>
				<td>{{ $lv->empno }}</td>
				<td>{{ $lv->fname }} {{ $lv->lname }}</td>
				<td>{{ date('M d, Y', strtotime($lv->created_at)) }}</td>
				<td>{{ date('M d, Y', strtotime($lv->app_fromdate)) }}</td>
				<td>{{ date('M d, Y', strtotime($lv->app_todate)) }}</td>
				<td style="text-align: center;">{{ $lv->app_days }}</td>
				<td style="text-align: center;">{{ $lv->leavecode }}</td>
				<td>{{ $lv->reason }}</td>
			</tr>
		@endforeach
	</tbody>
</table> -->

</body>
</html>