$(document).ready(function() {
    $('#input').datetimepicker({
        datepicker: { showOtherMonths: true },
        modal: true,
        footer: true
    });

	$(document).on('change', '.upload-file', function() {

	var files = $(this)[0].files;
	var newLine;

	for (var i = 0; i < files.length; i++) {
		console.log(files[i].name);
		newLine = "<div class='col-md-12'>"+
						"<i class=' text-success fa fa-file'></i><small> "+files[i].name +"</small></div>"+
					"</div>";

		$("#to_be_appended .selected-files").append(newLine);
		
		// Clone the file selector, assign the name, hide and append it
		$(this).clone().hide().attr('name', 'upload[]').insertAfter($(this));
	}
	});


    var table = $('#tbl_ot').DataTable( {
        // "scrollX": true,
        "ajax": "/ot_header",
		"info": false,
        "columns": [
            { "data": "date_file", "width": "20%" },
			{ "data": "refno" },
			{ "data": "submitted", "width": "15%", "className": "text-center" },
            // { 
            //     "width": "10%",
            //     "targets": -1,
            //     "data": null,
            //     "defaultContent": "<a href='#' class='btn btn-sm btn-rounded btn-primary'><small>View Details</small></a>" 
			// },
			{
				"data": null,
				"width": "5%",
				render: function(data){
					if(data['submitted'] == 'N'){
						return "<div class='dropdown'>"+
									"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
									"</button>"+
									"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
										"<a href='#' class='dropdown-item' id='continue'><small>Continue filing</small></a>"+
										// "<a href='#' class='dropdown-item' id='view_list'><small>View overtime lists</small></a>"+
										"<a class='dropdown-item' href='#' id='del_all'><small>Delete overtime</small></a>"+
									"</div>"+
								"</div>";
					}

					if(data['submitted'] == 'Y'){
						return "<div class='dropdown'>"+
									"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
									"</button>"+
									"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
										"<a href='#' class='dropdown-item' id='view_list'><small>View OT lists</small></a>"+
									"</div>"+
								"</div>";
					}
				} 
			}
        ]
    } );

    $('#tbl_ot tbody').on( 'click', '.dropdown .dropdown-menu a', function () {
		var data = table.row( $(this).parents('tr') ).data();
		
		if($(this).attr('id') == 'continue'){
			window.location.href = '/overtime/add-new/'+data['refno'];
		}

		if($(this).attr('id') == 'view_list'){
			// $.alert('Redirect to OVERTIME LIST! '+ data['date_file']);
			window.location.href = '/overtime/lists/'+data['refno'];
		}

		if($(this).attr('id') == 'del_all'){
			$.confirm({
				columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'top',
				animateFromElement: false,
				draggable: false,
                type: 'red',
                icon: 'fa fa-trash',
				title: 'Delete Overtime',
				buttons:{
					confirm:{
						btnClass: 'btn-primary',
						action: function(){
							$.ajax({
								headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "DELETE",
								url: '/delete/all/ot/'+data['refno'],
								success: function(response){
									$.confirm({
										columnClass: 'col-xs-12 col-sm-6 col-md-4',
										containerFluid: true, // this will add 'container-fluid' instead of 'container'
										autoClose: 'Ok|1000',
										animation: 'top',
										animateFromElement: false,
										draggable: false,
										type: 'green',
										icon: 'fa fa-check-circle',
										title: 'Deleted',
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
							});
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

	} );
	

	// $('#tbl_ot tbody').on( 'click', 'tr', function () {
    //     $(this).toggleClass('selected');
	// } );
	
	// $('#button').click( function () {
    //     $.alert( table.rows('.selected').data().length +' row(s) selected' );
    // } );

	var today = new Date()
	var yesterday = new Date(today)
	yesterday.setDate(yesterday.getDate() - 1)

	//Date Of OT
	$( "#dateot" ).datepicker({
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		useCurrent: false,
		minDate: '-30d',
		maxDate: yesterday,
	});

    $('#dateot').change(function(){
		var selectedDate	=	$(this).val();
		console.log(selectedDate);

		$.ajax({
			type: 'GET',
			data: {
				dateot: selectedDate
			},
			url: '/shift_today',
			success: function(response){
				console.log(response);
				var nextday_out	=	new Date(selectedDate);
				var txndate	=	nextday_out.getFullYear()+'-'+(nextday_out.getMonth()+1)+'-'+nextday_out.getDate();

				if(response.nextday_out == 'Y'){
					// nextday_out.setDate(nextday_out.getDate() +1)
					txndate	=	nextday_out.getFullYear()+'-'+(nextday_out.getMonth()+1)+'-'+(nextday_out.getDate()+1);
				}

				$('#shift_desc').val(response.desc);
				$('#shift_code').val(response.shift);
				
				if(response.shift == 'X'){
					$('#dt6').datetimepicker('date', selectedDate);
					$('#dt7').datetimepicker('date', selectedDate +' '+ response.out);
				}
				else{
					// $('#dt6').datetimepicker('date', txndate+' '+response.out);
					$('#dt6').datetimepicker('date', txndate);
					// $('#dt6').datetimepicker('minDate', txndate);
					// $('#dt6').datetimepicker('minDate', txndate+' '+response.out);
					// $('#dt7').datetimepicker('date', txndate+' '+response.out);
					$('#dt7').datetimepicker('date', txndate);
					$('#dt7').datetimepicker('minDate', txndate);
				}
				// $('#dt6').datetimepicker('minDate', selectedDate+' '+response.out);
				// $('#dt6').datetimepicker('minDate', txndate);
			}
		});
	});


    //timestart
	$('#dt6').datetimepicker({
		format: 'YYYY-MM-DD HH:mm',
		useCurrent: false,
		autoclose: true,
		maxDate: new Date()
    });

	//timefinish
	$('#dt7').datetimepicker({
		format: 'YYYY-MM-DD HH:mm',
		useCurrent: false,
		autoclose: true,
		maxDate: new Date()
    });
    
});

$(document).mousemove(function() {
    function calculateTime() {

            var datestart = $("input[id='dt6']").val();
            var datefinish = $("input[id='dt7']").val();
			var splitstart = datestart .split(' ');
			var splitfinish = datefinish .split(' ');
			var date1 = new Date(splitstart[0]);
			var date2 = new Date(splitfinish[0]);
            var valuestart = splitstart[1];
            var valuestop = splitfinish[1];

            // date
   			// var date1 = new Date(datestart);
			// var date2 = new Date(datefinish);
			var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            var prod = (diffDays) * 24;

			// time
			var timeStart = new Date("01/01/2017 " + valuestart).getHours();
			var timeEnd = new Date("01/01/2017 " + valuestop).getHours();

			var hourDiff = timeEnd - timeStart;

			var timeStartm = new Date("01/01/2007 " + valuestart);
			var timeEndm = new Date("01/01/2007 " + valuestop);

			var minute1 = timeStartm.getMinutes();
			var minute2 = timeEndm.getMinutes();

			var minuteDiff = (minute2 - minute1)/60;
			// console.log(minuteDiff);
			var sum = hourDiff + minuteDiff;

			// var date1_1 = new Date($("input[id='dt6']").val());
			// var date2_2 = new Date($("input[id='dt7']").val());
			// console.log(date1_1, date2_2);

			// var msec 	= 	date2_2 - date1_1;
			// var mins 	= 	Math.floor(msec / 60000);
			// var hrs 	= 	Math.floor(mins / 60);
			// var days	= 	Math.floor(hrs / 24);
			// var yrs		= 	Math.floor(days / 365);

			// mins = mins % 60;
			// hrs = hrs % 24;
			// console.log(hrs+' '+mins);

			// var ot_hrs	=	0;
			// if(hrs > 8){
			// 	// var fsum = prod + sum - 1;
			// 	// if (isNaN(fsum)) fsum = 0;
			// 	ot_hrs 	=	hrs - 1;
			// 	$("#hours").val(ot_hrs+'.'+mins)
			// }
			// else{
			// 	// var fsum = prod + sum;
			// 	// if (isNaN(fsum)) fsum = 0;
			// 	$("#hours").val(hrs+'.'+mins)	
			// }
			
			if(sum > 8){
				var fsum = prod + sum - 1;
				if (isNaN(fsum)) fsum = 0;
				$("#hours").val(fsum)
			}
			else{
				var fsum = prod + sum;
				if (isNaN(fsum)) fsum = 0;
				$("#hours").val(fsum)	
			}

    }
    $("input").change(calculateTime);
    	calculateTime();
});