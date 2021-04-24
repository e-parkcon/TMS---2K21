@extends('layouts.app')

@section('title', ' - DTR Transaction')

@section('content')
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet"> -->
<script src="{{asset('/js/moment.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            DTR Transaction
        @endslot
    @endcomponent


    <div class="col-md-12">
        <div class="row g-1">
           <div class="card">
               <div class="card-body">
                   <div class="table-responsive">
                       <table class="table table-sm table-hover" width="100%">
                            {{-- <tr>
                                <td width="10%"><label class="form-label text-uppercase">Name </label><span class="float-right">:</span></td>
                                <td><label class="form-label text-uppercase"> {{ $employee->lname }}, {{ $employee->fname }} {{ $employee->mname }} / {{ $employee->empno }}</label></td>
                            </tr> --}}
                            <thead>
                                <tr>
                                    <th width=15%>Date</th>
                                    <th class="text-center" width=5%>Day</th>
                                    <th class="text-center" width=10%>Shift</th>
                                    <th class="text-center">AM IN</th>
                                    <th class="text-center">AM OUT</th>
                                    <th class="text-center">PM IN</th>
                                    <th class="text-center">PM OUT</th>
                                    <th class="text-center" width=15%>Logs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daily_logs as $logs)
                                    <tr id="child_tr" in="{{ $logs->in == '' ? '--' : date('h:i A', strtotime($logs->in)) }}"
                                                    break_out="{{ $logs->break_out == '' ? '--' : date('h:i A', strtotime($logs->break_out)) }}"
                                                    break_in="{{ $logs->break_in == '' ? '--' : date('h:i A', strtotime($logs->break_in)) }}"
                                                    out="{{ $logs->out == '' ? '--' : date('h:i A', strtotime($logs->out)) }}"
                                                    txndate="{{ date('F d, Y', strtotime($logs->txndate)) }}"
                                                    day="{{ date('l', strtotime($logs->txndate)) }}">

                                        <td>{{ date('F d, Y', strtotime($logs->txndate)) }}</td>
                                        <td class="text-center">{{ date('D', strtotime($logs->txndate)) }}</td>
                                        <td class="text-center">{{ $logs->shift }}</td>
                                        <td class="text-center">{{ $logs->in == "" ? "" : date('h:i', strtotime($logs->in)) }}<span class="text-danger">{{ $logs->in_manual }}</span></td>
                                        <td class="text-center">{{ $logs->break_out == "" ? "" : date('h:i', strtotime($logs->break_out)) }}<span class="text-danger">{{ $logs->break_out_manual }}</span></td>
                                        <td class="text-center">{{ $logs->break_in == "" ? "" : date('h:i', strtotime($logs->break_in)) }}<span class="text-danger">{{ $logs->break_in_manual }}</span></td>
                                        <td class="text-center">{{ $logs->out == "" ? "" : date('h:i', strtotime($logs->out)) }}<span class="text-danger">{{ $logs->out_manual }}</span></td>
                                        <td class="text-center">
                                            <a href="#" data-toggle="modal" data-target="#edit_logs" 
                                                    data-time_in="{{ $logs->in == '' ? '--' : $logs->in }}"
                                                    data-break_out="{{ $logs->break_out == '' ? '--' : $logs->break_out }}"
                                                    data-break_in="{{ $logs->break_in == '' ? '--' : $logs->break_in }}"
                                                    data-time_out="{{ $logs->out == '' ? '--' : $logs->out }}"
                                                    data-txndate="{{ date('F d, Y', strtotime($logs->txndate)) }}"
                                                    data-day="{{ date('l', strtotime($logs->txndate)) }}"
                                                    data-empno="{{ $logs->empno }}"
                                                    data-shift="{{ $logs->shift }}"
                                                    data-nd_out="{{ $logs->nextday_out }}"
                                                    class="btn btn-sm btn-primary" title="Official Business / Adjustment"><span class="fa fa-edit"></span></a>
                                            
                                            <a href="#" id="raw_time" title="Raw Time" class="btn btn-sm btn-info"><span class="fa fa-history"></span></a>
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

</div>

<div class="modal fade" tabindex='-1' id='edit_logs' role='dialog'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">OB / Adjustment</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
            </div>

            <form action="{{ route('dtr_update') }}" method="POST" class="form-horizontal">
            
            {{ csrf_field() }}
		    {{ method_field('patch') }}

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                            <div class="form-group row">
                                <label for="txndate" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">Date <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-10" id="txndate">
                                    <input type="text" name="txndate_display" id="txndate_display" class="form-control form-control-sm" readonly>
                                    <input type="hidden" name="txndate" id="txndate" class="form-control form-control-sm" readonly>
                                    <input type="hidden" name="empno" id="empno" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="txndate" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">Shift <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-10">
                                    <select name="shift" id="shift" class="form-control form-control-sm selectpicker" data-live-search="true" data-size="10">
                                        <option selected disabled hidden>Choose Shift</option>
                                        @foreach($shift as $sh)
                                            <option value="{{ $sh->shift }}">{{ $sh->desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="am_in" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">AM IN <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-4">
                                    <div class="input-group date" id="am_in" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="am_in" id="am_in" data-target="#am_in" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#am_in" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm text-center datetimepicker-input" name="am_in_old" id="am_in_old" autocomplete="off" readonly/>
                                </div>
                                <label for="am_out" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">AM OUT <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-4">
                                    <div class="input-group date" id="am_out" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="am_out" id="am_out" data-target="#am_out" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#am_out" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm text-center datetimepicker-input" name="am_out_old" id="am_out_old" autocomplete="off" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pm_in" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">PM IN <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-4">
                                    <div class="input-group date" id="pm_in" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="pm_in" id="pm_in" data-target="#pm_in" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#pm_in" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm text-center datetimepicker-input" name="pm_in_old" id="pm_in_old" autocomplete="off" readonly/>
                                </div>
                                <label for="pm_out" class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">PM OUT <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-4">
                                    <div class="input-group date" id="pm_out" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="pm_out" id="pm_out" data-target="#pm_out" autocomplete="off"/>
                                        <div class="input-group-append" data-target="#pm_out" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm text-center datetimepicker-input" name="pm_out_old" id="pm_out_old" autocomplete="off" readonly/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-sm-2 col-md-3"><small class="font-weight-bold">Next Day Out :</small></label>
                                <div class="col-5 col-sm-5 col-md-2 text-center">
                                    <input type="radio" name="nd_out" id="no" value="N"> <label for="no"><small class="font-weight-bold">No</small></label>
                                </div>
                                <div class="col-5 col-sm-5 col-md-2 text-center">
                                    <input type="radio" name="nd_out" id="yes" value="Y"> <label for="yes"><small class="font-weight-bold">Yes</small></label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-sm-2 col-md-2"><small class="font-weight-bold">Remarks <span class="float-right">:</span></small></label>
                                <div class="col-10 col-sm-10 col-md-10">
                                    <input type="text" class="form-control form-control-sm" name="remarks" id="remarks" required autocomplete="off">
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row g-1">
					<div class="col-md-12 pull-right">
                        <button type="submit" class="btn btn-sm btn-primary"><small>Save Changes</small></button>
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="modal_close"><small>Close</small></button>
					</div>
				</div>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/logs/dtr.js') }}"></script>

@endsection