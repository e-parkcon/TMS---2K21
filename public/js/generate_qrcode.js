
$('a#health_qr_btn').click(function(){

    var q1_a = $('input[name="q1_a"]:checked').val();
    var q1_b = $('input[name="q1_b"]:checked').val();
    var q1_c = $('input[name="q1_c"]:checked').val();
    var q1_d = $('input[name="q1_d"]:checked').val();
    var q2 = $('input[name="q2"]:checked').val();
    var q3 = $('input[name="q3"]:checked').val();
    var q4 = $('input[name="q4"]:checked').val();
    var q5 = $('input[name="q5"]:checked').val();
    var ncrPlace =   $('#ncrPlace').val();

    if(!q1_a || !q1_b || !q1_c || !q1_d || !q2 || !q3 || !q4 || !q5){
        $.alert('Please fill up the required fields!');
        return false;
    }

    if(q5 == 'Y' && !ncrPlace){
        $.alert('Please specify the place!');
        return false;
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:{
            q1_a    : q1_a,
            q1_b    : q1_b,
            q1_c    : q1_c,
            q1_d    : q1_d,
            q2      : q2,
            q3      : q3,
            q4      : q4,
            q5      : q5,
            ncrPlace    :   ncrPlace
        },
        url: '/health_declaration',
        success: function(response){

            toastr.success('QR Code generated successfuly.');

            location.reload();

        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.log(status, xhr, error);
            $.alert('Error - ' + errorMessage);
        }
    });

});

function enableTextbox(){
    var q5 = $('input[name="q5"]:checked').val();

    if(q5 == "Y"){
        $('#ncrPlace').attr('disabled', false);
    }
    else{
        $('#ncrPlace').attr('disabled', true);
    }
}