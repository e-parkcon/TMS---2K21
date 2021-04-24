@extends('layouts.app')

@section('title', ' - Overtime Lists')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Overtime lists
        @endslot
    @endcomponent


    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <a href="{{ route('ot_dashboard') }}" class="btn btn-sm btn-link"><< OT Dashboard</a>
                    </div>
                    <div class="col-md-12">

                        <div class="table-responsive">

                            <table class="table table-sm table-hover" id="tbl_ot_lists">
                                <caption>
                                    <small><i>Date Filed : {{ date('M. d, Y H:i A', strtotime($ot_lists[0]['created_at'])) }}</i></small>
                                </caption>
                                <thead>
                                    <tr>
                                        <th width="10%">Date OT</th>
                                        <th width="30%">Overtime Period</th>
                                        <th width="30%">Client's Name</th>
                                        <th width="20%">Work Done</th>
                                        <th class="text-center">Hrs</th>
                                        <th width="10%" class="text-center" title="Approved Hours">Appr. Hrs</th>
                                        <th width="5%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ot_lists as $list)
                                        <tr>
                                            <td class="text-uppercase">{{ date('d-M-y', strtotime($list['dateot'])) }}</td>
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
                                            <td class="text-center">{{ $list['hours'] }}</td>
                                            <td class="text-center">{{ $list['appr_hours'] }}</td>
                                            <td class="text-center pt-2">
                                                <div class="dropdown">
                                                    <button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                        @if($list['status'] == '')
                                                            <a href='#' class='dropdown-item' id='cancel_ot' refno="{{ $list['refno'] }}" ot_id="{{ $list['id'] }}"><small>Cancel Overtime</small></a>
                                                        @endif
                                                        <a href='#' class='dropdown-item' id='view_status' ot_id="{{ $list['id'] }}"><small>View Status</small></a>
                                                        <a href='#' class='dropdown-item' id='printOT_pdf' refno="{{ $list['refno'] }}" ot_id="{{ $list['id'] }}" empno="{{ $list['empno'] }}"><small>Download as PDF</small></a>
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


<script src="{{ asset('js/overtime_lists.js') }}"></script>
<script src="{{ asset('js/view_attachment.js') }}"></script>

@endsection