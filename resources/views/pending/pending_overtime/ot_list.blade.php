@extends('layouts.app')

@section('title', ' - Pending OT Lists')

@section('content')

<style>
    .btns_approve_disapprove{
        display: none;
    }
</style>

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Overtime Lists
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="card">

            <div class="card-body">

                <form action="{{ route('pending.approve_selected_ot', [$refno, $emp_name->empno]) }}" method="POST" id="ot_list_form">
                    @csrf
                    @method('PATCH')
                    <div class="row g-3">

                        <div class="col-md-12">
                            <div class="btns_approve_disapprove">
                                {{-- <button type="submit" class="btn btn-sm btn-primary"><small>Approve Selected</small></button> --}}
                                {{-- <button type="submit" class="btn btn-sm btn-danger" formaction="{{ route('pending.disapprove_selected_ot', [$refno, $emp_name->empno]) }}"><small>Disapprove Selected</small></button> --}}
                                <a href="#" id="approve_selected_ot" refno="{{ $refno }}" empno="{{ $emp_name->empno }}" class="btn btn-sm btn-primary"><small>Approve Selected</small></a>
                                <a href="#" id="disapprove_selected_ot" refno="{{ $refno }}" empno="{{ $emp_name->empno }}" class="btn btn-sm btn-danger"><small>Disapprove Selected</small></a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            
                            <div class="table-responsive">

                                <table class="table table-borderless m-0" style="width: 50%;">
                                    <tr>
                                        <td width="20%" class="font-weight-bold">Name <span class="float-right">:</span></td>
                                        <td>{{ $emp_name->fname }} {{ $emp_name->lname }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Branch <span class="float-right">:</span></td>
                                        <td>{{ $emp_branch->entity03_desc }}</td>
                                    </tr>
                                </table>

                                <table class="table table-sm table-hover m-0" width="100%">
                                    <caption>
                                        <small><i>Date Filed : {{ date('M. d, Y H:i A', strtotime($ot_lists[0]['created_at'])) }}</i></small>
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th class="text-center"><input type="checkbox" name="chckAll" id="chckAll" value="{{ $refno }}" title="Check All"></th>	
                                            <th width="10%">Date OT</th>
                                            <th width="30%">Overtime Period</th>
                                            <th width="30%">Client's Name</th>
                                            <th width="20%">Work Done</th>
                                            <th width="10%" class="text-center">Hrs</th>
                                            <th width="5%" class="text-center" title="Approved Hours">App. Hrs</th>
                                            <th width="5%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ot_lists as $list)
                                            <tr>
                                                @if($list['status'] == 'Disapproved')
                                                    <td></td>
                                                @else
                                                    @if (Auth::user()->crypt == $list['ot_crypt'])
                                                        <td class="text-center"><input type="checkbox" name="checked[]" id="checked" class="checkbox" value="{{ $list['seqno'] }}"></td>
                                                    @else
                                                        <td class="text-center text-success">
                                                            <span class="fa fa-check-square"></span>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-uppercase">
                                                    {{ date('d-M-y', strtotime($list['dateot'])) }}
                                                    <input type="hidden" name="id" id="{{ $list['id'] }}" value="{{ $list['id'] }}" class="form-control form-control-sm" readonly>
                                                </td>
                                                <td class="text-uppercase">
                                                    {{ date('d-M-y H:i', strtotime($list['timestart'])) }} - {{ date('d-M-y H:i', strtotime($list['timefinish'])) }}
                                                    <br>
                                                    @if($list['status'] == 'Approved')
                                                        <i class="badge badge-success">Approved</i>
                                                    @elseif($list['status'] == 'Disapproved')
                                                        <i class="badge badge-danger">Disapproved</i>
                                                    @else
                                                        <i class="badge badge-secondary">Pending</i>
                                                    @endif
                                                </td>
                                                <td>{{ $list['clientname'] }}</td>
                                                <td>
                                                    {{ $list['workdone'] }}
                                                    @if ($list['pdf_file_ot'] != '')
                                                        <a href="#" name="attachment" id="attachment" refno="{{ $list['refno'] }}" ot_id="{{ $list['id'] }}" empno="{{ $list['empno'] }}"
                                                            class="float-right text-success"><span class="fa fa-file"></span></a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $list['hours'] }}
                                                    <input type="hidden" class="form-control form-control-sm" name="hours[]" id="hours" value="{{ $list['hours'] }}" readonly>
                                                </td>
                                                <td class="text-center">
                                                    {{ $list['appr_hours'] }}
                                                    <input type="hidden" class="form-control form-control-sm" name="apphours[]" id="apphours" value="{{ $list['appr_hours'] }}" readonly>
                                                </td>
                                                <td class="text-center pt-2">
                                                    <div class="dropdown dropleft">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton_{{ $list['seqno'] }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                            <a href="#" class="dropdown-item" id="edit_hours" ot_id="{{ $list['id'] }}" refno="{{ $list['refno'] }}" empno="{{ $list['empno'] }}"><small>Edit OT hours</small></a>
                                                            <a href="#" class="dropdown-item" id="disapprove_one_ot" ot_id="{{ $list['id'] }}" refno="{{ $list['refno'] }}" empno="{{ $list['empno'] }}"><small>Disapprove</small></a>
                                                            <a href="#" class="dropdown-item" id="view_status" ot_id="{{ $list['id'] }}"><small>View status</small></a>
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
                </form>

            </div>

        </div>

    </div>

</div>

<script src="{{ asset('js/pending_ot_details.js') }}"></script>
<script src="{{ asset('js/view_attachment.js') }}"></script>
@endsection