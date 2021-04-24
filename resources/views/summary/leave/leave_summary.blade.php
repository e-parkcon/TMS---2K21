@extends('layouts.app')

@section('title', ' - Leave Summary')

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
			@if (Request::segment(1) == 'inquiry')
				Summary of Approve Leave Application
			@else
				Leave Summary Application
			@endif
        @endslot
    @endcomponent

    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                
                <a href="#" id="lv_summ" data-toggle="modal" data-target="#lv_search" class="btn btn-primary btn-sm"> <small>Search &nbsp;<span class="fa fa-search"></span></small></a>
				
				@if(count($lv_summary) != 0)
					<a href="#" id="dl_lvpdf" empno_name="{{ Request::GET('empno') }}"
												fromdate="{{ Request::GET('fromdate') }}" todate="{{ Request::GET('todate') }}" 
												entity01="{{ Request::GET('cocode') }}" entity02="{{ Request::GET('distcode') }}"
												entity03="{{ Request::GET('brchcode') }}"
												status="{{ Request::GET('status') }}" class="btn btn-sm btn-success"><small>Download PDF</small></a>
					
					<a href="#" id="dl_lvcsv" empno_name="{{ Request::GET('empno') }}"
												fromdate="{{ Request::GET('fromdate') }}" todate="{{ Request::GET('todate') }}" 
												entity01="{{ Request::GET('cocode') }}" entity02="{{ Request::GET('distcode') }}"
												entity03="{{ Request::GET('brchcode') }}"
												status="{{ Request::GET('status') }}" class="btn btn-sm btn-warning"><small>Download CSV</small></a>
				@endif

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-hover mb-0" id="tbl_lv_summ" style="width: 100%;">
                        <thead>
                            <tr>
                                <th width="15%">Name</th>
                                <th width="25%">Leave Period</th>
                                <th width="44%">Reason</th>
                                <th width="1%">Day(s)</th>
								<th width="5%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
						<tbody>
							@foreach($lv_summary as $lv_sum)
								<tr title="ID #: {{ $lv_sum->empno }}" id="child-tr" 
										app_fromdate="{{ date('F d, Y', strtotime($lv_sum->app_fromdate)) }}" 
										app_todate="{{ date('F d, Y', strtotime($lv_sum->app_todate)) }}" 
										days="{{ $lv_sum->app_days }}"
										lv_id="{{ $lv_sum->id }}" 
										cocode="{{ $lv_sum->entity01 }}"
										app_code="{{ $lv_sum->app_code }}">
									<td>{{ $lv_sum->fname }} {{ $lv_sum->lname }}</td>
									<td>
										{{ date('M. d, Y', strtotime($lv_sum->fromdate)) }} - {{ date('M. d, Y', strtotime($lv_sum->todate)) }}
										<br>
										<i class="badge badge-success text-uppercase">{{ $lv_sum->lv_desc }}</i>
										<br>
										{{-- <small class="text-uppercase">Date Filed : {{ date('F d, Y', strtotime($lv_sum->created_at)) }}</small> --}}
									</td>
									<td>
										{{ $lv_sum->reason }}

										@if (!empty($lv_sum->pdf_file_leave))
										<a href="{{ $lv_sum->pdf_file_leave }}" target="_blank" class="text-success float-right">
											<span class="fa fa-file"></span>
										</a>
										@endif
									</td>
									<td class="text-center">{{ $lv_sum->total_day }}</td>
									<td>
										@if ($lv_sum->status == 'Approved')
											Approved
										@elseif($lv_sum->status == 'Disapproved')
											Disapproved
										@else
											Pending
										@endif
									</td>
									<td class="text-center">
										<div class="dropdown dropleft">
											<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
											<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
												<a href='#' class='dropdown-item' lv_id="{{ $lv_sum->id }}"  id='view_status'><small>View Status</small></a>
												<a href='#' class='dropdown-item' id='dl_pdf' lv_id="{{ $lv_sum->id }}" empno="{{ $lv_sum->empno }}"><small>Download as PDF</small></a>
											</div>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
                    </table>
                </div>

            </div>
			<div class="card-footer">
				<nav aria-label="Page navigation">
					<ul class="pagination pagination-sm m-0 float-right">{{ $lv_summary->links() }}</ul>
				</nav>
			</div>
        </div>

    </div>

</div>

