jQuery(document).ready(function($){
    $( "#ob_fromdate" ).datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });

    $( "#ob_todate" ).datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1
    });
});

var ob_application = "<div class='modal fade' tabindex='-1' id='DTR_TR' role='dialog'>" +
                        "<div class='modal-dialog modal-md' role='document'>" +
                            "<div class='modal-content'>" +
                                "<div class='modal-header'>" +
                                    "<h6 class='modal-title text-sm font-weight-bold'>DTR</h6>" +
                                    "<button type='button' class='close text-sm' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                                "</div>" +
                                "<div class='modal-body'>"+
                                    "<div class='row'>"+
                                        // "<form>"+

                                            "<div class='col-md-6'><label for='ob_fromdate'><small>From Date</small></label><span class='float-right'>:</span></div>"+
                                            "<div class='col-md-6'><input type='text' class='form-control form-control-sm text-center' name='ob_fromdate' id='ob_fromdate' autocomplete='off' placeholder='YYYY-MM-DD' required /></div>"+

                                            "<div class='col-md-6'><label for='ob_todate'><small>To Date</small> </label><span class='float-right'>:</span></div>"+
                                            "<div class='col-md-6'><input type='text' class='form-control form-control-sm text-center' name='ob_todate' id='ob_todate' autocomplete='off' placeholder='YYYY-MM-DD' required /></div>"+

                                            "<div class='col-md-6 mt-1'>"+
                                                "<select name='empno_list' id='empno_list' class='selectpicker form-control form-control-sm' data-live-search='true' data-size='10'>"+
                                                    "<option selected hidden disabled>Choose ID #</option>"+
                                                "</select>"+
                                            "</div>"+
                                            "<div class='col-md-6 mt-1'>"+
                                                "<select name='emp_name' id='emp_name' class='selectpicker form-control form-control-sm' data-live-search='true' data-size='10'>"+
                                                    "<option selected hidden disabled>Choose Name</option>"+
                                                "</select>"+
                                            "</div><div><hr></div>"+

                                        // "</form>"+
                                    "</div>"+
                                "</div>"+
                                "<div class='modal-footer'>"+
                                    "<div class='col-md-12'>"+
                                        "<div class='row g-2'>"+
                                            "<div class='col-md-6'><a href='#' name='dtr_trans' id='dtr_trans' class='btn btn-block btn-sm btn-primary'><small>DTR Transaction</small></a></div>"+
                                            "<div class='col-md-6'><a href='#' name='change_shift' id='change_shift' class='btn btn-block btn-sm btn-primary'><small>Change Shift</small></a></div>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>"+
                        "</div>"+
                    "</div>";


$('body').append(ob_application);

$(document).ready(function(){

    $('#empno_list').change(function(event){
        var name = $('#empno_list').find(':selected').attr('name');
        $('#emp_name').val(name).selectpicker('refresh');
    });

    // $('.empno').on('click', function(){
    $('#empno_list').show(function(){
        // console.log('click');
        $.ajax({
            type	: 	'GET',
            url		: 	'/empno',
            success	: 	function(data){
                    // console.log(data);

                    for(var i=0;i<data.length;i++){
                        $('#empno_list').append(
                            "<option value='"+data[i].empno+"' name='"+data[i].name+"'>"+ data[i].empno +"</option>"
                        );
                    }
                    $('#empno_list').selectpicker('refresh');
            }
        });
    });


    $('#emp_name').change(function(event){
        var empno = $('#emp_name').find(':selected').attr('empno');
        $('#empno_list').val(empno).selectpicker('refresh');
    });

    $('#emp_name').show(function(){
        // console.log('click');
        $.ajax({
            type	: 	'GET',
            url		: 	'/empno',
            success	: 	function(data){
                    // console.log(data);

                    for(var y=0;y<data.length;y++){
                        $('#emp_name').append(
                            "<option value='"+data[y].name+"' empno='"+data[y].empno+"'>"+ data[y].name +"</option>"
                        );
                    }
                    $('#emp_name').selectpicker('refresh');
            }
        });
    });
});

$('#dtr_trans').click(function(){

    var from    = $('#ob_fromdate').val();
    var to      = $('#ob_todate').val();
    var empno   = $('#empno_list').selectpicker().val();
    var emp_name   = $('#emp_name').selectpicker().val();

    if(from == "" || to == "" || empno == null || emp_name == null){
        $.alert({
            animation: 'zoom',
            animateFromElement: false,
            title: '<span class="text-danger fa fa-exclamation-triangle"></span> <b class="text-danger">Alert!</b>',
            content: '<h6>All fields are required!</h6>',
        });
    }
    else{
        window.location.href = '/dtr_transac/'+from+'/'+to+'/'+empno;
    }

    console.log(from, to, empno, emp_name);
});


$('#change_shift').click(function(){

    var from    = $('#ob_fromdate').val();
    var to      = $('#ob_todate').val();
    var empno   = $('#empno_list').val();
    var emp_name   = $('#emp_name').val();

    if(from == "" || to == "" || empno == null || emp_name == null){
        $.alert({
            animation: 'zoom',
            animateFromElement: false,
            title: '<span class="text-danger fa fa-exclamation-triangle"></span> <b class="text-danger">Alert!</b>',
            content: '<h6>All fields are required!</h6>',
        });
    }
    else{
        window.location.href = '/change_shift/'+from+'/'+to+'/'+empno;
    }

    console.log(from, to, empno, emp_name);
});

$('#edit_logs').on('show.bs.modal', function(event){
    var button      = $(event.relatedTarget) // Button that triggered the modal
    var txndate     = button.data('txndate') // Extract info from data-* attributes
    var empno       = button.data('empno')
    var day         = button.data('day')
    var shift       = button.data('shift')
    var time_in     = button.data('time_in')
    var break_out   = button.data('break_out')
    var break_in    = button.data('break_in')
    var time_out    = button.data('time_out')
    var nd_out    = button.data('nd_out')

    var modal = $(this)
    
    modal.find('.modal-body #txndate_display').val(day +' / '+ txndate)
    modal.find('.modal-body #txndate').val(txndate)
    modal.find('.modal-body #empno').val(empno)
    modal.find('.modal-body #shift').val(shift)
    modal.find('.modal-body #am_in').val(time_in)
    modal.find('.modal-body #am_out').val(break_out)
    modal.find('.modal-body #pm_in').val(break_in)
    modal.find('.modal-body #pm_out').val(time_out)
    modal.find('.modal-body #am_in_old').val(time_in)
    modal.find('.modal-body #am_out_old').val(break_out)
    modal.find('.modal-body #pm_in_old').val(break_in)
    modal.find('.modal-body #pm_out_old').val(time_out)
    $('input[name="nd_out"][value="'+nd_out+'"]').prop('checked',true);

});

$('#LV_SUM').click(function(){
    localStorage.removeItem('stat');
});

$('#OT_SUM').click(function(){
    localStorage.removeItem('ot_stat');
});