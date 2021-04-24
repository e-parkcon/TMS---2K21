@extends('layouts.app')

@section('title', ' - Overtime Summary')

@section('content')
<script>
    jQuery(document).ready(function($){
    
        $(function() {
            $( "#fromdate" ).datepicker({
                dateFormat: 'yy-mm-dd',
                // defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                $( "#todate" ).datepicker( "option", "minDate", selectedDate );
            }
            });
            $( "#todate" ).datepicker({
                dateFormat: 'yy-mm-dd',
                // defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                $( "#fromdate" ).datepicker( "option", "maxDate", selectedDate );
            }
            });
        });
    });
    </script>
<div class="row g-2 mt-0">

    @component('components.content_header')
        @slot('title')
            Overtime Summary Application
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="card">

            <div class="card-body">

                <a href="#" data-toggle="modal" data-target="#ot_search" class="btn btn-sm btn-primary"><small>Search  &nbsp;<span class="fa fa-search"></span></small></a>
                @if(count($ot_summary) != 0)
                    <a href="#" id="dl_otpdf" empno_name="{{ Request::GET('empno') }}"
                                                fromdate="{{ Request::GET('fromdate') }}" todate="{{ Request::GET('todate') }}" 
                                                entity01="{{ Request::GET('cocode') }}" entity02="{{ Request::GET('distcode') }}"
                                                entity03="{{ Request::GET('brchcode') }}"
                                                status="{{ Request::GET('status') }}" class="btn btn-sm btn-success"><small>Download PDF</small></a>

                    <a href="#" id="dl_otcsv" empno_name="{{ Request::GET('empno') }}"
                                                fromdate="{{ Request::GET('fromdate') }}" todate="{{ Request::GET('todate') }}" 
                                                entity01="{{ Request::GET('cocode') }}" entity02="{{ Request::GET('distcode') }}"
                                                entity03="{{ Request::GET('brchcode') }}"
                                                status="{{ Request::GET('status') }}" class="btn btn-sm btn-warning"><small>Download CSV</small></a>
                @endif

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-hover" id="tbl_ot_summ" style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="25%">Name</th>
                                <th>Reference Number</th>
                                <th width="10%">Date Filed</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ot_summary as $ot_summ)
                                <tr id="table_child" >
                                    {{-- <td>{{ $ot_summ->empno }}</td> --}}
                                    <td>{{ $ot_summ->fname }} {{ $ot_summ->lname }}</td>
                                    <td>{{ $ot_summ->refno }}</td>
                                    <td>{{ date('M. d, Y', strtotime($ot_summ->created_at)) }}</td>
                                    <td class="text-center">
                                        {{-- <a href="{{ url('/others/ot-list/' . $ot_summ->refno . '/' . $ot_summ->empno) }}" class="text-primary" title="View"><span class="fa fa-eye"></span></a> --}}
                                        <a href="{{ route('ot_summary.details', [$ot_summ->refno, $ot_summ->empno]) }}" class="btn btn-sm btn-primary"><small>View</small></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
			<div class="card-footer">
				<ul class="pagination pagination-sm m-0 float-right">{{ $ot_summary->links() }}</ul>
			</div>
        </div>

    </div>

</div>

