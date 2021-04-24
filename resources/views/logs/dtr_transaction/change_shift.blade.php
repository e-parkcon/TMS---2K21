@extends('layouts.app')

@section('content')
    
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Change Shift
        @endslot
    @endcomponent

    <div class="col-md-12">
        <div class="row g-1">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th width=15%>Date</th>
                                    <th class="text-center" width=5%>Day</th>
                                    <th class="text-center" width=10%>Shift</th>
                                    <th class="text-center">AM IN</th>
                                    <th class="text-center">AM OUT</th>
                                    <th class="text-center">PM IN</th>
                                    <th class="text-center">PM OUT</th>
                                    <th class="text-center" width=5%>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daily_logs as $logs)
                                    <tr>
                                        <td>{{ date('F d, Y', strtotime($logs->txndate)) }}</td>
                                        <td class="text-center">{{ date('D', strtotime($logs->txndate)) }}</td>
                                        <td class="text-center">{{ $logs->shift }}</td>
                                        <td class="text-center">{{ $logs->in == "" ? "" : date('h:i', strtotime($logs->in)) }}</td>
                                        <td class="text-center">{{ $logs->break_out == "" ? "" : date('h:i', strtotime($logs->break_out)) }}</td>
                                        <td class="text-center">{{ $logs->break_in == "" ? "" : date('h:i', strtotime($logs->break_in)) }}</td>
                                        <td class="text-center">{{ $logs->out == "" ? "" : date('h:i', strtotime($logs->out)) }}</td>
                                        <td class="text-center">
                                            <a href="#" data-toggle="modal" data-target="#shift" 
                                                    data-txndate="{{ date('Y-m-d', strtotime($logs->txndate)) }}"
                                                    data-empno="{{ $logs->empno }}"
                                                    data-curr_shift="{{ $logs->shift }}"
                                                    class="btn btn-sm btn-primary" title="Change Shift"><span class="fa fa-edit"></span></a>
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
<div class="modal fade" tabindex='-1' id='shift' role='dialog'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shift</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
            </div>

            <form action="{{ route('update_shift') }}" class="form-horizontal" method="POST">
                {{ csrf_field() }}
                {{ method_field('patch') }}

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="curr_shift"><small class="font-weight-bold">Current Shift :</small></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="curr_shift" id="curr_shift" class="form-control form-control-sm selectpicker" disabled>
                                        @foreach($shift as $pr_shift)
                                            <option value="{{ $pr_shift->shift }}">{{ $pr_shift->desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="new_shift"><small class="font-weight-bold">New Shift :</small></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="new_shift" id="new_shift" class="form-control form-control-sm selectpicker" data-live-search="true" data-size="10">
                                        <option selected disabled hidden>Choose Shift</option>
                                        @foreach($shift as $pr_shift)
                                            <option value="{{ $pr_shift->shift }}">{{ $pr_shift->desc }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="empno" id="empno" class="form-control" readonly>
                                    <input type="hidden" name="txndate" id="txndate" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-primary"><small>Save Changes</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="modal_close"><small>Close</small></button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script type="text/javascript">
$('#shift').on('show.bs.modal', function(event){
    var button      = $(event.relatedTarget) // Button that triggered the modal
    var curr_shift     = button.data('curr_shift') // Extract info from data-* attributes
    var empno     = button.data('empno')
    var txndate     = button.data('txndate')

    var modal = $(this)
    
    modal.find('.modal-body #curr_shift').val(curr_shift)
    modal.find('.modal-body #empno').val(empno)
    modal.find('.modal-body #txndate').val(txndate)

});

</script>
@endsection