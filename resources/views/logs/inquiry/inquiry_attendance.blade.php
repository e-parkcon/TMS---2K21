@extends('layouts.app')

@section('title', ' - Attendance Inquiry')

@section('content')


<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Attendance Inquiry
        @endslot
    @endcomponent

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form id="form_att_inq">
                    {{-- <label class="form-label text-uppercase text-sm text-primary">Search</label> --}}
                    <div class="row g-2">
                        <div class="col-4 col-md-4">
                            <label class="form-label" for="txndate"><small>Date</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <input type="text" name="txndate" id="txndate" class="form-control text-center form-control-sm" placeholder="Date" autocomplete="off" value="{{ Request::GET('txndate') }}" required>
                        </div>

                        <div class="col-4 col-md-4">
                            <label class="form-label" for="company"><small>Company</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <select name="company" type="text" id="company" class="selectpicker form-control form-control-sm" data-live-search="true">
                                <option selected value="%"> ALL </option>
                                @foreach($company as $company)
                                    <option value="{{ $company->entity01 }}"> {{ $company->entity01_desc }}
                                    </option>
                                @endforeach	
                            </select>
                        </div>

                        <div class="col-4 col-md-4">
                            <label class="form-label" for="district"><small>District</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <select name="district" type="text" id="district" class="selectpicker form-control form-control-sm" data-live-search="true">
                                <option value="%"> ALL </option>
                                @foreach($district as $district)
                                    <option value="{{ $district->entity02_desc }}"> {{ $district->entity02_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-4 col-md-4">
                            <label class="form-label" for="branch"><small>Branch</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <select name="branch" type="text" id="branch" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
                                <option value="%"> ALL </option>
                                @foreach($branch as $branch)
                                    <option value="{{ $branch->entity03_desc }}"> {{ $branch->entity03_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-4 col-md-4">
                            <label class="form-label" for="department"><small>Department</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <select name="department" type="text" id="department" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
                                <option value="%"> ALL</option>
                                @foreach($department as $department)
                                    <option value="{{ $department->deptcode }}"> {{ $department->deptdesc }}
                                    </option>
                                @endforeach	
                            </select>
                        </div>

                        <div class="col-4 col-md-4">
                            <label class="form-label" for="select1"><small>Select</small></label><span class="float-right">:</span>
                        </div>
                        <div class="col-8 col-md-8">
                            <div class="row">
                                <div class="col-4 col-md-12">
                                    <input type="radio" id="absent" name="select1" value="Absent" checked="checked" />
                                    <label class="form-label" for="absent"><small>Absent</small></label>
                                </div>
                                <div class="col-4 col-md-12">
                                    <input type="radio" id="present" name="select1" value="Present" />
                                    <label class="form-label" for="present"><small>Present</small></label>
                                </div>
                                <div class="col-4 col-md-12">
                                    <input type="radio" id="onleave" name="select1" value="On-Leave" />
                                    <label class="form-label" for="onleave"><small>On-Leave</small></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <a href="#" id="search_attInquiry" class="btn btn-primary btn-sm btn-block"><small>Search</small></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-sm">
                    <table class="table table-sm table-hover" id="tbl_att_inquiry" width="100%">
                        <thead>
                            <tr>
                                <th width="25%">Name</th>
                                <th width="20%">Company</th>
                                <th width="20%">Branch</th>
                                <th width="15%">Time In</th>
                                <th width="5%">Logs</th>
                                <th width="1%"></th>
                                <th width="1%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/logs/attendance_inquiry.js') }}"></script>
@endsection