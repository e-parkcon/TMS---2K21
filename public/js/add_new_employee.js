
$.ajax({
    type:"GET",
        url:'/userlevel',
        success:function(res){
            if(res == ''){
                $("#user_level").empty();
                $('#user_level').selectpicker('refresh');
            }
            else{
                console.log(res);
                $("#user_level").empty();
                $("#user_level").append('<option selected disabled>User Level</option>');
                $.each(res,function(key,value){
                    $("#user_level").append('<option value="'+key+'">'+value+'</option>');
                    $('#user_level').selectpicker('refresh');
                });
            }
        }
});

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