@extends('layouts.app')

@section('title', ' - Overtime Dashboard')

@section('content')
<script src="{{asset('/js/moment.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Overtime Dashboard
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="card">

            <div class="card-header">
                <a href="#" id="ot_btn" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#ot_modal"><small>File Overtime</small></a>
            </div>

            <div class="card-body">
                {{-- <button class="btn btn-sm mb-1" id="button">Row count</button> --}}
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="tbl_ot" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date File</th>
                                <th>OT Reference #</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="ot_modal" tabindex="-1" aria-labelledby="otEditFormModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('ot_post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="otEditFormModal">Overtime Application</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="dateot"><small>Date of OT :</small></label>
                            <input type="text" name="dateot" id="dateot" class="form-control form-control-sm text-center" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>

                        <div class="col-md-7">
                            <label><small>Schedule :</small></label>
							<input type="text" name="shift_desc" id="shift_desc" class="form-control form-control-sm text-sm" readonly>
							<input type="hidden" name="shift_code" id="shift_code" class="form-control form-control-sm text-sm" readonly>
                        </div>

                       <div class="col-md-5">
                            <label for="timestart"><small>Time Started : </small></label>
                            <div class="input-group date" id="dt6" data-target-input="nearest">
                                <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timestart" id="dt6" data-target="#dt6" autocomplete="off" required/>
                                <div class="input-group-append" data-target="#dt6" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="timefinish"><small>Time Finished :</small></label>
                            <div class="input-group date" id="dt7" data-target-input="nearest">
                                <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timefinish" id="dt7" data-target="#dt7" autocomplete="off" required/>
                                <div class="input-group-append" data-target="#dt7" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="hours"><small>Hour/s :</small></label>
                            <input type="text" name="hours" value="{{ old('hours') }}" id="hours" class="form-control form-control-sm text-center" readonly="true" />
                        </div> 

                        <div class="col-md-12">
							<label for="clientname"><small>Client's Name :</small></label>
							<input type="text" class="form-control form-control-sm" name="clientname" id="clientname" value="{{ old('clientname') }}" placeholder="Ex. Client1, Client2, Client3 etc ..." required>
                        </div>
                        
                        <div class="col-md-12">
							<label for="workdone"><small>Work Done :</small></label><br>
							<textarea name="workdone" id="workdone" class="form-control form-control-sm" required style="resize: none;"></textarea>
						</div>
						<div class="col-md-12">
                            <div id='to_be_appended'>
                                <div class="row">
                                    <div class="col-md-5 {{ $errors->has('upload') ? ' has-error' : '' }}" id="receipt_resize">
                                        <label class="form-label"><small>Attachment : </small></label>
                                        <br>
                                        <input type="file" id="pdf_file" class='form-control upload-file btn-sm' multiple>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label"><small>Selected Files :</small></label>
                                        <div class="selected-files">
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <label for="pdf_file_ot"><small>Attachment :</small></label>
                            <div class="form-outline">
                                <input type="file" name="pdf_file_ot[]" class="form-control form-control-sm pdf_file_ot" multiple/>
                            </div> --}}
                        </div>
                        
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Add Overtime</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/ot_dashboard.js') }}"></script>

@endsection