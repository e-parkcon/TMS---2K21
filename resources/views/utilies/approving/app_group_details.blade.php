@extends('layouts.app')

@section('title', ' - Approving Group Details')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            {{ $approving_group->app_desc }}
        @endslot
    @endcomponent

    <div class="col-md-12">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label text-uppercase text-primary text-sm">Add Officer : </label>
                        <div class="row g-1">
                            <div class="col-md-12">
                                <select name="app_officers" id="app_officers" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
                                    <option selected hidden disabled>Choose Officer</option>
                                    @foreach ($officer_lists as $off_list)
                                        <option class="text-uppercase" value="{{ $off_list->fname }} {{ $off_list->lname }}" empno="{{ $off_list->empno }}" email="{{ $off_list->email }}" crypt="{{ $off_list->crypt }}"><small>{{ $off_list->fname }} {{ $off_list->lname }}</small></option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="app_empno_officer" id="app_empno_officer" class="form-control form-control-sm" readonly>
                                <input type="hidden" name="app_crypt" id="app_crypt" class="form-control form-control-sm" readonly>
                                <input type="text" name="app_email" id="app_email" class="form-control form-control-sm mt-1" placeholder="Email" readonly>
                            </div>
                            <div class="col-md-6 offset-md-6">
                                <a href="#" class="btn btn-sm btn-primary btn-block" id="add_app_officer"><small>Add Officer</small></a>
                                <input type="hidden" name="category" id="category" class="form-control form-control-sm" value="{{ $approving_group->otlv }}" readonly>
                                <input type="hidden" name="app_code" id="app_code" class="form-control form-control-sm" value="{{ $approving_group->app_code }}" readonly>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <label class="form-label text-uppercase text-primary text-sm">Lists of Officer </label>
                            <table class="table table-sm table-hover">
                                <tbody id="tbl_officers">
                                    @foreach ($officers as $key => $officer)
                                        <tr id="officer_{{ $officer['empno'] }}">
                                            <td class="text-uppercase"># {{ $officer['seqno']+1 }}<span class="float-right">:</span></td>
                                            <td class="text-uppercase">{{ $officer['name'] }}<br>email : {{ $officer['email'] }}</td>
                                            <td width="20%" class="text-center">
                                                <a href="#" class="btn btn-sm btn-primary" id="change_officer" data-old_officer="{{ $officer['empno'] }}" data-seqno="{{ $officer['seqno'] }}" data-toggle="modal" data-target="#change_approving" title="Change Officer"><span class="fa fa-user-edit"></span></a> 
                                                <a href="#" class="btn btn-sm btn-danger" id="remove_officer" 
                                                    category="{{ $officer['otlv'] }}" 
                                                    app_code="{{ $officer['app_code'] }}" 
                                                    empno="{{ $officer['empno'] }}" title="Remove Officer"><span class="fa fa-user-times"></span></a>
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

    <div class="col-md-12">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label text-uppercase text-primary text-sm">Add Member : </label>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <select name="app_members" id="app_members" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
                                    <option selected hidden disabled>Choose Member</option>
                                    @foreach ($member_lists as $mem_list)
                                        <option class="text-uppercase" value="{{ $mem_list->empno }}" category="{{ $approving_group->otlv }}" app_code="{{ $approving_group->app_code }}"><small>{{ $mem_list->fname }} {{ $mem_list->lname }}</small></option>
                                    @endforeach
                                </select>
                                <input type="text" name="app_empno" id="app_empno" class="form-control form-control-sm mt-1" placeholder="Employee ID #" readonly>
                            </div>
                            <div class="col-md-6 offset-md-6">
                                <a href="#" id="add_new_member" class="btn btn-sm btn-primary btn-block"><small>Add Member</small></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <label class="form-label text-uppercase text-primary text-sm">Lists of Member</label>
                            <table class="table table-sm table-hover" id="tbl_app_member">
                                <tbody>
                                    @foreach ($members as $key => $member)
                                        <tr id="member_{{ $member['empno'] }}">
                                            <td class="text-uppercase">{{ $member['name'] }}</td>
                                            <td width="15%">{{ $member['empno'] }}</td>
                                            <td width="10%" class="text-center">
                                                <a href="#" id="remove_member" class="btn btn-sm btn-danger" 
                                                    category="{{ $member['category'] }}" 
                                                    app_code="{{ $member['app_code'] }}" 
                                                    empno="{{ $member['empno'] }}"><span class="fa fa-user-times"></span></a>
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

<div class="modal fade" id="change_approving" tabindex="-1" aria-labelledby="leaveEditFormModal" aria-hidden="true" data-backdrop="true" data-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-uppercase text-primary">Change Approving Officer : </label>
            </div>
            <form id="change_offr_form">
                <div class="modal-body">
                    <select name="new_app_officers" id="new_app_officers" class="form-control form-control-sm custom-select" data-live-search="true">
                        <option selected hidden disabled>Choose Officer</option>
                        @foreach ($officer_lists as $off_list)
                            <option class="text-uppercase" value="{{ $off_list->fname }} {{ $off_list->lname }}" empno="{{ $off_list->empno }}" email="{{ $off_list->email }}" crypt="{{ $off_list->crypt }}"><small>{{ $off_list->fname }} {{ $off_list->lname }}</small></option>
                        @endforeach
                    </select>
                    <input type="hidden" name="new_app_empno_officer" id="new_app_empno_officer" class="form-control form-control-sm" placeholder="empno_officer" readonly>
                    <input type="hidden" name="new_app_crypt" id="new_app_crypt" class="form-control form-control-sm" placeholder="app_crypt" readonly>
                    <input type="hidden" name="category" id="category" class="form-control form-control-sm" value="{{ $approving_group->otlv }}" readonly>
                    <input type="hidden" name="app_code" id="app_code" class="form-control form-control-sm" value="{{ $approving_group->app_code }}" readonly>
                    <input type="hidden" name="seqno" id="seqno" class="form-control form-control-sm" placeholder="seqno" readonly>
                    <input type="text" name="new_app_email" id="new_app_email" class="form-control form-control-sm mt-1" placeholder="Email" readonly>
                    <input type="hidden" name="old_officer" id="old_officer" class="form-control form-control-sm mt-1" placeholder="old_officer" readonly>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12">
                            <a href="#" id="save_changes_offr" class="btn btn-sm btn-primary"><small>Save Changes</small></a>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('/js/utilities/approving_details.js') }}"></script>

@endsection