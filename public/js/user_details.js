var level       =   $('#level').val();
var cocode      =   $('#cocode').val();
var distcode    =   $('#distcode').val();
var brchcode    =   $('#brchcode').val();
var dept_code   =   $('#dept_code').val();
var shift_code  =   $('#shift_code').val();

console.log(cocode, distcode, brchcode, dept_code);

$( "#birth_date" ).datepicker({
    dateFormat: 'yy-mm-dd',
    // numberOfMonths: 1
    changeMonth: true,
    changeYear: true
});

$( "#empl_date" ).datepicker({
    dateFormat: 'yy-mm-dd',
    // numberOfMonths: 1
    
    changeMonth: true,
    changeYear: true
});

// CHANGE EMPLOYEE STATUS
$('#emp_status').change(function(){

    var status  =   $(this).val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        dataType: "json",
        url: '/employess/change/status/'+empno,
        data: {
            'status': status, 
        },
        success: function(data){
            
            if(data == 'success'){
                // location.reload();
                toastr.success('Employee status changed!');

                if(status == 'N'){
                    $('#term').fadeIn();
                }
                else{
                    $('#term').fadeOut();
                }

            }
            else{
                toastr.error(data);
            }

        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.log(status, xhr, error);
            $.alert('Error - ' + errorMessage);
        }
    })

});
// END OF CHANGE OF EMPLOYEE STATUS


// $("#user_level").val(level);
// $("#entity01").val(cocode);
// $("#entity02").val(distcode);
// $("#entity03").val(brchcode);
// $("#deptcode").val(dept_code);
// $(".selectpicker").selectpicker('refresh');

console.log(level, cocode, distcode, brchcode, dept_code, shift_code);

$.ajax({
    type:"GET",
    url:'/userlevel',
    success:function(res){
        if(res == ''){
            $("#user_level").empty();
        }
        else{
            // console.log(res);
            // $("#user_level").empty();
            $("#user_level").append('<option selected hidden disabled>User Level</option>');
            $.each(res,function(key,value){
                console.log(key, value);
                $("#user_level").append('<option value="'+key+'">'+value+'</option>');
                $("#user_level").selectpicker('refresh');

                $("#user_level").find('[value="'+level+'"]').attr('selected', true);
                $("#user_level").selectpicker('refresh');
            });
        }
    }
});

$.ajax({
    type:"GET",
    url:'/entity01',
    success:function(res){
        // if(res == ''){
        //     $("#entity01").empty();
        //     $('#entity01').selectpicker('refresh');
        // }
        // else{
            // console.log(res, cocode);
            $("#entity01").empty();
            $("#entity01").append('<option selected disabled>Company</option>');
            $.each(res,function(key,value){
                // console.log(key, value);
                
                $("#entity01").append('<option value="'+key+'">'+value+'</option>');
                $('#entity01').selectpicker('refresh');

                $("#entity01").find('[value="'+cocode+'"]').attr('selected', true);
                $('#entity01').selectpicker('refresh');
            });
        // }
    }
});

$.ajax({
    type:"GET",
    url:'/entity02?entity01='+ cocode,
    success:function(res){
        
        if(res == ''){
            $("#entity02").empty();
            // $('#entity02').selectpicker('refresh');
        }
        else{
            // console.log(res);
            $("#entity02").empty();
            $("#entity02").append('<option selected hidden disabled>Select District</option>');
            $.each(res,function(key,value){
                $("#entity02").append('<option value="'+key+'">'+value+'</option>');
                $('#entity02').selectpicker('refresh');

                $("#entity02").find('[value="'+distcode+'"]').attr('selected', true);
                $('#entity02').selectpicker('refresh');
            });
        }
    }
});

$.ajax({
    type:"GET",
    url:'/entity03?entity01='+ cocode+'&'+'entity02='+distcode,
    success:function(res){

        if(res == ''){
            $("#entity03").empty();
            $('#entity03').selectpicker('refresh');
        }
        else{
            // console.log(res);
            $("#entity03").empty();
            $("#entity03").append('<option selected hidden disabled>Select Branch</option>');
            $.each(res,function(key,value){
                $("#entity03").append('<option value="'+key+'">'+value+'</option>');
                $("#entity03").selectpicker('refresh');

                $("#entity03").find('option[value="'+brchcode+'"]').attr('selected', true);
                $("#entity03").selectpicker('refresh');
            });
        }
    }
});

