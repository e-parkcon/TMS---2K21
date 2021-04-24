@extends('layouts.app')

@section('title', ' - Add Overtime')

@section('content')
<script src="{{asset('/js/moment.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Add new overtime
        @endslot
    @endcomponent

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
        
                    <form action="{{ route('post_newOT', $refno) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row g-3">
        
                                <div class="col-md-5">
                                    <label for="dateot"><small>Date of OT :</small></label>
                                    <input type="text" name="dateot" id="dateot" class="form-control form-control-sm text-center" placeholder="YYYY-MM-DD" autocomplete="off" value="{{ Request::old('dateot') }}" required>
                                </div>
                                <div class="col-md-7">
                                    <label><small>Schedule :</small></label>
                                    <input type="text" name="shift_desc" id="shift_desc" class="form-control form-control-sm text-sm" readonly>
                                    <input type="hidden" name="shift_code" id="shift_code" class="form-control form-control-sm text-sm" readonly>
                                </div>
        
                                <div class="col-md-5">
                                    <label for="timestart"><small>Time Started : </small></label>
                                    <div class="input-group date" id="dt6" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timestart" id="dt6" data-target="#dt6" value="{{ Request::old('timestart') }}" autocomplete="off" required/>
                                        <div class="input-group-append" data-target="#dt6" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="timefinish"><small>Time Finished :</small></label>
                                    <div class="input-group date" id="dt7" data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timefinish" id="dt7" data-target="#dt7" value="{{ Request::old('timefinish') }}" autocomplete="off" required/>
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
                                    <input type="text" class="form-control form-control-sm" name="clientname" id="clientname" value="{{ Request::old('clientname') }}" placeholder="Ex. Client1, Client2, Client3 etc ..." required>
                                </div>
                                
                                <div class="col-md-12">
                                    <label for="workdone"><small>Work Done :</small></label><br>
                                    <textarea name="workdone" id="workdone" class="form-control form-control-sm" value="{{ Request::old('workdone') }}" required style="resize: none;"></textarea>
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
                                        <input type="file" name="pdf_file_ot[]" class="form-control form-control-sm" multiple/>
                                    </div> --}}
                                </div>
                                
                            </div>
                        </div>
        
                        <div class="card-footer text-right">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-sm btn-primary"><small>Add overtime</small></button>
                                    <a href="{{ route('ot_dashboard') }}" class="btn btn-sm btn-danger  "><small>Finish later</small></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
        
            </div>

            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-sm btn-primary" id="submit_ot" refno="{{ $refno }}" ><small>Submit Overtime</small></button>
                    </div>
                    {{-- <form method="POST" action="{{ route('submit_ot') }}">
                        {{ csrf_field() }}
                        <div class="card-header">
                            <input type="text" name="refno" value="{{ $refno }}">
                            <button type="submit" class="btn btn-sm btnPrimary">Submit Overtime</button> 
                        </div>
                    </form> --}}
        
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="tbl_add_ot">
                                    <thead>
                                        <tr>
                                            <th width=10%>Date OT</th>
                                            <th width=25% class="text-left">Overtime Period</th>
                                            <th width=25%>Client's Name </th>
                                            <th width=25%>Work Done</th>
                                            <th width=5% class="text-center">Hr(s)</th>
                                            <th class="text-center" width=5%>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overtime as $ot)
                                            <tr>
                                                <td class="text-uppercase">{{ date('d-M-y', strtotime($ot['dateot'])) }}</td>
                                                <td class="text-uppercase">{{ date('d-M-y H:i', strtotime($ot['timestart'])) }} - {{ date('d-M-y H:i', strtotime($ot['timefinish'])) }}</td>
                                                <td>{{ $ot['clientname'] }}</td>
                                                <td>
                                                    {{ $ot['workdone'] }}
                                                    @if ($ot['pdf_file_ot'] != '')
                                                        <a href="#" name="attachment" id="attachment" refno="{{ $ot['refno'] }}" ot_id="{{ $ot['id'] }}" empno="{{ $ot['empno'] }}"
                                                            class="float-right text-success"><span class="fa fa-file"></span></a>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $ot['hours'] }}</td>
                                                <td class="text-center pt-2">
                                                    <div class="dropdown dropleft">
                                                        <button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
        
                                                            <a href='#' class='dropdown-item' id='edit_ot' data-toggle="modal" data-target="#add_ot_modal_edit"
                                                                data-id="{{ $ot['id'] }}" data-empno="{{ $ot['empno'] }}" 
                                                                data-seqno="{{ $ot['seqno'] }}" data-clientname="{{ $ot['clientname'] }}" 
                                                                data-dateot="{{ $ot['dateot'] }}"
                                                                data-shift="{{ $ot['shift'] }}" data-shift_desc="{{ $ot['shift_desc'] }}"
                                                                data-timestart="{{ $ot['timestart'] == '-' ? '-' : date('Y-m-d H:i', strtotime($ot['timestart']))  }}" 
                                                                data-timefinish="{{ $ot['timefinish'] == '-' ? '-' : date('Y-m-d H:i', strtotime($ot['timefinish']))  }}" 
                                                                data-hours="{{ $ot['hours'] }}" data-workdone="{{ $ot['workdone'] }}"><small>Edit overtime</small></a>
        
                                                            <a href='#' class='dropdown-item' id='del_ot' refno="{{ $refno }}" ot_id="{{ $ot['id'] }}"><small>Delete overtime</small></a>
                                                            @if (!empty($ot['pdf_file_ot']))
                                                                <a href='#' class='dropdown-item' id='del_attach' refno="{{ $refno }}" ot_id="{{ $ot['id'] }}"><small>Delete Attachment</small></a>
                                                            @endif
                                                        </div>
                                                    </div>
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
    </div>

