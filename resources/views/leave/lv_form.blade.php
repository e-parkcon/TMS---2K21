<!DOCTYPE html>
<html>
<head>
	<title>Leave Form</title>
<style>
	body{
		margin: 0;
		padding: 0;
	}
	.header{
		text-align: center;
	}
	.address{
		margin-top: 0;
		padding: 0;
		font-size: 11px;
	}
	input[type="text"]{
	    box-sizing: border-box;
	    border: 1px solid #505050;
	    font-size: 11px;
	}	
	table{
		width: 100%;
	}
	td{
		padding: 0;
		font-size: 11px;
	}
	table, td{
	}
	.center{
		text-align: center;
	}
	.signature{
		font-size: 13px;
		font-weight: bold;
	}
	.underline{
		border: none;
		box-shadow: none;
		border-radius: 0px;
		border-bottom: 1px solid black;
	}
</style>

</head>
<body>

	<h3 style="text-align: center; text-transform: uppercase; margin-bottom: -1px;">{{ $lv_form->entity01_desc }}</h3>
	<address style="font-size: 11px; text-align: center;">
			No. 441 Lt. Artiaga St. Brgy. Corazon De Jesus, San Juan City - 1500 <br/>
			Tel. No (632)410-4607 &nbsp;
			TIN No. 008-533-025-000
	</address>
	<h3 style="text-align: center;">Leave Application Form</h3>

	<br>

	<table width=100%>
		<tbody>
			<tr>
				<td><b>Name</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->fname }} {{ $lv_form->lname }}"></td> 

				<td><b>Date Filed</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->created_at)) }}"></td>
			</tr>

			<tr>
				<td><b>Period Covered :</b></td>
			</tr>

			<tr>
				<td><b>From Date</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->app_fromdate)) }}"></td>

				<td><b>Leave Type</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->lv_desc }}"></td>
			</tr>

			<tr>
				<td><b>To Date</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->app_todate)) }}"></td>
			</tr>

			<tr>
				<td><b>No. of day(s)</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->app_days }}"></td>
				<td></td>
			</tr>

			<tr>
				<td><b>Reason(s)</b></td>
			{{-- </tr>
			<tr>
				<td></td> --}}
				<td><input type="text" style="width: 200%;" value="{{ $lv_form->reason }}"></td>
			</tr>

			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td></td>
				<td style="border-bottom: 1px solid black; text-align: center;"><b>{{ $lv_form->fname }} {{ $lv_form->lname }}</b></td>
				<td></td>
				<td style="border-bottom: 1px solid black; text-align: center;"></td>
			</tr>

			<tr>
				<td></td>
				<td style="text-align: center;"><b>Employee Name</b></td>
				<td></td>
				<td style="text-align: center;"><b>Approved By</b></td>
			</tr>

		</tbody>
	</table>

	<div class="col-md-12">
	<small>NOTE:</small>
		<small style="font-size: 10px; color: #303030;">
			<ol>
				<li>An employee who has rendered at least(1)year of service, reckoned from the date the employee started working including authorized <br/>
					Absences and paid regular holidays, is entitled to VL/SL of fourteen(14) working days with pay.
				</li>
				<li>VL/SL may be used for approved leave including vacation, sick, forced, and emergency leave in accordance with all existing administrative procedures.</li>
				<li>VL/SL when used is paid at the current daily rate of pay. It is deducted from unused leave</li>
				<li>VL/SL, if not used, will be commuted to its cash value based on current rate.</li>
				<li>VL must be filed & approved prior to application date. In case of emergency immediately after his/her leave.</li>
				<li>On separation from the company other than termination for just cause, accrued leave will be computed to cash at current rate.</li>
				<li>SL, If three(3) days or more, must be supported by doctor's certificate.</li>
			</ol>
		</small>
	</div>


	<h3 style="text-align: center; text-transform: uppercase; margin-bottom: -1px;">{{ $lv_form->entity01_desc }}</h3>
	<address style="font-size: 11px; text-align: center;">
			No. 441 Lt. Artiaga St. Brgy. Corazon De Jesus, San Juan City - 1500 <br>
			Tel. No (632)410-4607 &nbsp;
			TIN No. 008-533-025-000
	</address>
	<h3 style="text-align: center;">Leave Application Form</h3>

	<br>

	<table width=100%>
		<tbody>
			<tr>
				<td><b>Name</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->fname }} {{ $lv_form->lname }}"></td> 

				<td><b>Date Filed</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->created_at)) }}"></td>
			</tr>

			<tr>
				<td><b>Period Covered :</b></td>
			</tr>

			<tr>
				<td><b>From Date</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->fromdate)) }}"></td>

				<td><b>Leave Type</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->lv_desc }}"></td>
			</tr>

			<tr>
				<td><b>To Date</b></td>
				<td>: &nbsp;<input type="text" value="{{ date('F d, Y', strtotime($lv_form->todate)) }}"></td>
			</tr>

			<tr>
				<td><b>No. of day(s)</b></td>
				<td>: &nbsp;<input type="text" value="{{ $lv_form->app_days }}"></td>
				<td></td>
			</tr>

			<tr>
				<td><b>Reason(s)</b></td>
			{{-- </tr>
			<tr>
				<td></td> --}}
				<td><input type="text" style="width: 200%;" value="{{ $lv_form->reason }}"></td>
			</tr>

			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td></td>
				<td style="border-bottom: 1px solid black; text-align: center;"><b>{{ $lv_form->fname }} {{ $lv_form->lname }}</b></td>
				<td></td>
				<td style="border-bottom: 1px solid black; text-align: center;"></td>
			</tr>

			<tr>
				<td></td>
				<td style="text-align: center;"><b>Employee Name</b></td>
				<td></td>
				<td style="text-align: center;"><b>Approved By</b></td>
			</tr>

		</tbody>
	</table>

	<div class="col-md-12">
	<small>NOTE:</small>
		<small style="font-size: 10px; color: #303030;">
			<ol>
				<li>An employee who has rendered at least(1)year of service, reckoned from the date the employee started working including authorized <br/>
					Absences and paid regular holidays, is entitled to VL/SL of fourteen(14) working days with pay.
				</li>
				<li>VL/SL may be used for approved leave including vacation, sick, forced, and emergency leave in accordance with all existing administrative procedures.</li>
				<li>VL/SL when used is paid at the current daily rate of pay. It is deducted from unused leave</li>
				<li>VL/SL, if not used, will be commuted to its cash value based on current rate.</li>
				<li>VL must be filed & approved prior to application date. In case of emergency immediately after his/her leave.</li>
				<li>On separation from the company other than termination for just cause, accrued leave will be computed to cash at current rate.</li>
				<li>SL, If three(3) days or more, must be supported by doctor's certificate.</li>
			</ol>
		</small>
	</div>

</body>
</html>