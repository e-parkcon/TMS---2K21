$(document).ready(function() {
    var table = $('#tbl_ot_summ').DataTable({
        "ajax": "/ot_summ",
        deferRender:    true,
        "columns": [
            { "data": "date_filed", "width": "15%" },
            { "data": "name", "width": "25%"},
			{ "data": "refno" },
            { 
                "width": "5%",
                // "className": "text-center p-2",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View</small></a>" 
			},
        ]
    });
    

    $('#tbl_ot_summ tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location.href = '/overtime_summary/details/'+data['refno']+'/'+data['empno'];

        // $.alert('Status '+ data['empno']);

    });
});