</div>


<div class="modal fade" id="add_ot_modal_edit" tabindex="-1" aria-labelledby="otEditFormModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('update_overtime', $refno) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-sm font-weight-bold" id="otEditFormModal">Edit overtime</h5>
                    <button type="button" class="close text-sm" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-5">
                            <label for="dateot"><small>Date of OT :</small></label>
                            <input type="text" name="dateot_edit" id="dateot_edit" class="form-control form-control-sm text-center" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                        <div class="col-md-7">
                            <label><small>Schedule :</small></label>
                            <input type="text" name="shift_desc_edit" id="shift_desc_edit" class="form-control form-control-sm text-sm" readonly>
                            <input type="hidden" name="shift_code_edit" id="shift_code_edit" class="form-control form-control-sm text-sm" readonly>
                        </div>

                        <div class="col-md-5">
                            <label for="timestart_edit"><small>Time Started : </small></label>
                            <div class="input-group date" id="dt6_edit" data-target-input="nearest">
                                <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timestart_edit" id="dt6_edit" data-target="#dt6_edit" autocomplete="off" required/>
                                <div class="input-group-append" data-target="#dt6_edit" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="timefinish_edit"><small>Time Finished :</small></label>
                            <div class="input-group date" id="dt7_edit" data-target-input="nearest">
                                <input type="text" class="form-control form-control-sm text-center datetimepicker-input" name="timefinish_edit" id="dt7_edit" data-target="#dt7_edit" autocomplete="off" required/>
                                <div class="input-group-append" data-target="#dt7_edit" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="hours_edit"><small>Hour/s :</small></label>
                            <input type="text" name="hours_edit" value="{{ old('hours') }}" id="hours_edit" class="form-control form-control-sm text-center" readonly="true" />
                        </div>

                        <div class="col-md-12">
                            <label for="clientname_edit"><small>Client's Name :</small></label>
                            <input type="text" class="form-control form-control-sm" name="clientname_edit" id="clientname_edit" value="{{ old('clientname') }}" placeholder="Ex. Client1, Client2, Client3 etc ..." required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="workdone"><small>Work Done :</small></label><br>
                            <textarea name="workdone_edit" id="workdone_edit" class="form-control form-control-sm" required style="resize: none;"></textarea>
                            <input type="hidden" name="ot_id" id="ot_id" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="col-md-12">
                            <div id='to_be_appended1'>
                                <div class="row">
                                    <div class="col-md-5 {{ $errors->has('upload1') ? ' has-error' : '' }}" id="receipt_resize">
                                        <label class="form-label"><small>Attachment : </small></label>
                                        <br>
                                        <input type="file" id="pdf_file1" class='form-control upload-file1 btn-sm' multiple>
                                        <small style="font-size: 10px;" class="text-danger"><i>* Uploading new files will override the uploaded files.</i></small>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label"><small>Selected Files :</small></label>
                                        <div class="selected-files1">
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <label for="pdf_file_ot_edit"><small>Attachment :</small></label>
                            <div class="form-outline">
                                <input type="file" name="pdf_file_ot_edit[]" class="form-control form-control-sm" multiple/>
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
<script src="{{ asset('js/add_ot.js') }}"></script>
<script src="{{ asset('js/view_attachment.js') }}"></script>
@endsection