$(document).ready(function() {
    var table = $('#tbl_lv_summ').DataTable({
        "ajax": "/lv_summ",
        deferRender:    true,
        scrollCollapse: true,
        scroller:       true,
        stateSave:      true,
        "columns": [
            { "data": "lv_period", "width": "20%" },
            { "data": "name", "width": "20%" },
            { "data": "lv_desc", "width": "15%" },
            { "data": "reason", "width": "30%" },
            { "data": "days", "width": "5%", "className": "text-center"}, 
            // { "data": "status" },
            {
                "width": "5%",
                // "className": "text-center p-2",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View</small></a>" 
                // "className": "p-2",
                // "width": "5%",
				// "data": "status",
				// render: function(data){
                //     return "<div class='dropdown'>"+
                //                 "<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                //                 "</button>"+
                //                 "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
                //                     "<a href='#' class='dropdown-item' id='view_status'><small>View to status</small></a>"+
                //                 "</div>"+
                //             "</div>";
				// }
			},
			{ "data": "empno", "visible": false}
        ]
    });
    

    $('#tbl_lv_summ tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();
        // window.location.href = '/pending_ot/ot_lists/'+data['refno']+'/'+data['empno'];

        // $.alert('Status '+ data['empno']);
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

    });
});