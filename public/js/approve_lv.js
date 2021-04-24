$(document).ready(function() {

    var table = $('#tbl_app_lv').DataTable( {
        // "scrollX": true,
        "ajax": "/approve_lv",
        "columns": [
            { "data": "name", "width": "15%" },
			{ "data": "lv_period", "width": "25%" },
            { "data": "lv_desc", "width": "10%" },
            { "data": "reason" },
            { "data": "days", "className": "text-center", "width": "5%"}, 
            {
				// "className": "p-2",
				"width": "5%",
				"data": "status",
				render: function(data){

					// if(data == 'Pending'){
					// 	return "<div class='dropdown dropleft'>"+
					// 				"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
					// 				"</button>"+
					// 				"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
					// 					"<a href='#' class='dropdown-item' id='lv_cancel'><small>Cancel leave</small></a>"+
					// 					"<a href='#' class='dropdown-item' id='lv_edit' data-toggle='modal' data-target='#lv_edit_modal'><small>Edit leave</small></a>"+
					// 					"<a href='#' class='dropdown-item' id='chk_status'><small>Check status</small></a>"+
					// 					"<a class='dropdown-item' href='#' id='lv_pdf'><small>Download as PDF</small></a>"+
					// 				"</div>"+
					// 			"</div>";
					// }

					if(data == 'Approved' || data == 'Disapproved'){
						return "<div class='dropdown dropleft'>"+
									"<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
									"</button>"+
									"<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
										"<a class='dropdown-item' href='#' id='view_details'><small>View Details</small></a>"+	
										// "<a class='dropdown-item' href='#' id='chk_status'><small>Check Status</small></a>"+
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

    $('#tbl_app_lv tbody').on( 'click', '.dropdown .dropdown-menu a', function () {
        var data = table.row( $(this).parents('tr') ).data();
		console.log($(this).attr('id'));

		if($(this).attr('id') == 'view_details'){
			window.location.href	=	'/approve_leave/details/'+data['lv_id']+'/'+data['empno'];
		}

		if($(this).attr('id') == 'chk_status'){
			// $.alert('CHECK STATUS');
			$.ajax({
				method: 'GET',
				url: '/status/'+ data['lv_id'],
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
		}

	} );

});