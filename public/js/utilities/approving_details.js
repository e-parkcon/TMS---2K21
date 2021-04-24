$(document).ready(function(){
    
    $('#app_officers').change(function(event){
        
        var email   = $('#app_officers').find(':selected').attr('email');
        var empno   = $('#app_officers').find(':selected').attr('empno');
        var crypt   = $('#app_officers').find(':selected').attr('crypt');

        $('#app_email').val(email);
        $('#app_empno_officer').val(empno);
        $('#app_crypt').val(crypt);

    });

    $('#new_app_officers').change(function(event){
        
        var email   = $('#new_app_officers').find(':selected').attr('email');
        var empno   = $('#new_app_officers').find(':selected').attr('empno');
        var crypt   = $('#new_app_officers').find(':selected').attr('crypt');

        $('#new_app_email').val(email);
        $('#new_app_empno_officer').val(empno);
        $('#new_app_crypt').val(crypt);

    });

    $('#app_members').change(function(event){
        var empno   = $('#app_members').find(':selected').val();
        $('#app_empno').val(empno);
    });
    $('#app_empno').change(function(event){
        var empno = $('#empno_list').find(':selected').data('empno');
        var cocode = $('#empno_list').find(':selected').data('cocode');
        var name = $('#empno_list').find(':selected').data('name');
        
        $('#member_name').val(empno);
        $('#member_name').selectpicker('refresh');

        $('#member_empno').val(empno);
        $('#member_cocode').val(cocode);

        console.log(empno, cocode, name);
    });

    $('a#add_app_officer').click(function(){

        var app_officers    =   $('#app_officers').val();
        var empno       =   $('#app_empno_officer').val();
        // var app_email   =   $('#app_email').val();
        // var app_crypt   =   $('#app_crypt').val();
        var app_code    =   $('#app_code').val();
        var category    =   $('#category').val();

        if(!app_officers){
            $.alert('Select Approving Officer');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            data:{
                category: category,
                empno: empno,
                // app_crypt: app_crypt,
                // app_officers: app_officers,
                // app_email: app_email
            },
            url: '/utilities/approving_group/add/officer/'+app_code,
            success: function(data){
                if(data == 1){
                    toastr.error('Officer is already in the list.');
                    return false;
                }

                toastr.success('Officer added.');
                location.reload();
                var new_officer =   '<tr id="officer_'+data.empno+'">'+
                                        '<td class="text-uppercase"># '+(data.seqno+1)+'<span class="float-right">:</span></td>'+
                                        '<td class="text-uppercase">'+
                                            data.name+'<br>'+
                                            'Email : '+ data.email+
                                        '</td>'+
                                        '<td width="20%" class="text-center">'+
                                            // '<a href="#" class="text-primary" id="change_officer" title="Change Officer"><span class="fa fa-user-edit"></span></a> | '+
                                            '<a href="#" class="btn btn-sm btn-primary" id="change_officer" data-seqno="'+data.seqno+'" data-toggle="modal" data-target="#change_approving" title="Change Officer"><span class="fa fa-user-edit"></span></a> '+
                                            '<a href="#" class="btn btn-sm btn-danger" id="remove_officer" category="'+data.otlv+'" app_code="'+data.app_code+'" empno="'+data.empno+'" title="Remove Officer">'+
                                            '<span class="fa fa-user-times"></span></a>'+
                                        '</td>'+
                                    '</tr>';

                $('#tbl_officers').append(new_officer);
                $('#app_email').val();
                $('#app_crypt').val();

            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log(status, xhr, error);
                $.alert('Error - ' + errorMessage);
            }
        });
    });


    $(document).on('click', 'a#remove_officer', function(){
        var app_code    =   $(this).attr('app_code');
        var category    =   $(this).attr('category');
        var empno       =   $(this).attr('empno');

        $.alert({
            columnClass: 'col-xs-8 col-sm-6 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'red',
            icon: 'fa fa-user-times',
            title: 'Remove Officer',
            buttons:{
                confirm:{
                    btnClass: 'btn-primary',
                    action: function(){
                        $.ajax({
                            method: 'DELETE',
                            data:{
                                category: category,
                                empno: empno,
                                "_token": $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/utilities/approving_group/remove/officer/'+app_code,
                            success: function(appmember){
                                toastr.success('Officer removed!');

                                $('#officer_'+empno).remove();
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);
                            }
                        });
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

    $('#change_approving').on('show.bs.modal', function (event){

        var button = $(event.relatedTarget)
        var seqno = button.data('seqno')
        var old_officer = button.data('old_officer')
    
        var modal = $(this)
        modal.find('.modal-body #seqno').val(seqno)
        modal.find('.modal-body #old_officer').val(old_officer)
    });

    $('a#save_changes_offr').click(function(){

        var app_officer =   $('#new_app_officers').val();
        var app_code    =   $('#app_code').val();

        if(!app_officer){
            $.alert('Select officer to change');
            return false;
        }

        var form    =   $('#change_offr_form');
        var old_officer   =   $('#old_officer').val();        

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "PATCH",
            data: form.serialize(),
            url: '/utilities/approving_group/change/officer/'+app_code,
            success: function(data){
                
                console.log(old_officer);
                if(data == 1){
                    toastr.error('Officer is already in the list.');
                    return false;
                }

                toastr.success('Officer Change.');
                
                // var new_officer =   '<tr id="officer_'+data.empno+'">'+
                //                         '<td class="text-uppercase"># '+(data.seqno+1)+'<span class="float-right">:</span></td>'+
                //                         '<td class="text-uppercase">'+
                //                             data.name+'<br>'+
                //                             'Email : '+ data.email+
                //                         '</td>'+
                //                         '<td class="text-center">'+
                //                             // '<a href="#" class="text-primary" id="change_officer" title="Change Officer"><span class="fa fa-user-edit"></span></a> | '+
                //                             '<a href="#" class="text-primary" id="change_officer" data-old_officer="'+old_officer+'" data-seqno="'+data.seqno+'" data-toggle="modal" data-target="#change_approving" title="Change Officer"><span class="fa fa-user-edit"></span></a> | '+
                //                             '<a href="#" class="text-danger" id="remove_officer" category="'+data.otlv+'" app_code="'+data.app_code+'" empno="'+data.empno+'" title="Remove Officer">'+
                //                             '<span class="fa fa-user-times"></span></a>'+
                //                         '</td>'+
                //                     '</tr>';
                
                // $('#officer_'+old_officer).replaceWith( new_officer );
                location.reload();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log(status, xhr, error);
                $.alert('Error - ' + errorMessage);
            }
        })


    });

    $('a#add_new_member').click(function(){

        var empno       =   $('#app_members').val();
        var category    =   $('#app_members').find(':selected').attr('category');
        var app_code    =   $('#app_members').find(':selected').attr('app_code');

        console.log(empno, category, app_code);

        if(!empno){
            $.alert('Select employee to add to the group.');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            data:{
                empno: empno,
            },
            url: '/utilities/approving_group/add/member/'+app_code+'/'+category,
            success: function(data){
                
                if(data == 1){
                    toastr.error('Employee is already in a group.');
                    return false;
                }

                toastr.success('New employee added.');

                // var new_member =   '<tr id="member_'+data.empno+'">'+
                //                         '<td class="text-uppercase">'+data.empno+'</td>'+
                //                         '<td width="15%">'+data.empno+'</td>'+
                //                         '<td width="10%" class="text-center">'+
                //                             '<a href="#" id="remove_member" class="text-danger" category="'+data.otlv+'" app_code="'+data.app_code+'" empno="'+data.empno+'"><span class="fa fa-user-times"></span></a>'+
                //                         '</td>'+
                //                     '</tr>'+

                // $('#tbl_app_member').append(new_member);
                location.reload();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.log(status, xhr, error);
                $.alert('Error - ' + errorMessage);
            }
        });
    });


    $('a#remove_member').click(function(){

        var app_code    =   $(this).attr('app_code');
        var category    =   $(this).attr('category');
        var empno    =   $(this).attr('empno');

        $.alert({
            columnClass: 'col-xs-8 col-sm-6 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'red',
            icon: 'fa fa-user-times',
            title: 'Remove Member',
            buttons:{
                confirm:{
                    btnClass: 'btn-primary',
                    action: function(){
                        $.ajax({
                            method: 'DELETE',
                            data:{
                                category: category,
                                empno: empno,
                                "_token": $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '/utilities/approving_group/remove/member/'+app_code,
                            success: function(appmember){
                                toastr.success('Member removed!');

                                $('#member_'+empno).remove();
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);
                            }
                        });
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

});