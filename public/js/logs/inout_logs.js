
$('a#inout_logs').click(function(){

    var txndate =   $(this).attr('txndate');
    var empno   =   $(this).attr('empno');
    var formatted_date  =   $(this).attr('formatted_date');

    console.log(txndate, empno);
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
                self.setContentAppend('<div class="text-center"><p class="text-sm">'+formatted_date+'</p></div>');
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

$('a#dl_logs').click(function(){

var empno	=	$(this).attr('empno');
var name	=	$(this).attr('name');
var fromdate	=	$(this).attr('fromdate');
var todate		=	$(this).attr('todate');
var entity01	=	$(this).attr('entity01');
var entity03	=	$(this).attr('entity03');

console.log(empno, name, fromdate, todate, entity01, entity03);
$.confirm({
    columnClass: 'col-xs-2 col-sm-5 col-md-4',
    containerFluid: true, // this will add 'container-fluid' instead of 'container'
    animation: 'zoom',
    animateFromElement: false,
    draggable: false,
    type: 'dark',
    icon: 'fa fa-file',
    title: 'Download Logs',
    buttons:{
        close:{
            btnClass: 'btn-danger',
            action: function(){

            }
        },
        confirm:{
            btnClass: 'btn-primary',
            action: function(){
                // if(fromdate == ''){
                // 	$.alert({
                // 		animation: 'zoom',
                // 		animateFromElement: false,
                // 		type: 'red',
                // 		icon: 'fa fa-exclamation-triangle',
                // 		title: 'Alert',
                // 		content: 'Fields are empty!',
                // 		buttons:{
                // 			close:{
                // 				btnClass: 'btn-danger',
                // 				action: function(){

                // 				}
                // 			}
                // 		}
                // 	// });

                // 	return false;
                // }

                var url = 'http://tms.ideaserv.com.ph:8080/logs-report?empno='+empno+'&name='+name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity03='+entity03
                // var url = 'http://192.168.0.64:8000/logs-report?empno='+empno+'&name='+name+'&fromdate='+fromdate+'&todate='+todate+'&entity01='+entity01+'&entity03='+entity03
                myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
                myWindow.focus();
            }
        }
    }
});

});

$('#empno').change(function(event){
	
	var empno = $('#empno').find(':selected').data('name');
	var entity01 = $('#empno').find(':selected').data('entity01');
	var entity03 = $('#empno').find(':selected').data('entity03');

	$('#name').val(empno);
	$('#entity01').val(entity01);
	$('#entity03').val(entity03);
	$('#name').selectpicker('refresh');

	console.log(empno);
});

$('#name').change(function(event){
	
	var name = $('#name').find(':selected').data('empno');
	var entity01 = $('#name').find(':selected').data('entity01');
	var entity03 = $('#name').find(':selected').data('entity03');

	$('#empno').val(name);
	$('#entity01').val(entity01);
	$('#entity03').val(entity03);
	$('#empno').selectpicker('refresh');

	console.log(name);
});