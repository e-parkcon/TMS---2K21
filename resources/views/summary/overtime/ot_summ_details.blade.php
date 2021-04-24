@extends('layouts.app')

@section('title', ' - OT Summary Details')

@section('content')

<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Overtime Details
        @endslot
    @endcomponent

    <div class="col-md-12">
    
        <div class="card">

            <div class="card-body">

                <div class="row g-3">
            
                    <div class="col-md-2">
                        <a href="{{ route('ot_summary') }}" class="btn btn-sm btn-link"><< OT Summary</a>
                    </div>
                    <div class="col-md-12">

                        <div class="table-responsive-sm">

                            <table class="table table-sm table-borderless m-0" style="width: 50%;">
                                <tr>
                                    <td width="20%" class="font-weight-bold">Name <span class="float-right">:</span></td>
                                    <td>{{ $emp_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Branch <span class="float-right">:</span></td>
                                    <td>{{ $emp_branch->entity03_desc }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm table-hover m-0" id="tbl_ot_lists">
                                <caption>
                                    <small><i>Date Filed : {{ date('M. d, Y H:i A', strtotime($ot_lists[0]['created_at'])) }}</i></small>
                                </caption>
                                <thead>
                                    <tr>
                                        <th width="10%">Date OT</th>
                                        <th width="25%">Overtime Period</th>
                                        <th width="30%">Client's Name</th>
                                        <th width="25%">Work Done</th>
                                        <th class="text-center">Hrs</th>
                                        <th width="5%" class="text-center" title="Approved Hours">App. Hrs</th>
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
                                            <td class="text-center">
                                                <div class="dropdown dropleft">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="#" class="dropdown-item" id="view_status" ot_id="{{ $list['id'] }}"><small>View Status</small></a>
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

<script src="{{ asset('js/view_attachment.js') }}"></script>
<script>
$(document).ready(function(){

    $('a#view_status').click(function(){

        var ot_id   =   $(this).attr('ot_id');

        $.ajax({
            method: 'GET',
            url: '/status/'+ ot_id,
            success: function(res){
                $.alert({
                    columnClass: 'col-xs-12 col-sm-8 col-md-5',
                    containerFluid: true, // this will add 'container-fluid' instead of 'container'
                    animation: 'top',
                    animateFromElement: false,
                    draggable: false,
                    type: 'dark',
                    icon: 'fa fa-calendar',
                    title: '---',
                    content: res, // BLADE VIEW
                    buttons:{
                        close:{
                            btnClass: 'btn-danger',
                            action: function(){

                            }
                        }
                    }
                });
            }
        });
    });

});
</script>

@endsection