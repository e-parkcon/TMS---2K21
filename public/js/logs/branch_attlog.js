$(document).ready(function() {

    $( "#fromdate" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
    });

    $( "#todate" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
    });

    $('#search_branch_attlog').click(function(){

        var fromdate    =   $('#fromdate').val();
        var todate      =   $('#todate').val();
        var entity01    =   $('#company').val();
        var entity03    =   $('#branch').val();

        console.log(fromdate, todate, entity01, entity03);
        if(!fromdate || !todate){
            $.alert('Please select date.');
            return false;
        }

		if (entity01 == "%") {
			var comp1 = entity01+"25";
		} else {
			var comp1 = entity01;
		}

        $('#tbl_brch_attlog').DataTable().clear().destroy();

        var table = $('#tbl_brch_attlog').DataTable( {
            "ajax": "/json/branch_attlog?"+'fromdate='+fromdate+'&todate='+todate+'&company='+comp1+'&branch='+entity03,
            "columns": [
                { "data": "entity03_desc"},
                { "data": "notxn"},
                { 
                    "className": "text-center",
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<a href='#' id='brch_log' class='btn btn-sm btn-primary'><small><span class='fa fa-history'></span></small></a>" 
                },
            ]
        } );


        $('#tbl_brch_attlog tbody').off('click').on( 'click', 'a', function () {
            var data = table.row( $(this).parents('tr') ).data();

            console.log(data);

            // $('.view_data').click(function(){
        
                $.confirm({
                    animation: 'none',
                    draggable: false,
                    boxWidth: '80%',
                    useBootstrap: false,
                    title: '<div class="col-sm-12 row"><b class="text-uppercase">'+data['entity03_desc']+'</b><br><br>'+
                    // '&nbsp; <label style="font-size: 14px;">('+entity01_desc+')</label> </b>'+'<br>'+ 
                            '<table>'+
                                '<tr>'+
                                    '<td width=5px><div class="squareBlue"></div></td>'+
                                    '<td width=60px><span>Day Off</span>&nbsp;</td>'+
        
                                    '<td width=5px><div class="squareRed"></div></td>'+
                                    '<td width=60px><span>Absent</span>&nbsp;</td>'+
        
                                    '<td width=5px><div class="squareGreen"></div></td>'+
                                    '<td width=60px><span>IN</span>&nbsp;</td>'+
        
                                    '<td width=5px><div class="squareYellow"></div></td>'+
                                    '<td width=60px><span>OUT</span>&nbsp;</td>'+
                                '</tr>'+
                            '</table>'+
                            '</div>'+
                    '',
                    content: function(){
                        var self = this;
                        return $.ajax({
                            url: '/brch_log/'+fromdate+'/'+todate+'/'+comp1+'/'+data['entity03_desc'],
                            dataType: 'json',
                            method: 'get'
                        }).done(function(response){
                            console.log(response)
                        self.setContentAppend(
                            '<table class="table table-sm">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th width=1%><div class="squareWhite"></div></th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=10%>EmpNo.</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=20%>Name</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=10%>Company</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=10%>Date</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=10%>Time</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=15%>Unit#</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=6%>Seq#</th>'+
                                        '<th style="font-size: 12px; text-align:center;" width=10%>Status</th>'+
                                    '</tr>'+
                                '</thead>'+
                            '</table>');

                            for(i=0; i < response.length; i++){
                                self.setContentAppend(
                                    '<table class="table table-hover table-sm m-0">'+
                                        '<tr>'+
                                            '<td width=1%><div class="'+response[i].color+'"></div></td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=10%>'+response[i].empno+'</td>'+
                                            '<td style="font-size: 11px; text-align:left;"   width=20%>'+response[i].empname+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=10%>'+response[i].entity01_desc+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=10%>'+response[i].txndate+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=10%>'+response[i].time_in+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=15%>'+response[i].unit+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=6%>'+response[i].seqno+'</td>'+
                                            '<td style="font-size: 11px; text-align:center;" width=10%>'+response[i].status+'</td>'+
                                        '</tr>'+
                                    '</table>');
                            }
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
            // });

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
            })
    });

});