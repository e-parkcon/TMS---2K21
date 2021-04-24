$('a#attachment').click(function() {

    var refno = $(this).attr('refno');
    var ot_id = $(this).attr('ot_id');
    var empno = $(this).attr('empno');
  
    console.log(refno, ot_id, empno);
    $.alert({
        columnClass: 'col-xs-6 col-sm-6 col-md-3',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        animation: 'zoom',
        animateFromElement: false,
        draggable: false,
        type: 'dark',
        icon: 'fa fa-file',
        title: 'Attachment(s)',
        content: function(){
            var self = this;
            return $.ajax({
                url: '/ot_attachments/'+refno+'/'+ot_id+'/'+empno,
                dataType: 'json',
                method: 'GET'
            }).done(function (response) {
                console.log(response);
                for(i=0; i < response.length; i++){
                    // self.setContentAppend('<a href="'+response[i]+'" target="_blank1"> Attachment #'+i+'</a><br>');
                    self.setContentAppend('<div class="col-md-12">'+
                                            '<a href="'+response[i]+'" target="_blank1"> Attachment #'+i+'</a>'+
                                            '</div>'
                                            );
                }
            }).fail(function() {
                // self.setContentAppend('Something went wrong!');
                $.alert('Something went wrong!');
            });
        },
        buttons:{
            close:{
                btnClass: 'btn-danger'

            }
        }
    });
});