$.ajax({
    type:"GET",
    url:'/deptcode?entity01='+ cocode,
    success:function(res){
        
        if(res == ''){
            $("#deptcode").empty();
            $("#deptcode").selectpicker('refresh');
        }
        else{
            // console.log(res);
            // $("#deptcode").empty();
            $("#deptcode").append('<option selected hidden disabled>Select Department</option>');
            $.each(res,function(key,value){
                $("#deptcode").append('<option value="'+key+'">'+value+'</option>');
                $('#deptcode').selectpicker('refresh');

                $("#deptcode").find('[value="'+dept_code+'"]').attr('selected', true);
                $('#deptcode').selectpicker('refresh');
            });
        }

    }
});

$.ajax({
    type:"GET",
    url:'/shifts',
    success:function(res){
        if(res == ''){
            $("#shifts").empty();
            $('#shifts').selectpicker('refresh');
        }
        else{
            // console.log(res);
            $("#shifts").empty();
            $("#shifts").append('<option selected hidden disabled>Shift</option>');
            $.each(res,function(key,value){
                $("#shifts").append('<option value="'+key+'">'+key+' - '+value+'</option>');
                $('#shifts').selectpicker('refresh');

                $("#shifts").find('[value="'+shift_code+'"]').attr('selected', true);
                $('#shifts').selectpicker('refresh');
            });
        }
    }
});

$('#entity01').on('change',function(){
    var entity01 = $("#entity01").val();    
    console.log(entity01);
    if(entity01){
        $.ajax({
            type:"GET",
            url:'/entity02?entity01='+ entity01,
            success:function(res){
                
                if(res == ''){
                    $("#entity02").empty();
                    $('#entity02').selectpicker('refresh');
                }
                else{
                    console.log(res);
                    $("#entity02").empty();
                    $("#entity02").append('<option selected hidden disabled>Select District</option>');
                    $.each(res,function(key,value){
                        $("#entity02").append('<option value="'+key+'">'+value+'</option>');
                        $('#entity02').selectpicker('refresh');
                    });
                }
            }
        });
    }
    else{
        $("#entity02").empty();
        $('#entity02').selectpicker('refresh');
    }
});

$('#entity02').on('change',function(){

    var entity01 = $("#entity01").val();    
    var entity02 = $("#entity02").val();

    console.log(entity01, entity02);

    if(entity01 && entity02){
        $.ajax({
            type:"GET",
            url:'/entity03?entity01='+ entity01+'&'+'entity02='+entity02,
            success:function(res){

                if(res == ''){
                    $("#entity03").empty();
                    $('#entity03').selectpicker('refresh');
                }
                else{
                    console.log(res);
                    $("#entity03").empty();
                    $("#entity03").append('<option selected hidden disabled>Select Branch</option>');
                    $.each(res,function(key,value){
                        $("#entity03").append('<option value="'+key+'">'+value+'</option>')
                        $("#entity03").selectpicker('refresh');
                    });
                }
            }
        });
    }else{
        $("#entity03").empty();
        $('#entity03').selectpicker('refresh');
    }
});

$('#entity03').on('change',function(){
    var cocode = $("#entity01").val();    
    // var distcode = $("#distcode").val();

    console.log(cocode);
    if(cocode){
        $.ajax({
            type:"GET",
            url:'/deptcode?entity01='+ cocode,
            success:function(res){
                
                if(res == ''){
                    $("#deptcode").empty();
                    // $("#deptcode").selectpicker('refresh');
                }
                else{
                    console.log(res);
                    $("#deptcode").empty();
                    $("#deptcode").append('<option selected hidden disabled>Select Department</option>');
                    $.each(res,function(key,value){
                        $("#deptcode").append('<option value="'+key+'">'+value+'</option>');
                        $('#deptcode').selectpicker('refresh');
                    });
                }

            }
        });
    }else{
        $("#deptcode").empty();
        $("#deptcode").selectpicker('refresh');
    }
});

