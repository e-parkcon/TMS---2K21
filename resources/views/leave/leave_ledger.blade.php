@extends('layouts.app')

@section('title', ' - Leave Ledger')

@section('content')
<script>
jQuery(document).ready(function($){

    $(function() {
	    $( "#from_date" ).datepicker({
	    	dateFormat: 'yy-mm-dd',
	    	// defaultDate: "+1w",
	    	changeMonth: true,
	    	changeYear: true,
	    	numberOfMonths: 1,
    	});
	    $( "#to_date" ).datepicker({
	    	dateFormat: 'yy-mm-dd',
	    	// defaultDate: "+1w",
	    	changeMonth: true,
	    	changeYear: true,
	    	numberOfMonths: 1,
		});
	});
});
</script>

<div class="row g-2 mt-0">
    
    @component('components.content_header')
        @slot('title')
            Leave Ledger
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="row g-3">
            <div class="col-md-3">
                <div class="row">
        
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body pr-1">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link p-3" id="v-pills-leave-dashboard-tab" href="{{ route('lv_dashboard') }}" role="tab" aria-controls="v-pills-leave-dashboard" aria-selected="true">Leave Dashboard</a>
                                    <a class="nav-link active p-3" id="v-pills-leave-ledger-tab" href="{{ route('leave.leave_ledger') }}" role="tab" aria-controls="v-pills-leave-ledger" aria-selected="false">
                                        Leave Ledger
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    {{-- USER LEAVE CREDITS --}}
                    <div class="col-md-12">
                        @include('components.emp_lv_credits', ['lv_credits' => $emp_leave])
                    </div>
                    
                </div>
            </div>
        
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body pb-0">
                        <form method="GET">
                            <div class="row">  
                                <div class="col-md-12 mb-2">
                                    <div class="row g-1">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control form-control-sm text-center" name="from_date" id="from_date" value="{{ Request::GET('from_date') }}" placeholder="From Date" autocomplete="off" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control form-control-sm text-center" name="to_date" id="to_date" value="{{ Request::GET('to_date') }}" placeholder="To Date" autocomplete="off" required>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="lv_code" id="lv_code" class="form-control form-control-sm selectpicker" required>
                                                <option selected disabled hidden>Choose Leave</option>
                                                    <option value="All">All</option>
                                                @foreach($emp_leave as $emp_lv)
                                                    <option value="{{ $emp_lv->lv_code }}">{{ $emp_lv->lv_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-sm btn-block btn-primary"><small>Search</small></button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        @if($from_date != '' && $to_date != '')
                                            <a href="{{ url('/leave_ledger-pdf?' . 'empno=' . Auth::user()->empno . '&lv_code=' . Request::GET('lv_code') . '&from_date=' . Request::GET('from_date') . '&to_date=' . Request::GET('to_date') ) }}" target="_blank" class="btn btn-sm btn-success"><small>Download PDF</small></a>
                                            <a href="{{ url('/leave-exports?' . 'lv_code=' . Request::GET('lv_code') . '&from_date=' . Request::GET('from_date') . '&to_date=' . Request::GET('to_date') ) }}" class="btn btn-sm btn-warning"><small>Download CSV</small></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
        
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="20%">Date</th>
                                        <th width="50%">Leave Type</th>
                                        <th width="10%" class="text-right">Credit</th>
                                        <th width="10%" class="text-right">Used</th>
                                        <th width="10%" class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lv_ledger as $lv)
                                        <tr>
                                            <td>{{ date('F d, Y', strtotime($lv->txndate)) }}</td>
                                            <td>{{ $lv->lv_desc }}</td>
                                            <td class="text-right">{{ number_format($lv->lv_credit, 2) }}</td>
                                            <td class="text-right">{{ number_format($lv->lv_used, 2) }}</td>
                                            <td class="text-right">{{ number_format($lv->lv_balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
        
                    </div>
                    <div class="card-footer">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{ $lv_ledger->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
@endsection