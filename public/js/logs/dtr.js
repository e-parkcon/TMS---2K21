$(function () {
    //Start of Schedule
    $('#am_in').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true,
    });

    //End of Schedule
    $('#am_out').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true,
    });    

    //timestart
    $('#pm_in').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true,
    });

    //timefinish
    $('#pm_out').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true,
    });

});

function get_data(object){
    this.txndate  	= object.parent().parent().attr('txndate');
    this.day  	    = object.parent().parent().attr('day');
    this.time_in  	= object.parent().parent().attr('in');
    this.break_out  = object.parent().parent().attr('break_out');
    this.break_in  	= object.parent().parent().attr('break_in');
    this.time_out  	= object.parent().parent().attr('out');
}

$('a#raw_time').click(function(){
    var obj = new get_data($(this));
    var txndate     = obj.txndate;
    var day         = obj.day;
    var time_in     = obj.time_in;
    var break_out   = obj.break_out;
    var break_in    = obj.break_in;
    var time_out    = obj.time_out;

    // console.log('click');
    $.confirm({
        animation: 'none',
        boxWidth: '25%',
        useBootstrap: false,
        draggable: false,
        title: 'Raw Time',
        content:'<div class="table-responsive">'+
                '<table width=100% class="text-center">'+
                    '<tr>'+
                        '<td><label class="form-label">'+txndate+' / '+ day +'</label></td>'+
                    '</tr>'+
                '</table>'+
                '<table class="table">'+
                    '<tr class="text-center">'+
                        '<td>'+ time_in +'</td>'+
                    '</tr>'+
                    '<tr class="text-center">'+
                        '<td>'+ break_out +'</td>'+
                    '</tr>'+
                    '<tr class="text-center">'+
                        '<td>'+ break_in +'</td>'+
                    '</tr>'+
                    '<tr class="text-center">'+
                        '<td>'+ time_out +'</td>'+
                    '</tr>'+
                 '</table>'+
                 '</div>',
        buttons: {
            close:{
                btnClass: 'btn-danger',
                action: function(){

                }
            },
        }
    });
});