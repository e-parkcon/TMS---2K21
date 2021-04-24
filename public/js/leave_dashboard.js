$(document).ready(function() {
    var table = $('#tbl_leave').DataTable( {
        // "scrollX": true,
        "ajax": "/lv_list",
		"info": false,
        "columns": [
			{ "data": "lv_period" },
            { "data": "lv_desc" },
            // { "data": "reason" },
			{
				"data": function(data, type, dataToset){
					return data['reason'] + ' ' + (data['pdf_file_leave'] != '' ? "<a href='"+data['pdf_file_leave']+"' target='_blank' class='fa fa-file text-success float-right'></a>" : '' );
				}
			},
            { "data": "days", "className": "text-center"}, 
            { "data": "status" },
            {
				// "className": "p-2",
				"data": "status",
				render: function(data){

					if(data == 'Pending'){
						return "<div class='dropdown dropleft'>"+
									"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
									"</button>"+
									"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
										"<a href='#' class='dropdown-item' id='lv_cancel'><small>Cancel leave</small></a>"+
										"<a href='#' class='dropdown-item' id='lv_edit' data-toggle='modal' data-target='#lv_edit_modal'><small>Edit leave</small></a>"+
										"<a href='#' class='dropdown-item' id='chk_status'><small>Check status</small></a>"+
										"<a class='dropdown-item' href='#' id='lv_pdf'><small>Download as PDF</small></a>"+
									"</div>"+
								"</div>";
					}

					if(data == 'Approved' || data == 'Disapproved'){
						return "<div class='dropdown dropleft'>"+
									"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
									"</button>"+
									"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
										"<a class='dropdown-item' href='#' id='chk_status'><small>Check Status</small></a>"+
										"<a class='dropdown-item' href='#' id='lv_pdf'><small>Download as PDF</small></a>"+
									"</div>"+
								"</div>";
					}

					if(data == 'Cancelled'){
						return "";
					}
				}
			},
			{ "data": "lv_id", "visible": false, "searchable": false},
        ]
    } );


    $('#tbl_leave tbody').on( 'click', '.dropdown .dropdown-menu a', function () {
        var data = table.row( $(this).parents('tr') ).data();
		console.log($(this).attr('id'));

		if($(this).attr('id') == 'chk_status'){
			// $.alert('status');
			$.ajax({
				method: 'GET',
				url: '/status/'+ data['lv_id'],
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
		}

		if($(this).attr('id') == 'lv_cancel'){
			$.alert({
				columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'top',
				animateFromElement: false,
				type: 'orange',
				icon: 'fa fa-calendar',
				title: 'Cancel Leave',
				content: 'Are you sure you want to cancel your leave?',
				buttons:{
					confirm:{
						btnClass: 'btn-primary btn-sm',
						action: function(){
							// window.location.href =  '/cancel/leave/'+lv_id;
							
							$.ajax({
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								},
								type: "POST",
								data:{
									lv_id	:	data['lv_id']
								},
								url: '/cancel/leave',
								success: function(response){
									$.confirm({
										columnClass: 'col-xs-12 col-sm-6 col-md-4',
										containerFluid: true, // this will add 'container-fluid' instead of 'container'
										autoClose: 'Ok|1000',
										animation: 'top',
										animateFromElement: false,
										draggable: false,
										type: 'green',
										icon: 'fa fa-calendar',
										title: 'Leave Cancelled',
										content: response,
										buttons:{
											Ok: {
												btnClass: 'btn-primary',
												action: function(){
													// location.reload();
													table.ajax.reload();
												}
											}
										}
									});
								},
                                error: function(xhr, status, error){
                                    var errorMessage = xhr.status + ': ' + xhr.statusText
                                    console.log(status, xhr, error);
                                    $.alert('Error - ' + errorMessage);
                                }
							})
						}
					},
					cancel:{
						btnClass: 'btn-link',
						action: function(){
		
						}
					}
				}
			});
		}

		if($(this).attr('id') == 'lv_edit'){
			// EDIT LEAVE
			$('#lv_edit_modal').on('show.bs.modal', function (event) {
				console.log('Modal Open sesame');

				var modal = $(this)
				// modal.find('.modal-title #empno').text('New message to ' + empno)
				modal.find('.modal-body #lv_id').val(data['lv_id'])
				modal.find('.modal-body #edt_fromdate').val(data['fromdate'])
				modal.find('.modal-body #edt_todate').val(data['todate'])
				modal.find('.modal-body #edt_no_days').val(data['days'])
				modal.find('.modal-body #edt_lv_type').val(data['lv_code'])
				modal.find('.modal-body #edt_reason').val(data['reason'])

			}); 
		}

		if($(this).attr('id') == 'lv_pdf'){
			// $.alert({
			// 	columnClass: 'col-xs-12 col-sm-6 col-md-5',
			// 	containerFluid: true, // this will add 'container-fluid' instead of 'container'
			// 	animation: 'zoom',
			// 	animateFromElement: false,
			// 	content: 'PDF <small>'+data['lv_period']+'</small>'
			// });

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
		
							var url = 'http://tms.ideaserv.com.ph:8080/lv_form/'+data['lv_id']+'/'+data['empno'];
							// var url = 'http://192.168.0.64:8000/lv_form/'+data['lv_id']+'/'+data['empno'];
							myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
							myWindow.focus();
						}
					}
				}
			});
		}

	} );


	$('#lv_form').submit(function(){
		$('#leave_submit').html('<small><i class="spinner-border spinner-border-sm text-warning mr-2 pr-2"></i> Submitting ..</small>');
		$('#leave_submit').prop('disabled', true);
	});


	$( "#fromdate" ).datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#todate" ).datepicker( "option", "minDate", selectedDate );
		}
	});

	$( "#todate" ).datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#fromdate" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

	$( "#edt_fromdate" ).datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#edt_todate" ).datepicker( "option", "minDate", selectedDate );
		}
	});

	$( "#edt_todate" ).datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#edt_fromdate" ).datepicker( "option", "maxDate", selectedDate );
		}
	});


    $(function() {
        $.ajax({
        	type: "GET",
        	url: '/dayoff',
        	success: function(res1){
			// console.log(res1);
	    	$.ajax({
	    		type: "GET",
	    		url: '/holidays',
	    		success: function(res){

	    			var MS_PER_DAY = 24 * 3600 * 1000;
	    			var array = [];
	    			var hol_off = res.concat(res1);
	    			// console.log(hol_off);

	    			for (i = 0; i < hol_off.length; i++) {
	    				var off = new Date(hol_off[i]);
	    				var dayoff = new Date(off.getFullYear(), off.getMonth(), off.getDate()).getTime();
	    				array.push(dayoff);
	    				// console.log(hol_off.length);
	    			};


			        // console.log(HOLIDAYS);
			        $.datepicker.setDefaults({beforeShowDay: function(date) { // disabled specific dates
			            // var day = date.getDay();

			            // if ($.inArray(date.getTime(), array) > -1) {
			            //     return [true, 'holiday'];
			            // }
			            // // return $.datepicker.noWeekends(date); // disabled weekends
			            // return [true,(day > 0), ''];
			            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        				return [ hol_off.indexOf(string) == -1 ]
			        }
			    	}); // end of disabled specific dates

			        $("#todate").change(function() {

			            var d1 = $('#fromdate').datepicker('getDate');
			            var d2 = $('#todate').datepicker('getDate');
			            var diff = Math.floor((d2 - d1) / MS_PER_DAY + 1);
			            var work = diff;
			            var d = d1;
			            while (d <= d2) {
			                if ((d.getDay() || 7) > 7) { // Sat/Sun
			                    work--;
			                }
			                else if ($.inArray(d.getTime(), array) > -1) {
			                    work--;
			                }
			                d.setDate(d.getDate() + 1);
			            }
			             if (isNaN(work)) work = 0;
			             $("#no_days").val(work)

			        }); // end of todate


			        $("#fromdate").change(function() {

			            var d1 = $('#fromdate').datepicker('getDate');
			            var d2 = $('#todate').datepicker('getDate');
			            var diff = Math.floor((d2 - d1) / MS_PER_DAY + 1);
			            var work = diff;
			            var d = d1;
			            while (d <= d2) {
			                if ((d.getDay() || 7) > 7) { // Sat/Sun
			                    work--;
			                }
			                else if ($.inArray(d.getTime(), array) > +1) {
			                    work--;
			                }
			                d.setDate(d.getDate() + 1);
			            }
			             if (isNaN(work)) work = 0;
			             $("#no_days").val(work)

			        }); // end of todate

			        $("#edt_todate").change(function() {

			            var d1 = $('#edt_fromdate').datepicker('getDate');
			            var d2 = $('#edt_todate').datepicker('getDate');
			            var diff = Math.floor((d2 - d1) / MS_PER_DAY + 1);
			            var work = diff;
			            var d = d1;
			            while (d <= d2) {
			                if ((d.getDay() || 7) > 7) { // Sat/Sun
			                    work--;
			                }
			                else if ($.inArray(d.getTime(), array) > -1) {
			                    work--;
			                }
			                d.setDate(d.getDate() + 1);
			            }
			             if (isNaN(work)) work = 0;
			             $("#edt_no_days").val(work)

			        }); //end of #edt_todate

			        $("#edt_fromdate").change(function() {

			            var d1 = $('#edt_fromdate').datepicker('getDate');
			            var d2 = $('#edt_todate').datepicker('getDate');
			            var diff = Math.floor((d2 - d1) / MS_PER_DAY + 1);
			            var work = diff;
			            var d = d1;
			            while (d <= d2) {
			                if ((d.getDay() || 7) > 7) { // Sat/Sun
			                    work--;
			                }
			                else if ($.inArray(d.getTime(), array) > +1) {
			                    work--;
			                }
			                d.setDate(d.getDate() + 1);
			            }
			             if (isNaN(work)) work = 0;
			             $("#edt_no_days").val(work)

			        }); //end of #edt_todate

	    		} //end of success (holiday)
	    	}); //end of ajax holidays
		} //end of success (dayoff)
		}); // end of ajax dayoff

    });


	$('#leavecode').change(function() {
		var lv_desc = $('#leavecode').find(':selected').data('lv_desc');
		var lv_code = $('#leavecode').val();
		console.log(lv_desc, lv_code);
        
        
		if($('#no_days').val() >= 3 && lv_code == "SL") {

			$('#pdf').prop('disabled', true);
			// $('#lv_modal').dispose();
			$.alert({
				columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'zoom',
				animateFromElement: false, 
				draggable: false,
				type: 'red',
				icon: 'fa fa-exclamation-circle',
				title: 'Alert!',
				content: 'Filing 3 days of sick leave requires medical certificate to present to HR Department. <br>'+
						'<label class="font-weight-bold text-danger text-sm"><i>Warning: Application cannot be accepted by the system.</i></label>',
				buttons:{
					confirm:{
						text: 'Ok',
						btnClass: 'btn-primary',
						action: function(){
							
						}
					}
				}
			});

		}
		else{
			$('#pdf').prop('disabled', true);

			if($("#day_lapse").val() >= -2 && lv_code == "VL"){
				// $('#lv_modal').dispose();
				$.alert({
					columnClass: 'col-xs-12 col-sm-6 col-md-4',
					containerFluid: true, // this will add 'container-fluid' instead of 'container'
					animation: 'zoom',
					animateFromElement: false, 
					draggable: false,
					type: 'red',
					icon: 'fa fa-exclamation-circle',
					title: 'Alert!',
					content: 'Filing of '+ lv_desc +' at least three days before your actual leave date.',
					buttons:{
						confirm:{
							text: 'Ok',
							btnClass: 'btn-primary',
							action: function(){
								
							}
						}
					}
				});
			}
		}

		if(lv_code == 'PL' && lv_code == 'ML'){
			$('#pdf').prop('disabled', false);
		}

	});

} );


