$(document).ready(function() {
    
    $('#tbl_emp_lists thead').clone(true).appendTo('#tbl_emp_lists');
    $('#tbl_emp_lists thead tr:eq(1) th').each(function (i){
        var title = $(this).text();
        if(title != 'Action'){
            $(this).html('<input type="text" class="form-control form-control-sm text-uppercase" placeholder="" />');
        }

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    });

    var table = $('#tbl_emp_lists').DataTable( {
        // "scrollX": true,
        "ajax": "/employees_lists",
        // dom: 'Bfrtip',
        // buttons: [
        //     {
        //         extend: 'csv',
        //         className: 'btn btn-sm btn-light m-0'
        //     },
        //     'excel', 'pdf', 'print'
        // ],
        "columns": [
            { "data": "name" },
			{ "data": "empno" },
            { "data": "entity01_desc" },
            { "data": "entity02_desc" }, 
            { "data": "entity03_desc"},
            { "data": "is_active" },
            { 
                "width": "5%",
                "className": "text-center",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View</small></a>" 
			},
            // { "data": "lv_id", "visible": false, "searchable": false},
        ]
    } );
    
    $('#tbl_emp_lists tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();
        window.location.href = '/employees/'+data['empno']

        // $.alert('Status '+ data['empno']);

    });

});