<div class="modal fade" id="lv_search" role="dialog">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-sm font-weight-bold">Leave Summary</h6>
				<button type="button" class="close text-sm" data-dismiss="modal" ><span aria-hidden="true">×</span></button>
			</div>

			<form class="form-horizontal" action="{{ Request::segment(1) != 'inquiry' ? route('lv_summary') : route('summ_appleave')}}" id="lv_search" method="GET">
				<div class="modal-body">
					@if(Request::segment(1) != 'inquiry')
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
								<select name="name" id="name" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
									<option selected hidden disabled>Name</option>
									@foreach($name as $ngalan)
										<option value="{{ $ngalan->fname }} {{ $ngalan->lname }}" data-empno="{{ $ngalan->empno }}">{{ $ngalan->fname }} {{ $ngalan->lname }}</option>
									@endforeach
								</select>
								<!-- <input type="text" name="empno_name" id="empno_name" class="form-control form-control-sm" placeholder="ID No. / Name" value="" autocomplete="off"> -->
							</div>
						</div>
					@endif
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>From Date</small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="fromdate" id="fromdate" class="form-control text-center form-control-sm" placeholder="Fromdate" value="{{ Request::GET('fromdate') }}" autocomplete="off" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>To Date</small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<input type="text" name="todate" id="todate" class="form-control text-center form-control-sm" placeholder="Todate" value="{{ Request::GET('fromdate') }}" autocomplete="off" required>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Company</small></label><span class="float-right">:</span>
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
							<label for="#"><small>District</small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="distcode" id="distcode" class="selectpicker form-control form-control-sm">
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Branch</small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="brchcode" id="brchcode" class="selectpicker form-control form-control-sm" data-live-search="true" data-size="10">
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4">
							<label for="#"><small>Status </small></label><span class="float-right">:</span>
						</div>
						<div class="col-md-8">
							<select name="status" id="status" class="selectpicker form-control form-control-sm" required>
								<!-- <option selected hidden disabled>Choose Status</option> -->
								<option value="All" selected>All</option>
								<option value="Approved">Approved</option>
								<option value="Approved_With_Pay">Approved w/ Pay</option>
								<option value="Disapproved">Disapproved</option>
								<!-- <option value="Cancelled">Cancelled</option> -->
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
                    $('#distcode');
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
					console.log(res);
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

$('a#view_status').click(function(){
	var lv_id	=	$(this).attr('lv_id')

	$.ajax({
		method: 'GET',
		url: '/status/'+ lv_id,
		success: function(res){
			$.alert({
				columnClass: 'col-xs-12 col-sm-6 col-md-5',
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

$('a#dl_pdf').click(function(){

	var lv_id	=	$(this).attr('lv_id');
	var empno	=	$(this).attr('empno');

	$.confirm({
		columnClass: 'col-xs-12 col-sm-6 col-md-3',
		containerFluid: true, // this will add 'container-fluid' instead of 'container'
		animation: 'zoom',
		animateFromElement: false,
		draggable: false,
		type: 'dark',
		icon: 'fa fa-file',
		title: 'Download PDF',
		buttons:{
			close:{
				btnClass: 'btn-danger',
				action: function(){

				}
			},
			confirm:{
				text: 'OK',
				btnClass: 'btn-primary',
				action: function(){

					var url = 'http://tms.ideaserv.com.ph:8080/lv_form/'+lv_id+'/'+empno;
					// var url = 'http://192.168.0.64:8000/lv_form/'+lv_id+'/'+empno;
					myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
					myWindow.focus();
				}
			}
		}
	});
});

$('#dl_lvpdf').click(function(){

// var empno_name = document.getElementById('empno_name').value;
// var fromdate = document.getElementById('fromdate').value;
// var todate = document.getElementById('todate').value;
// var status = document.getElementById('status').value;

// console.log(empno_name, fromdate, todate, status);
var empno_name 	= 	$('#dl_lvpdf').attr('empno_name');
var fromdate 	= 	$('#dl_lvpdf').attr('fromdate');
var todate 		= 	$('#dl_lvpdf').attr('todate');
var entity01 	= 	$('#dl_lvpdf').attr('entity01');
var entity02 	= 	$('#dl_lvpdf').attr('entity02');
var entity03 	= 	$('#dl_lvpdf').attr('entity03');
var status 		= 	$('#dl_lvpdf').attr('status');

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
				
				var url = 'http://tms.ideaserv.com.ph:8080/lv_report?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&cocode='+entity01+'&distcode='+entity02+'&brchcode='+entity03+'&status='+status
				// var url = 'http://192.168.0.64:8000/lv_report?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&cocode='+entity01+'&distcode='+entity02+'&brchcode='+entity03+'&status='+status
				myWindow = window.open(url, '', 'width=800,height=700,scrollbars=1');
				myWindow.focus();

			}
		}
	}
})

});

$('#dl_lvcsv').click(function(){

// var empno_name = document.getElementById('empno_name').value;
// var fromdate = document.getElementById('fromdate').value;
// var todate = document.getElementById('todate').value;
// var status = document.getElementById('status').value;

// console.log(empno_name, fromdate, todate, status);
var empno_name 	= 	$('#dl_lvcsv').attr('empno_name');
var fromdate 	= 	$('#dl_lvcsv').attr('fromdate');
var todate 		= 	$('#dl_lvcsv').attr('todate');
var entity01 	= 	$('#dl_lvcsv').attr('entity01');
var entity02 	= 	$('#dl_lvcsv').attr('entity02');
var entity03 	= 	$('#dl_lvcsv').attr('entity03');
var status 		= 	$('#dl_lvcsv').attr('status');

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
				
				window.location.href = 'http://tms.ideaserv.com.ph:8080/lv_csv?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity02='+entity02+'&entity03='+entity03+'&status='+status
				// window.location.href = 'http://192.168.0.64:8000/lv_csv?empno_name='+empno_name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity02='+entity02+'&entity03='+entity03+'&status='+status
				// myWindow = window.open(url, '', 'width=800,height=700,scrollbars=1');
				// myWindow.focus();

			}
		}
	}
})

});
</script>
{{-- <script src="{{ asset('js/lv_summary.js') }}"></script> --}}
@endsection