// $("#fromdate").change(function() {
// 	function calculateDay() {
// 		//get values
// 		var datestart_lapse = $("input[id='fromdate']").val();
		
// 		// date
// 		var date1_lapse = new Date(datestart_lapse);
// 		var date2_lapse = new Date();
// 		var timeDiff_lapse = Math.abs(date1_lapse.getDate() - date2_lapse.getDate());
// 		// var diffDays_lapse = Math.ceil(timeDiff_lapse / (1000 * 3600 * 24));
// 		var diffDays_lapse = Math.ceil(timeDiff_lapse);
// 		if(date2_lapse > date1_lapse){
// 			var total_lapse = diffDays_lapse;2
// 		}
// 		else{
// 			var total_lapse = -diffDays_lapse;
// 		}
		
// 		if (isNaN(total_lapse)) total_lapse = 0;
// 		$("#day_lapse").val(total_lapse)
// 	}

// 	$("#fromdate").change(calculateDay);
// 	calculateDay();
// });

// $("#edt_fromdate").change(function() {
// 	function calculateDay1() {
// 		//get values
// 		var datestart_lapse = $("input[id='edt_fromdate']").val();
		
// 		// date
// 		var date1_lapse = new Date(datestart_lapse);
// 		var date2_lapse = new Date();
// 		var timeDiff_lapse = Math.abs(date1_lapse.getDate() - date2_lapse.getDate());
// 		// var diffDays_lapse = Math.ceil(timeDiff_lapse / (1000 * 3600 * 24));
// 		var diffDays_lapse = Math.ceil(timeDiff_lapse);
// 		if(date2_lapse > date1_lapse){
// 			var total_lapse = diffDays_lapse;2
// 		}
// 		else{
// 			var total_lapse = -diffDays_lapse;
// 		}
		
// 		if (isNaN(total_lapse)) total_lapse = 0;
// 		$("#edt_day_lapse").val(total_lapse)
// 	}

// 	$("#edt_fromdate").change(calculateDay1);
// 	calculateDay1();
// });