$(document).ready(function(){

    // LEAVE CREDITS
    $('#leave_type').change(function(){
          var no_days =  $('#leave_type').find(':selected').attr('no_days');
        $('#no_days').val(no_days);
    });

    function tbl_leave_credits(){

        $.ajax({
            method: 'GET',
            url: '/employees/lv_credits/'+empno,
            success: function(res){
                // console.log(res);

                if(res[0].length == 0){
                    $('#tbl_user_lv tbody').append(
                        '<tr>'+
                            '<td colspan=3 class="text-center">No Available Leaves Yet</td>'+
                        '</tr>');
                }
                for(l=0; l < res[0].length; l++){
                    $('#tbl_user_lv tbody').append(
                        '<tr>'+
                            '<td>'+res[0][l].lv_desc+'</td>'+
                            '<td>'+res[0][l].no_days+'</td>'+
                            '<td>'+res[0][l].lv_balance+'</td>'+
                            (res[1] >= 7 ? '<td class="text-center"><a href="#" lv_desc="'+res[0][l].lv_desc+'" lv_code="'+res[0][l].lv_code+'" lv_balance="'+res[0][l].lv_balance+'" id="btn_edit_lv" class="btn btn-sm btn-primary"><small>EDIT</small></a></td>' : '')+
                        '</tr>');
                }

            }
        });
    }

    $('.table-responsive').append(tbl_leave_credits());

    $('#btn_save_lv_type').click(function(){

        var lv_type =   $('#leave_type').val();
        var no_days =   $('#no_days').val();

        if(!lv_type){
            $.alert('Please select leave type.');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            data:{
                lv_type: lv_type,
                no_days: no_days
            },
            url: '/employees/lv_credits/'+empno,
            success: function (res){
                // console.log(res);
                if(res != 'success'){
                    $.alert(res);
                    return false;
                }
                else{
                    toastr.success('New leave credit added.');
                }

                $("#tbl_user_lv tbody").empty();
                // $('#leave_type').val('');
                $('#no_days').val('');
                $('#tbl_user_lv tbody').append(tbl_leave_credits());
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log(status, xhr, error);
                $.alert('Error - ' + errorMessage);
            }
        })

    });
    // END OF LEAVE CREDITS

    $('#tbl_user_lv tbody').on( 'click', 'a', function () {

        var lv_desc =   $(this).attr('lv_desc');
        var lv_balance  =   $(this).attr('lv_balance');
        var lv_code     =   $(this).attr('lv_code');

        $.alert({
            columnClass: 'col-xs-8 col-sm-6 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            className: 'text-uppercase',
            type: 'blue',
            icon: 'fa fa-lock',
            title: lv_desc,
            content: 
                '<form>'+
                    '<div class="col-md-12">'+
                        '<div class="row">'+
                            '<label for="lv_balance" class="form-label"><small>Leave Balance :</small></label>'+
                            '<input type="number" class="form-control form-control-sm lv_balance" name="lv_balance" id="lv_balance" value="'+lv_balance+'" required>'+
                        '</div>'+
                    '</div>'+
                '</form>',
            buttons:{
                formSubmit:{
                    text: 'Save Changes',
                    btnClass: 'btn-primary',
                    action: function(){

                        var new_lvbalance   =   this.$content.find('.lv_balance').val();

                        if(!new_lvbalance){
                            $.alert('Please input a valid amount');
                            return false;
                        }

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PUT",
                            url: '/edit/lv/balance/'+empno,
                            data:{
                                lv_balance: new_lvbalance,
                                lv_code: lv_code
                            },
                            success:function(res){
                                // $.alert('Password reset successful');

                                toastr.success('Leave balance updated.'); // Toast
                                window.location.reload();
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);
                            }
                        })
                    }
                },
                close:{
                    btnClass: 'btn-danger',
                    action: function(){

                    }
                }
            },
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    });

    // PASSWORD RESET
    $('a#reset_pass').click(function (){
        $.alert({
            columnClass: 'col-xs-8 col-sm-6 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            className: 'text-uppercase',
            type: 'blue',
            icon: 'fa fa-lock',
            title: 'Reset Password',
            buttons:{
                reset:{
                    btnClass: 'btn-primary',
                    action: function(){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PUT",
                            url: '/employees/reset/'+empno,
                            success:function(res){
                                $.alert('Password reset successful');
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);
                            }
                        })
                    }
                },
                close:{
                    btnClass: 'btn-danger',
                    action: function(){

                    }
                }
            }
        });
    });
    // END OF PASSWORD RESET
});