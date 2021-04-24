$(document).ready(function() {
    var table = $('#tbl_pending_lv').DataTable( {
        // "scrollX": true,
        "ajax": "/lv/pending",
        "columns": [
            { "data": "name", "width": "20%" },
			{ "data": "lv_period", "width": "20%" },
            { "data": "lv_desc", "width": "15%" },
            { "data": "reason", "width": "30%" },
            { "data": "days", "width": "5%", "className": "text-center"}, 
			{ 
                "width": "5%",
                "className": "text-center",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View</small></a>" 
			},
            // {

            //     "className": "p-2",
            //     "width": "5%",
			// 	"data": "status",
			// 	render: function(data){

			// 		// if(data == 'Pending'){
			// 			return "<div class='dropdown'>"+
			// 						"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
			// 						"</button>"+
			// 						"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
			// 							"<a href='#' class='dropdown-item' id='view_app'><small>View to approve</small></a>"+
			// 							// "<a href='#' class='dropdown-item' id='lv_edit' data-toggle='modal' data-target='#lv_edit_modal'><small>Edit leave</small></a>"+
			// 							// "<a href='#' class='dropdown-item' id='chk_status'><small>Check status</small></a>"+
			// 							// "<a class='dropdown-item' href='#' id='lv_pdf'><small>Download as PDF</small></a>"+
			// 						"</div>"+
			// 					"</div>";
			// 		// }
			// 	}
			// },
			{ "data": "lv_id", "visible": false, "searchable": false},
        ]
    } );

    $('#tbl_pending_lv tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();

        console.log(data);

        // if($(this).attr('id') == 'view_app'){
			window.location.href = '/pending_lv/lv_details/'+data['lv_id']+'/'+data['empno'];
		// }

	});
	


	$( "#fromdate_app" ).datepicker({
		dateFormat: 'yy-mm-dd',
		// defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#todate_app" ).datepicker( "option", "minDate", selectedDate );
	}
	});

	$( "#todate_app" ).datepicker({
		dateFormat: 'yy-mm-dd',
		// defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		$( "#fromdate_app" ).datepicker( "option", "maxDate", selectedDate );
	}
	});


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

				$("#todate_app").change(function() {

					var d1 = $('#fromdate_app').datepicker('getDate');
					var d2 = $('#todate_app').datepicker('getDate');
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

				}); // end of todate_app


				$("#fromdate_app").change(function() {

					var d1 = $('#fromdate_app').datepicker('getDate');
					var d2 = $('#todate_app').datepicker('getDate');
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

				}); // end of todate_app

			} //end of success (holiday)
		}); //end of ajax holidays
	} //end of success (dayoff)
	}); // end of ajax dayoff


	$('#edit_lv_date').on('show.bs.modal', function(event){

		var button = $(event.relatedTarget) // Button that triggered the modal
		var app_fromdate	=	button.data('app_fromdate')
		var app_todate		=	button.data('app_todate')
		var no_days 		=	button.data('no_days')

		var modal 	=	$(this);
		modal.find('.modal-body #fromdate_app').val(app_fromdate);
		modal.find('.modal-body #todate_app').val(app_todate);
		modal.find('.modal-body #no_days').val(no_days);

	});


	$('#approve_lv').click(function(){
		var lv_id	=	$(this).attr('lv_id')
		var empno	=	$(this).attr('empno')

		console.log(lv_id, empno);

		$.alert({
			columnClass: 'col-xs-12 col-sm-6 col-md-4',
			containerFluid: true, // this will add 'container-fluid' instead of 'container'
			animation: 'top',
			animateFromElement: false,
			draggable: false,
			type: 'blue',
			icon: 'far fa-calendar-alt',
			title: 'Approve Leave',
			content:'<form>'+
						'<div class="col-md-12">'+
							'<div class="row g-1">'+
								'<div class="col-md-12">'+
									'<label class="form-label"><small>Are you sure you want to approve this leave?</small></label>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<label class="form-label"><small>Reason :</small></label>'+
									'<textarea class="form-control form-control-sm reason" style="resize: none;" cols="2" rows="2" ></textarea>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</form>',
			buttons:{
				formSubmit:{
                    text: 'Confirm',
                    btnClass: 'btn-primary',
                    action: function(){

						var reason     =   this.$content.find('.reason').val();

						$('#approve_lv').html('<small><i class="spinner-border spinner-border-sm text-warning mr-2 pr-2"></i> Submitting ..</small>');
						$('#approve_lv').prop('disabled', true);

						$.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
							data:{
								// lv_id:	lv_id,
								// empno:	empno,
								reason: reason
							},
							url: '/approve/user/leave/'+lv_id+'/'+empno,
							success: function(response){
								// console.log(response);

								toastr.success('Leave Approved.');

								window.location.href = '/pending_lv';
							}
						});

                    }
                },
				close:{
					btnClass: "btn-link",
					action: function(){

					}
				}
			},
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }

		});
	});

	$('#disapprove_lv').click(function(){
		var lv_id	=	$(this).attr('lv_id')
		var empno	=	$(this).attr('empno')

		console.log(lv_id, empno);

		$.alert({
			columnClass: 'col-xs-12 col-sm-6 col-md-4',
			containerFluid: true, // this will add 'container-fluid' instead of 'container'
			animation: 'top',
			animateFromElement: false,
			type: 'red',
			icon: 'fa fa-exclamation',
			title: 'Disapprove Leave',
			content:'<form>'+
						'<div class="col-md-12">'+
							'<div class="row g-1">'+
								'<div class="col-md-12">'+
									'<label class="form-label"><small>Are you sure you want to approve this leave?</small></label>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<label class="form-label"><small>Reason :</small></label>'+
									'<textarea class="form-control form-control-sm reason" style="resize: none;" cols="2" rows="2" ></textarea>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</form>',
			buttons:{
				formSubmit:{
                    text: 'Confirm',
                    btnClass: 'btn-primary',
                    action: function(){
                        
						var reason     =   this.$content.find('.reason').val();

						if(!reason){
							$.alert('Please provide a reason for disapproving.');
							return false;
						}

						$.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
							data:{
								// lv_id:	lv_id,
								// empno:	empno,
								reason: reason
							},
							url: '/disapprove/user/leave/'+lv_id+'/'+empno,
							success: function(response){
								// console.log(response);

								toastr.success('Leave Disapproved.');

								window.location.href = '/pending_lv';
							}
						});

                    }
                },
				close:{
					btnClass: "btn-link",
					action: function(){

					}
				}
			},
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }

		});
	});

});