<div class="modal fade" id="ot_search" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-sm font-weight-bold">Overtime Summary</h6>
				<button type="button" class="close text-sm" data-dismiss="modal" ><span aria-hidden="true">×</span></button>
			</div>

			<form class="form-horizontal" action="{{ route('ot_summary') }}" id="ot_search" method="GET">
				<div class="modal-body">
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>ID # / Name </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-3">
							<!-- <input type="text" name="empno_name" id="empno_name" class="form-control form-control-sm" placeholder="ID No. / Name" value="" autocomplete="off"> -->
							<select name="empno" id="empno" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
								<option selected hidden disabled>ID #</option>
								@foreach($idNo as $emp)
									<option value="{{ $emp->empno }}" data-name="{{ $emp->fname }} {{ $emp->lname }}">{{ $emp->empno }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-5">
							<!-- <input type="text" name="empno_name" id="empno_name" class="form-control form-control-sm" placeholder="ID No. / Name" value="" autocomplete="off"> -->
							<select name="name" id="name" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
								<option selected hidden disabled>Name</option>
								@foreach($name as $ngalan)
									<option value="{{ $ngalan->fname }} {{ $ngalan->lname }}" data-empno="{{ $ngalan->empno }}">{{ $ngalan->fname }} {{ $ngalan->lname }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>From Date </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="fromdate" id="fromdate" class="form-control text-center form-control-sm" placeholder="Fromdate" value="{{ Request::GET('fromdate') }}" autocomplete="off" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>To Date </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="todate" id="todate" class="form-control text-center form-control-sm" placeholder="Todate" value="{{ Request::GET('todate') }}" autocomplete="off" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Company </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="cocode" id="cocode" class="selectpicker form-control form-control-sm">
								<option selected disabled>Choose Company</option>
								@foreach($company as $comp)
                                    <option value="{{ $comp->entity01 }}">{{ $comp->entity01_desc }}</option>
                                @endforeach
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>District </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="distcode" id="distcode" class="selectpicker form-control form-control-sm">
								<!-- <option selected disabled>Choose District</option> -->
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Branch </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="brchcode" id="brchcode" class="selectpicker form-control form-control-sm" data-live-search="true"  data-size="10">
								<!-- <option selected disabled>Choose Branch</option> -->
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Status </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="status" id="status" class="selectpicker form-control form-control-sm" required>
								<option value="All" selected>All</option>
								<option value="Approved">Approved</option>
								<option value="Disapproved">Disapproved</option>
								
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
                    <div class="row g-1">
                        <div class="col-md-12 text-right">
							<button type="submit" class="btn btn-sm btn-primary"><small>Search</small></button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" ><small>Close</small></button>
                        </div>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$('#empno').change(function(event){
	
	var name = $('#empno').find(':selected').data('name');

	$('#name').val(name);
	$('#name').selectpicker('refresh');

	console.log(name);
});

$('#name').change(function(event){
	
	var empno = $('#name').find(':selected').data('empno');

	$('#empno').val(empno);
	$('#empno').selectpicker('refresh');

	console.log(empno);
});

$('#cocode').on('change',function(){
    console.log('test');
    var cocode = $("#cocode").val();    
    
    if(cocode){
        $.ajax({
            type:"GET",
            url:"/entity02?entity01="+ cocode,
            success:function(res){
                if(res == ''){
                    $("#distcode").empty();
                    $('#distcode').selectpicker('refresh');
                }
                else{
                    console.log(res);
                    $("#distcode").empty();
                    $("#distcode").append('<option selected hidden disabled>Select District</option>');
                    $.each(res,function(key,value){
                        $("#distcode").append('<option value="'+key+'">'+value+'</option>');
                        $('#distcode').selectpicker('refresh');
                    });
                }
            }
        });
    }
    else{
        $("#distcode").empty();
        $('#distcode').selectpicker('refresh');
    }
});


$('#distcode').on('change',function(){
    var cocode = $("#cocode").val();    
    var distcode = $("#distcode").val();

    if(cocode){
        $.ajax({
            type:"GET",
            url:"/entity03?entity01="+ cocode+'&'+'entity02='+distcode,
            success:function(res){

                if(res == ''){
                    $("#brchcode").empty();
                    $('#brchcode').selectpicker('refresh');
                }
                else{
                    $("#brchcode").empty();
                    $("#brchcode").append('<option selected hidden disabled>Select Branch</option>');
                    $.each(res,function(key,value){
                        $("#brchcode").append('<option value="'+key+'">'+value+'</option>');
                        $('#brchcode').selectpicker('refresh');
                    });
                }
            }
        });
    }else{
        $("#brchcode").empty();
        $('#brchcode').selectpicker('refresh');
    }
});



// $('#ot_serts').click(function(){
// 	var old_status = $('#status').find(':selected').val();
// 	// console.log(old_status);
// 	localStorage.setItem('ot_stat', old_status)
	
// });
// $('#status').val(localStorage.getItem('ot_stat'));

// function get_data(object){

// 	this.empno_name	=   object.parent().parent().attr('empno_name');
// 	this.fromdate   =   object.parent().parent().attr('fromdate');
// 	this.todate		=   object.parent().parent().attr('todate');
// 	this.entity01   =   object.parent().parent().attr('entity01');
//  this.entity02   =   object.parent().parent().attr('entity02');
// 	this.entity03   =   object.parent().parent().attr('entity03');
// 	this.status     =   object.parent().parent().attr('status');
// }

$('#dl_otpdf').click(function(){

	// var obj         =   new get_data($(this));

	var empno_name 	= 	$('#dl_otpdf').attr('empno_name');
	var fromdate 	= 	$('#dl_otpdf').attr('fromdate');
	var todate 		= 	$('#dl_otpdf').attr('todate');
	var entity01 	= 	$('#dl_otpdf').attr('entity01');
	var entity02 	= 	$('#dl_otpdf').attr('entity02');
	var entity03 	= 	$('#dl_otpdf').attr('entity03');
	var status 		= 	$('#dl_otpdf').attr('status');

	console.log(empno_name, fromdate, todate, entity01, entity02, entity03, status);
	$.confirm({
		animation: 'top',
		animateFromElement: false,
		draggable: false,
		title: '<b class="text-primary text-uppercase">Download PDF</b>',
		buttons:{
			close:{
				btnClass: 'btn-danger',
				action: function(){

				}
			},
			confrim:{
				btnClass: 'btn-primary',
				action: function(){

					if(empno_name == '' && fromdate == '' && todate == '' && status == ''){
						$.alert({
							animation: 'zoom',
							animateFromElement: false,
							title: '<span class="text-danger fa fa-exclamation-triangle"></span> <b class="text-danger">Alert!</b>',
							content: '<h6>Fields are empty!</h6>',
							buttons:{
								close:{
									btnClass: 'btn-danger',
									action: function(){

									}
								}
							}
						});

						return false;
					}

					var url = 'http://tms.ideaserv.com.ph:8080/ot_pdf?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&cocode='+entity01+'&distcode='+entity02+'&brchcode='+entity03+'&status='+status
					// var url = 'http://192.168.0.64:8000/ot_pdf?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&cocode='+entity01+'&distcode='+entity02+'&brchcode='+entity03+'&status='+status
					myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
					myWindow.focus();

				}
			}
		}
	})

});

$('#dl_otcsv').click(function(){

	// var empno_name 	= $('#empno_name').val();
	// var fromdate 	= $('#fromdate').val();
	// var todate 		= $('#todate').val(); 
	// var status 		= $('#status').find(':selected').val();

	// var empno_name = document.getElementById('empno_name').value;
	// var fromdate = document.getElementById('fromdate').value;
	// var todate = document.getElementById('todate').value;
	// var status = document.getElementById('status').value;

	// console.log(empno_name, fromdate, todate, status);

	var empno_name 	= 	$('#dl_otcsv').attr('empno_name');
	var fromdate 	= 	$('#dl_otcsv').attr('fromdate');
	var todate 		= 	$('#dl_otcsv').attr('todate');
	var entity01 	= 	$('#dl_otcsv').attr('entity01');
	var entity02 	= 	$('#dl_otcsv').attr('entity02');
	var entity03 	= 	$('#dl_otcsv').attr('entity03');
	var status 		= 	$('#dl_otcsv').attr('status');

	console.log(empno_name, fromdate, todate, entity01, entity02, entity03, status);
	$.confirm({
		animation: 'none',
		animateFromElement: false,
		draggable: false,
		title: '<b class="text-primary text-uppercase">Download CSV</b>',
		buttons:{
			close:{
				btnClass: 'btn-danger',
				action: function(){

				}
			},
			confrim:{
				btnClass: 'btn-primary',
				action: function(){
					if(empno_name == '' && fromdate == '' && todate == '' && status == ''){
						$.alert({
							animation: 'zoom',
							animateFromElement: false,
							title: '<span class="text-danger fa fa-exclamation-triangle"></span> <b class="text-danger">Alert!</b>',
							content: '<h6>Fields are empty!</h6>',
							buttons:{
								close:{
									btnClass: 'btn-danger',
									action: function(){

									}
								}
							}
						});

						return false;
					}
					
					window.location.href = 'http://tms.ideaserv.com.ph:8080/ot_csv?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity02='+entity02+'&entity03='+entity03+'&status='+status
					// window.location.href = 'http://192.168.0.64:8000/ot_csv?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity02='+entity02+'&entity03='+entity03+'&status='+status
					// myWindow = window.open(url, '', 'width=800,height=700,scrollbars=1');
					// myWindow.focus();

				}
			}
		}
	})

});
</script>
{{-- <script src="{{ asset('js/ot_summary.js') }}"></script> --}}
@endsection