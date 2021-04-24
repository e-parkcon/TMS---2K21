@extends('layouts.app')

@section('title', ' - Leave Dashboard')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Leave Dashboard
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
                                    <a class="nav-link active p-3" id="v-pills-leave-dashboard-tab" href="{{ route('lv_dashboard') }}" role="tab" aria-controls="v-pills-leave-dashboard" aria-selected="true">Leave Dashboard</a>
                                    <a class="nav-link p-3" id="v-pills-leave-ledger-tab" href="{{ route('leave.leave_ledger') }}" role="tab" aria-controls="v-pills-leave-ledger" aria-selected="false">
                                        Leave Ledger
                                    </a>
                                </div>
        
                            </div>
                        </div>
                    </div>
        
                    {{-- USER LEAVE CREDITS --}}
                    <div class="col-md-12">
                        @include('components.emp_lv_credits')
                    </div>
                    
                </div>
            </div>

            <div class="col-md-9">

                <div class="card">
                    <div class="card-body">
                        <a href="#" id="lv_btn" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#lv_modal"><small>File Leave</small></a>
                        <div class="row">
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-hover" id="tbl_leave" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th width="25%">Leave Period</th>
                                            <th width="15%">Leave Type</th>
                                            <th width="30%">Reason</th>
                                            <th width="5%">Day(s)</th>
                                            <th width="5%">Status</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="lv_modal" tabindex="-1" aria-labelledby="leaveFormModal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form action="{{ route('lv_post') }}" id="lv_form" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="leaveFormModal">Leave Application</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">                        
                        <div class="col-md-5">
                            {{-- <div class="form-outline"> --}}
                                <label for="fromdate" class="form-label font-weight-bold"><small>From Date :</small></label>
                                <input type="text" name="fromdate" id="fromdate" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                        </div>
                        {{-- </div> --}}
                        <div class="col-md-5">
                            {{-- <div class="form-outline"> --}}
                                <label for="todate" class="form-label"><small>To Date :</small></label>
                                <input type="text" name="todate" id="todate" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                            {{-- </div> --}}
                        </div>
                        <div class="col-md-2">
                            {{-- <div class="form-outline"> --}}
                                <label for="no_days" class="form-label"><small># of days :</small></label>
                                <input type="text" name="no_days" id="no_days" class="form-control form-control-sm text-center" readonly>
                                <input type="hidden" name="day_lapse" id="day_lapse" class="form-control form-control-sm text-center" readonly>
                            {{-- </div> --}}
                        </div>

                        <div class="col-md-5">
                            <label for="leavecode" class="form-label"><small>Leave Type</small></label>
                            <select name="leavecode" id="leavecode" class="selectpicker form-control form-control-sm" required>
                                <option selected disabled hidden>Choose Leave Type</option>
                                @foreach($lv_credits as $lv_type)
                                    <option value="{{ $lv_type['lv_code'] }}" data-lv_desc="{{ $lv_type['lv_desc'] }}">{{ $lv_type['lv_desc'] ?? 'Error' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            {{-- <div class="form-outline"> --}}
                                <label for="pdf" class="form-label"><small>Attachment</small></label>
                                <input type="file" name="pdf[]" id="pdf" value="{{ old('pdf') }}" class="form-control form-control-sm" disabled="disabled" multiple />
                            {{-- </div> --}}
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="form-outline"> --}}
                                <label class="form-label" for="reason"><small>Reason :</small></label>
                                <textarea class="form-control text-sm" name="reason" id="reason" rows="3" cols="4" style="resize: none" required></textarea>
                            {{-- </div> --}}
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <button type="submit" id="leave_submit" class="btn btn-sm btn-primary"><small>File Leave</small></button>
                            <button type="button" id="close_modal" class="btn btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="lv_edit_modal" tabindex="-1" aria-labelledby="leaveEditFormModal" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form action="{{ route('lv_update') }}" id="lv_edit_form" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="leaveFormModal">Leave Application</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-md-5">
                            <label for="edt_fromdate" class="form-label font-weight-bold"><small>From Date :</small></label>
                            <input type="text" name="edt_fromdate" id="edt_fromdate" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                        </div>
                        <div class="col-md-5">
                            <label for="edt_todate" class="form-label"><small>To Date :</small></label>
                            <input type="text" name="edt_todate" id="edt_todate" class="form-control form-control-sm text-center" placeholder="Y-m-d" required>
                        </div>
                        <div class="col-md-2">
                            <label for="edt_no_days" class="form-label"><small># of days :</small></label>
                            <input type="text" name="edt_no_days" id="edt_no_days" class="form-control form-control-sm text-center" readonly>
                        </div>

                        <div class="col-md-5">
                            <select name="edt_lv_type" id="edt_lv_type" class="selectpicker form-control form-control-sm form-select form-select-sm" required>
                                <option selected disabled hidden>Choose Leave Type</option>
                                @foreach($lv_credits as $lv_type)
                                    <option value="{{ $lv_type['lv_code'] }}" data-lv_desc="{{ $lv_type['lv_desc'] }}">{{ $lv_type['lv_desc'] ?? 'Error' }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-5">
                            <div class="form-outline">
                                <input type="file" name="edt_pdf[]" id="edt_pdf" value="{{ old('edt_pdf') }}" class="form-control form-control-sm" disabled="disabled" multiple />
                            </div>
                        </div> --}}

                        <div class="col-md-12">
                            <input type="hidden" name="lv_id" id="lv_id" class="form-control form-control-sm" readonly>
                                <label class="form-label" for="edt_reason">Reason</label>
                                <textarea class="form-control text-sm" name="edt_reason" id="edt_reason" rows="4" style="resize: none" required></textarea>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Submit</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/leave_dashboard.js') }}"></script>

@endsection