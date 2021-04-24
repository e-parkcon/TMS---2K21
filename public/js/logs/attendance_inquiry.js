$(document).ready(function() {

    $( "#txndate" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        
    });

    $('#search_attInquiry').click(function(){

        var txndate1 = $('#txndate').val();
        var entity01 = $('#company').val();
        var entity02 = $('#district').val();
        var entity03 = $('#branch').val();
        var deptcode = $('#department').val();
        var select1 =   $('input[name="select1"]:checked').val();


        console.log(txndate1, entity01, entity02, entity03, deptcode, select1);
        if(!txndate){
            $.alert('Please select date.');
            return false;
        }

        $('#tbl_att_inquiry').DataTable().destroy();
        
        var table = $('#tbl_att_inquiry').DataTable( {
            // "scrollX": true,
            "ajax": "/json/attendance_inquiry?"+'txndate='+txndate1+'&company='+entity01+'&district='+entity02+'&branch='+entity03+'&department='+deptcode+'&select1='+select1,
            "columns": [
                { "data": "empname"},
                { "data": "entity01_desc"},
                { "data": "entity03_desc"},
                { "data": "in"},
                {
                    "data": null,
                    render: function(data){
                        if(data['absent'] == 'Absent' || data['absent'] == 'On-Leave'){
                            return "";
                        }

                        if(data['absent'] == 'Present'){
                            return "<a href='#' id='inout_log' class='btn btn-sm btn-primary'><small><span class='fa fa-history'></span></small></a>";
                        }
                    }
                },
                // { 
                //     "className": "text-center",
                //     "targets": -1,
                //     "data": null,
                //     "defaultContent": "<a href='#' id='inout_log' class='btn btn-sm btn-primary'><small><span class='fa fa-history'></span></small></a>" 
                // },
                { "data": "empno", "visible": false},
                { "data": "txndate", "visible": false}
            ]
        } );

        $('#tbl_att_inquiry tbody').off('click').on( 'click', 'a', function () {
            table.ajax.reload();
            var data = table.row( $(this).parents('tr') ).data();
            console.log(data);

            var txndate =   data['txndate'];
            var empno   =   data['empno'];

            $.confirm({
                columnClass: 'col-xs-2 col-sm-5 col-md-3',
                containerFluid: true, // this will add 'container-fluid' instead of 'container'
                animation: 'zoom',
                animateFromElement: false,
                draggable: false,
                type: 'blue',
                icon: 'fa fa-calendar',
                title: 'Logs',
                content: function(){
                    var self = this;
                    return $.ajax({
                        url: '/inout_logs/'+txndate+'/'+empno,
                        dataType: 'json',
                        method: 'GET'
                    }).done(function(response){
                        // self.setContentAppend('<div class="text-center"><p class="text-sm">'+data['txndate']+'</p></div>');
                        for(i=0; i < response.length; i++){
                            self.setContentAppend('<div class="text-center p-0"><small class="p-0">'+response[i].txntime+'</small></div>');
                        }
                    }).fail(function(){
                        
                    });
                },
                buttons: {
                    close:{
                        btnClass: 'btn-danger',
                        action: function(){

                        }
                    },
                }
            });

        });

    });
    

    $('#company').change(function(){
    		
        var company 	= $("#company").val();
        
        return $.ajax({
                method: 'get',
                url: "/comp_inq/" + company,
                dataType: 'json',
                
                success:function(res){

                    if(res.length == 0){
                        $("#district").empty();
                        $("#district").append('<option value="No district">No District</option>');
                        $('#district').selectpicker('refresh');

                    }else{
                           $("#district").empty();
                        $("#district").append('<option value="%">ALL</option>');
                        $.each(res,function(key,value){
                            $("#district").append('<option value="'+key+'">'+value+'</option>');
                            $('#district').selectpicker('refresh');
                        });
                    }
                   },
            }),

            $.ajax({
                method: 'get',
                url: "/brchlog_inq/" + company,
                dataType: 'json',
                
                success:function(res){
                    if( res.length == 0){
                        $("#branch").empty();
                        $("#branch").append('<option value="No branches">No branches</option>');
                        $('#branch').selectpicker('refresh');
                        
                    }else{
                       $("#branch").empty();
                       $("#branch").append('<option value="%">ALL</option>');
                        $.each(res,function(key,value){
                            $("#branch").append('<option value="'+key+'">'+value+'</option>');
                            $('#branch').selectpicker('refresh');
                        });
                    }
                   },
            }),

            $.ajax({
                method: 'get',
                url: "/dept_inq/" + company,
                dataType: 'json',
                success:function(res){
                    if(res.length == 0){
                        $("#department").empty();
                        $("#department").append('<option value="0">No department</option>');
                        $('#department').selectpicker('refresh');

                    }else{
                           $("#department").empty();
                        $("#department").append('<option value="%">ALL</option>');
                        $.each(res,function(key,value){
                            $("#department").append('<option value="'+key+'">'+value+'</option>');
                            $('#department').selectpicker('refresh');
                        });
                    }
                   },
            });
    });

    $('#district').change(function(){
        
        var company 	= $("#company").val();
        var district 	= $("#district").val();
        
        return $.ajax({
                method: 'get',
                url: "/dist_inq/" + company + "/" + district,
                dataType: 'json',

                success:function(res){
                    if( res.length == 0){
                        $("#branch").empty();
                        $("#branch").append('<option value="No branches">No branches</option>');
                        $('#branch').selectpicker('refresh');
                        
                    }else{
                       $("#branch").empty();
                       $("#branch").append('<option value="%">ALL</option>');
                        $.each(res,function(key,value){
                            $("#branch").append('<option value="'+key+'">'+value+'</option>');
                            $('#branch').selectpicker('refresh');
                        });
                    }
                   },
            });
    });

});