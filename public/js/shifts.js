$.ajax({
    type:"GET",
    url:'/shifts',
    success:function(res){
        if(res == ''){
            $("#shifts").empty();
            $('#shifts').selectpicker('refresh');
        }
        else{
            console.log(res);
            $("#shifts").empty();
            $("#shifts").append('<option selected disabled>Shift</option>');
            $.each(res,function(key,value){
                $("#shifts").append('<option value="'+key+'">'+key+' - '+value+'</option>');
                $('#shifts').selectpicker('refresh');
            });
        }
    }
});