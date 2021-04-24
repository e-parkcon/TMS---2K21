$(document).ready(function(){
    var table = $('#tbl_district_list').DataTable({
        "ajax": '/utilities/district/list',
        "columns": [
            { "data": "entity01_desc" },
            { "data": "entity02_desc" },
            { 
                "width": "5%",
                "className": "text-center",
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#' class='btn btn-sm btn-primary'><small>View Branches</small></a>" 
			}
        ]
    });

    $('#tbl_district_list tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();
		
        // console.log(data['entity01'], data['entity02']);

        $.confirm({
            columnClass: 'col-xs-12 col-sm-6 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'dark',
            icon: 'fa fa-building',
            title: '<small>'+ data['entity01_desc'] +' '+ data['entity02_desc'] +'</small>',
            content: function(){
                    var self = this;
                    return $.ajax({
                        url:'/entity03?entity01='+ data['entity01'] +'&'+'entity02='+data['entity02'],
                        dataType: 'json',
                        method: 'GET'
                    }).done(function(response){
                        console.log(response);
                        self.setContentAppend('<div class="text-left"><p class="text-sm">Lists of Branches</p></div>');
                        self.setContentAppend(
                            '<table class="table table-sm table-bordered mb-0" style="width: 100%;">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th style="text-align:center;" width=30%>Code</th>'+
                                        '<th style="text-align:center;" width=70%>Branch Name</th>'+
                                    '</tr>'+
                                '</thead>'+
                            '</table>');
                        $.each(response,function(key,value){
                            self.setContentAppend(
                                    '<table class="table table-sm table-bordered m-0" style="width: 100%;">'+
                                        '<tr>'+
                                            '<td style="text-align:center;" width=30%>'+key+'</td>'+
                                            '<td style="text-align:left;" width=70%>'+value+'</td>'+
                                        '</tr>'+
                                    '</table>');
                        });
                    }).fail(function(){
                        
                    });
                },
            buttons: {
                close: {
                    btnClass: 'btn-danger',
                    action: function(){
                    }
                }
            }
        });

    });
});