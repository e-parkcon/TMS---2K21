$(document).ready(function(){

    function leave_app(){
        var empno   =   $('#v-pills-profile-tab').attr('empno');
        var lv      =   $('#v-pills-profile-tab').attr('lv');

        $.ajax({
            method:"GET",
            data:{
                empno: empno,   
                otlv: lv
            },
            url:'/app_group/officers',
            success: function(response){

                $('#lv_appgrp_div').append(response); // BLADE VIEW 
            }
        });
    }

    function overtime_app(){
        var empno   =   $('#v-pills-profile-tab').attr('empno');
        var ot      =   $('#v-pills-profile-tab').attr('ot');
    
        $.ajax({
            method:"GET",
            data:{
                empno: empno,
                otlv: ot
            },
            url:'/app_group/officers',
            success: function(response){
                // console.log(response);
                $('#ot_appgrp_div').append(response); // BLADE VIEW     

            }
        });
    }


    $('#lv_appgrp_div').append(leave_app());
    $('#ot_appgrp_div').append(overtime_app());

    $('a#lv_app_group').click(function(){
        $.ajax({
            method: 'GET',
            url: '/approving_groups',
            data:{
                otlv: 'L'
            },
            success: function(res){
                $.alert({
                    columnClass: 'col-xs-12 col-sm-8 col-md-4',
                    containerFluid: true, // this will add 'container-fluid' instead of 'container'
                    animation: 'top',
                    animateFromElement: false,
                    draggable: false,
                    type: 'dark',
                    icon: 'fa fa-users',
                    title: 'Leave Approving Group',
                    content: res, // BLADE VIEW // components.approving_group_dropdown
                    buttons:{
                        formSubmit:{
                            text: 'Set Group',
                            btnClass: 'btn-primary',
                            action: function(){
                                var app_group     =   this.$content.find('.app_group').val();
                                var empno         =   $('a#lv_app_group').attr('empno');
                                // console.log(empno);
                                if(!app_group){
                                    $.alert('Choose approving group!');
                                    return false;
                                }
        
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: "PUT",
                                    data:{
                                        app_code: app_group,
                                        lv_ot: 'L'
                                    },
                                    url: '/approving/'+empno,
                                    success: function (res){
        
                                        if(res == 'success'){
                                            toastr.success('Leave approving group set!');
                                        }
                                        else{
                                            toastr.error(res);
                                        }
        
                                        $("#lv_appgrp_div").empty();
                                        $("#lv_appgrp_div #lv_appgrp_div_2").append(leave_app());
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
            }
        });
    });

    $('a#ot_app_group').click(function(){
        $.ajax({
            method: 'GET',
            url: '/approving_groups',
            data:{
                otlv: 'O'
            },
            success: function(res){
                $.alert({
                    columnClass: 'col-xs-12 col-sm-8 col-md-4',
                    containerFluid: true, // this will add 'container-fluid' instead of 'container'
                    animation: 'top',
                    animateFromElement: false,
                    draggable: false,
                    type: 'dark',
                    icon: 'fa fa-users',
                    title: 'Overtime Approving Group',
                    content: res, // BLADE VIEW // components.approving_group_dropdown
                    buttons:{
                        formSubmit:{
                            text: 'Set Group',
                            btnClass: 'btn-primary',
                            action: function(){
                                
                                var app_group     =   this.$content.find('.app_group').val();
                                var empno         =   $('a#ot_app_group').attr('empno');
                                // console.log(empno);
                                if(!app_group){
                                    $.alert('Choose approving group!');
                                    return false;
                                }

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: "PUT",
                                    data:{
                                        app_code: app_group,
                                        lv_ot: 'O'
                                    },
                                    url: '/approving/'+empno,
                                    success: function (res){

                                        if(res == 'success'){
                                            toastr.success('Overtime approving group set!');
                                        }
                                        else{
                                            toastr.error(res);
                                        }

                                        $("#ot_appgrp_div").empty();
                                        $("#ot_appgrp_div #ot_appgrp_div_2").append(overtime_app());
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
            }
        });
    });

});