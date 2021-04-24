@extends('layouts.app')

@section('title', ' - Leave Details')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Leave Details
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="row flex-column-reverse flex-md-row">

            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <a href="#" id="approve_lv" lv_id="{{ $leave_dtl['lv_id'] }}" empno="{{ $leave_dtl['empno'] }}" class="btn btn-sm btn-primary"><small>Approve</small></a>
                                <a href="#" id="disapprove_lv" lv_id="{{ $leave_dtl['lv_id'] }}" empno="{{ $leave_dtl['empno'] }}" class="btn btn-sm btn-danger"><small>Disapprove</small></a>
                            </div>
        
                            <div class="col-md-12">
        
                                <div class="table-responsive">
                                    <table class="table table-sm" width="100%">
                                        <caption class="text-sm"><small>Date Filed : {{ $leave_dtl['date_file'] }}</small></caption>
                                        <tr>
                                            <td>Name / ID # <span class="float-right">:</span></td>
                                            <td>{{ $leave_dtl['name'] }} / {{ $leave_dtl['empno'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Branch <span class="float-right">:</span></td>
                                            <td>{{ $leave_dtl['entity03_desc'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Leave Period 
                                                <span class="float-right">:</span></td>
                                            <td>
                                                {{ date('d-M-Y', strtotime($leave_dtl['fromdate'])) }} to {{ date('d-M-Y', strtotime($leave_dtl['todate'])) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Leave Type <span class="float-right">:</span></td>
                                            <td>{{ $leave_dtl['lv_desc'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Day(s) <span class="float-right">:</span></td>
                                            <td>{{ $leave_dtl['days'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Reason <span class="float-right">:</span></td>
                                            <td class="p-2">
                                                {{ $leave_dtl['reason'] }}

                                                @if (!empty($leave_dtl['pdf_file_leave']))
                                                    <a href="{{ $leave_dtl['pdf_file_leave'] }}" target="_blank" class="btn btn-sm btn-primary float-right"><span class="fa fa-file"></span></a>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td rowspan="2" class="font-weight-bold"><i>Approved Leave Period</i> <span class="float-right">:</span></td>
                                            <td class="p-2">
                                                {{ date('d-M-Y', strtotime($leave_dtl['app_fromdate'])) }} to {{ date('d-M-Y', strtotime($leave_dtl['app_todate'])) }} //
                                                {{ $leave_dtl['app_days'] }} Day(s)
                                                
                                                <a href="#" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#edit_lv_date"
                                                data-app_fromdate="{{ $leave_dtl['app_fromdate'] }}" data-app_todate="{{ $leave_dtl['app_todate'] }}"
                                                data-no_days="{{ $leave_dtl['app_days'] }}"><small>Edit Leave Period</small></a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
        
                            </div>
                                
                        </div>
                    </div>
        
                </div>
                
                {{-- STATUS --}}    
                <div class="card">
                    <div class="card-body">
                        <div class="row g-1">
                            @include('components.status', ['otlv_status' => $leave_dtl['leave_timeline']])
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                @include('components.emp_lv_credits')
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="edit_lv_date" tabindex="-1" aria-labelledby="editlvDateFormModal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('update_lvPeriod', ['lv_id' => $leave_dtl['lv_id'], 'empno' =>  $leave_dtl['empno']]) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="editlvDateFormModal">Edit Leave Period</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-5">
                            <label for="fromdate_app" class="form-label font-weight-bold"><small>From Date :</small></label>
                            <input type="text" name="fromdate_app" id="fromdate_app" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                        </div>
                        <div class="col-md-5">
                            <label for="todate_app" class="form-label"><small>To Date :</small></label>
                            <input type="text" name="todate_app" id="todate_app" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                        </div>
                        <div class="col-md-2">
                            <label for="no_days" class="form-label"><small># of days :</small></label>
                            <input type="text" name="no_days" id="no_days" class="form-control form-control-sm text-center" readonly>                
                        </div>

                        <div class="col-md-5">
                            <label for="lv_code" class="form-label"><small>Leave Type :</small></label>
                            <select name="lv_code" id="lv_code" class="selectpicker form-control form-control-sm">
                                <option value="{{ $leave_dtl['lv_code'] }}" selected hidden>{{ $leave_dtl['lv_desc'] }}</option>
                                @foreach ($lv_credits as $lv_cred)
                                    <option value="{{ $lv_cred['lv_code'] }}">{{ $lv_cred['lv_desc'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="reason"><small>Reason <i>(Provide reason for editing the dates)</i> :</small></label>
                            <textarea name="reason" id="reason" class="form-control form-control-sm" cols="3" rows="3" required style="resize: none"></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Save Changes</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/pending_lv.js') }}"></script>
@endsection