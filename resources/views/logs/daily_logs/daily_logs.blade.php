@extends('layouts.app')

@section('title', ' - Daily Logs')

@section('content')
<script>
jQuery(document).ready(function($){

    $(function() {
	    $( "#fromdate" ).datepicker({
	    	dateFormat: 'yy-mm-dd',
	    	// defaultDate: "+1w",
	    	changeMonth: true,
	    	changeYear: true,
	    	numberOfMonths: 1,
	    	onClose: function( selectedDate ) {
	    	$( "#todate" ).datepicker( "option", "minDate", selectedDate );
	    }
    	});
	    $( "#todate" ).datepicker({
	    	dateFormat: 'yy-mm-dd',
	    	// defaultDate: "+1w",
	    	changeMonth: true,
	    	changeYear: true,
	    	numberOfMonths: 1,
	    	onClose: function( selectedDate ) {
	        $( "#fromdate" ).datepicker( "option", "maxDate", selectedDate );
	    }
		});
	});
});
</script>
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Daily Logs
        @endslot
    @endcomponent

    {{-- <div class="col-md-3">
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div> --}}

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#search_modal"><small>Search &nbsp;<span class="fa fa-search"></span></small></a>
                @if(Request::GET('empno') != NULL || Request::GET('fromdate') != NULL)
                    <a href="#" id="dl_logs" class="btn btn-sm btn-success"
                        empno="{{ Request::GET('empno') }}"
                        name="{{ Request::GET('name') }}"
                        entity01="{{ Request::GET('entity01') }}" entity03="{{ Request::GET('entity03') }}"
                        fromdate="{{ Request::GET('fromdate') }}"
                        todate="{{ Request::GET('todate') }}"><small>Download PDF <span class="fa fa-file"></span></small></a>
                @endif
                <div class="table-responsive mt-3">
                    @if(Request::GET('empno') != NULL || Request::GET('fromdate') != NULL)
                        @if(Auth::user()->level >= 2)
                        <div class="table-responsive pl-2">
                            <table width=100%>
                                <tr>
                                    <td width=7%><label class="form-label text-sm">Branch</label><span class="float-right">:</span></td>
                                    <td>&nbsp;<label class="form-label text-sm">{{ $branch }}</label></td>
                                </tr>
                                <tr>
                                    <td><label class="form-label text-sm">Name</label><span class="float-right">:</span></td>
                                    <td>&nbsp;<label class="form-label text-sm">{{ Request::GET('name') }}, {{ Request::GET('empno') }} </label></td>
                                </tr>
                            </table>
                        </div>
                        @endif
                    @endif
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr class="table-bordered">
								<th width=15%>Date</th>
								<th width=2%>Shift</th>
								<th width=7% class="text-center">In</th>
								<th width=8% class="text-center">Break Out</th>
								<th width=8% class="text-center">Break In</th>
								<th width=7% class="text-center">Out</th>
								<th width=5% class="text-center" title="Next Day Out">ND O</th>
								<th width=8% class="text-center">Hrs of Work</th>
								<th colspan="2" scope="colgroup" class="text-center">Late</th>
								<th colspan="2" scope="colgroup" class="text-center">Undertime</th>
								<th width=2% class="text-center">Action</th>
							</tr>
							<tr class="table-bordered">
								<th colspan="8"></th>
								<th width=5% class="text-center">AM</th>
								<th width=5% class="text-center">PM</th>
								<th width=5% class="text-center">AM</th>
								<th width=5% class="text-center">PM</th>
								<th></th>
							</tr>
                        </thead>
                        <tbody>
							@foreach($daily_logs as $logs)
								<tr class="table-bordered" id="child-tr" title="{{ $logs['empno'] }}"  txndate="{{ date('F d, Y', strtotime($logs['txndate'])) }} /  {{ date('l', strtotime($logs['txndate'])) }}"
												time_in="{{ $logs['in'] == '' ? '--' : date('H:i a', strtotime($logs['in'])) }}"
												break_out="{{ $logs['break_out'] == '' ? '--' : date('H:i a', strtotime($logs['break_out'])) }}"
												break_in="{{ $logs['break_in'] == '' ? '--' : date('H:i a', strtotime($logs['break_in'])) }}"
												time_out="{{ $logs['out'] == '' ? '--' : date('H:i a', strtotime($logs['out'])) }}"
												txndate_log="{{ $logs['txndate'] }}" empno="{{ $logs['empno'] }}">
                                    
									@if($logs['txndate'] == $logs['hol_date'])
										<td><small class="text-danger">{{ $logs['hol_desc'] }}</small></td>
									@elseif($logs['lv_code'] != "")
										<td><small class="text-danger">{{ $logs['lv_desc'] }}</small></td>
									@elseif($logs['shift'] == 'X')
										<td><small>{{ date('M. d, Y', strtotime($logs['txndate'])) }} <i class="badge badge-danger float-right">{{ date('D', strtotime($logs['txndate'])) }}</i></small></td>
									@else
										<td><small>{{ date('M. d, Y', strtotime($logs['txndate'])) }} <i class="badge badge-info float-right">{{ date('D', strtotime($logs['txndate'])) }}</i></small></td>
									@endif

									<td class="text-center"><small>{{ $logs['shift'] }}</small></td>
									<td class="text-center"><small>{{ $logs['in'] == "" ? "" : date('H:i a', strtotime($logs['in'])) }}<span class="text-danger">{{ $logs['in_manual'] }}</span></small></td>
									<td class="text-center"><small>{{ $logs['break_out'] == "" ? "" : date('H:i a', strtotime($logs['break_out'])) }}<span class="text-danger">{{ $logs['break_out_manual'] }}</span></small></td>
									<td class="text-center"><small>{{ $logs['break_in'] == "" ? "" : date('H:i a', strtotime($logs['break_in'])) }}<span class="text-danger">{{ $logs['break_in_manual'] }}</span></small></td>
									<td class="text-center"><small>{{ $logs['out'] == "" ? "" : date('H:i a', strtotime($logs['out'])) }}<span class="text-danger">{{ $logs['out_manual'] }}</span></small></td>
									<td class="text-center"><small>{{ $logs['nextday_out'] }}</small></td>
									<td class="text-center"><small>{{ $logs['hrs_work'] }}</small></td>
									<td class="text-center"><small>{{ $logs['am_late'] }}</small></td>
									<td class="text-center"><small>{{ $logs['pm_late'] }}</small></td>
									<td class="text-center"><small>{{ $logs['am_undertime'] }}</small></td>
									<td class="text-center"><small>{{ $logs['pm_undertime'] }}</small></td>
									<td class="text-center">
										@if($logs['shift'] == "X" || $logs['lv_code'] != null)
										@else
											{{-- <a href="#" id="view_logs" class="text-success"><span class="fa fa-history"></span></a> --}}
                                            <a href="#" id="inout_logs" txndate="{{ $logs['txndate'] }}" formatted_date="{{ date('M. d, Y', strtotime($logs['txndate'])) }}" empno="{{ $logs['empno'] }}" class="btn btn-sm btn-primary"><small>Logs</small></a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="search_modal" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form action="">
				<div class="modal-header">
					<h6 class="modal-title text-sm font-weight-bold">Daily Logs</h6>
					<button type="button" class="close text-sm" data-dismiss="modal" ><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					@if(Auth::user()->level >= 2)
					<div class="form-group row">
						<div class="col-md-4">
							<label for="#"><small>ID # / Name </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-3">
							<select name="empno" id="empno" class="form-control form-control-sm selectpicker" data-live-search="true" data-size="10" required>
								<option selected disabled hidden>ID No. </option>
								@if(Request::GET('empno') != NULL)
									<option value="{{ Request::GET('empno') }}" selected hidden>{{ Request::GET('empno') }}</option>
								@endif
								@foreach($empno as $emp)
									<option data-name="{{ $emp->fname }} {{ $emp->lname }}" data-entity01="{{ $emp->entity01 }}" 
									data-entity03="{{ $emp->entity03 }}" value="{{ $emp->empno }}">{{ $emp->empno }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-5">
							<select name="name" id="name" class="form-control form-control-sm selectpicker" data-live-search="true" data-size="10" required>
								<option selected disabled hidden>Name </option>
								@if(Request::GET('name') != NULL)
									<option value="{{ Request::GET('name') }}" selected hidden>{{ Request::GET('name') }}</option>
								@endif
								@foreach($name as $ngalan)
									<option data-empno="{{ $ngalan->empno }}" data-entity01="{{ $ngalan->entity01 }}" 
									data-entity03="{{ $ngalan->entity03 }}" value="{{ $ngalan->fname }} {{ $ngalan->lname }}">{{ $ngalan->fname }} {{ $ngalan->lname }}</option>
								@endforeach
							</select>
							<input type="hidden" class="form-control form-control-sm" name="entity01" id="entity01" value="{{ Request::GET('entity01') }}" readonly>
							<input type="hidden" class="form-control form-control-sm" name="entity03" id="entity03" value="{{ Request::GET('entity03') }}" readonly>
						</div>
					</div>
					@endif
					<div class="form-group row">
						<div class="col-md-4">
							<label for="#"><small>From Date </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="fromdate" id="fromdate" class="form-control text-center form-control-sm" placeholder="Fromdate" autocomplete="off" value="{{ Request::GET('fromdate') }}" required>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">
							<label for="#"><small>To Date </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="todate" id="todate" class="form-control text-center form-control-sm" placeholder="Todate" autocomplete="off" value="{{ Request::GET('todate') }}" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Search</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" ><small>Close</small></button>
                        </div>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="{{ asset('js/logs/inout_logs.js') }}"></script>
@endsection