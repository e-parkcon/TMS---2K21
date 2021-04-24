$(document).ready(function() {

    var table = $('#tbl_pending_ot').DataTable( {
        // "scrollX": true,
        "ajax": "/ot/pending",
        "columns": [
            { "data": "name", "width": "20%"},
			{ "data": "refno" },
			{ "data": "date_filed", "width": "15%" },
            { 
                "width": "5%",
                "className": "text-center",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View</small></a>" 
			},
        ]
    } );


    $('#tbl_pending_ot tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();

        window.location.href = '/pending_ot/ot_lists/'+data['refno']+'/'+data['empno'];

    });

});