$(document).ready(function(){
    var table   =   $('#tbl_department_list').DataTable({
        "ajax": '/utilities/department/list',
        "columns": [
            { "data": "entity01_desc" },
            { "data": "deptdesc" }
        ]
    });
});