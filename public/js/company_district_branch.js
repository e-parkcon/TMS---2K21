$.ajax({
    type:"GET",
    url:'/entity01',
    success:function(res){
        
        if(res == ''){
            $("#entity01").empty();
            $('#entity01').selectpicker('refresh');
        }
        else{
            console.log(res);
            $("#entity01").empty();
            $("#entity01").append('<option selected disabled>Company</option>');
            $.each(res,function(key,value){
                $("#entity01").append('<option value="'+key+'">'+value+'</option>');
                $('#entity01').selectpicker('refresh');
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
                    $("#entity02").append('<option selected disabled>Select District</option>');
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
                    $("#entity03").append('<option selected disabled>Select Branch</option>');
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

    console.log(cocode);
    if(cocode){
        $.ajax({
            type:"GET",
            url:'/deptcode?entity01='+ cocode,
            success:function(res){
                
                if(res == ''){
                    $("#deptcode").empty();
                    $("#deptcode").selectpicker('refresh');
                }
                else{
                    console.log(res);
                    $("#deptcode").empty();
                    $("#deptcode").append('<option selected disabled>Select Department</option>');
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
