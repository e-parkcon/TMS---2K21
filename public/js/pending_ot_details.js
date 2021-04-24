$(document).ready(function(){
    
    $('#chckAll').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
                $('.btns_approve_disapprove').show();
                console.log($('#chckAll').val());
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
                $('.btns_approve_disapprove').hide();
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){

            $('#chckAll').prop('checked',true);

            $('.btns_approve_disapprove').show();
            console.log($('#chckAll').val());
        }
        else{
            if($('.checkbox:checked').length >= 1){
                $('#chckAll').prop('checked',false);                
                $('.btns_approve_disapprove').show();
            }
            else{
                $('#chckAll').prop('checked',false);
                $('.btns_approve_disapprove').hide();
            }
        }
    });

    $('a#approve_selected_ot').click(function(){

        var refno   =   $(this).attr('refno');
        var empno   =   $(this).attr('empno');

        $.alert({
            columnClass: 'col-xs-12 col-sm-4 col-md-4',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'dark',
            icon: 'fa fa-',
            title: 'Approved Selected OT',
            buttons:{
                confirm:{
                    btnClass: 'btn-primary',
                    action: function(){
                        
                        $('#approve_selected_ot').html('<small><i class="spinner-border spinner-border-sm text-warning mr-2 pr-2"></i> Submitting ..</small>');
						$('#approve_selected_ot').prop('disabled', true);

                        $('#ot_list_form').submit();
                    }
                },
                cancel:{
                    btnClass: 'btn-danger',
                    action: function(){

                    }
                }
            }
        });
    });

    $('a#disapprove_selected_ot').click(function(){
        var refno   =   $(this).attr('refno');
        var empno   =   $(this).attr('empno');

        $.alert({
            columnClass: 'col-xs-12 col-sm-4 col-md-4',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'dark',
            icon: 'fa fa-',
            title: 'Disapproved Selected OT',
            content: 
            '<form>'+
                '<div class="col-md-12">'+
                    '<div class="row g-1">'+
                        '<div class="col-md-12">'+
                            '<label class="form-label"><small>Are you sure you want to disapprove? </small></label>'+
                        '</div>'+
                        '<div class="col-md-12">'+
                            '<label class="form-label"><small>Reason :</small></label>'+
                            '<textarea class="form-control form-control-sm reason" style="resize: none;" cols="3" rows="3" ></textarea>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</form>',
            buttons:{
                confirm:{
                    btnClass: 'btn-primary',
                    action: function(){

                        var reason     =   this.$content.find('.reason').val();

                        if(!reason){
                            $.alert('Provide reason for disapproving.');
                            return false;
                        }

                        // window.location.href    =   '/disapprove/selected_ot/'+refno+'/'+empno;

                        var form    =   $('#ot_list_form');

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
                            data: form.serialize() + '&reason='+reason,
							url: '/disapprove/selected_ot/'+refno+'/'+empno,
                            success: function(response){
                                window.location.href    =   response;
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);
                            }
                        })

                    }
                },
                cancel:{
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

    $('a#edit_hours').click(function(){

        var ot_id   =   $(this).attr('ot_id');
        var refno   =   $(this).attr('refno');
        var empno   =   $(this).attr('empno');

        $.alert({
            columnClass: 'col-xs-12 col-sm-4 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'dark',
            icon: 'fa fa-edit',
            title: 'Edit Hours',
            content: 
                '<form>'+
                    '<div class="col-md-12">'+
                        '<div class="row g-1">'+
                            '<div class="col-md-12">'+
                                '<label class="form-label"><small>Enter Approve # of hours : </small></label>'+
                                '<input type="number" name="appr_hours" id="appr_hours" class="form-control form-control-sm appr_hours" />'+
                            '</div>'+
                            '<div class="col-md-12">'+
                                '<label class="form-label"><small>Reason :</small></label>'+
                                '<textarea class="form-control form-control-sm reason" style="resize: none;" cols="3" rows="3" ></textarea>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</form>',
            buttons:{
				formSubmit:{
                    text: 'Save changes',
                    btnClass: 'btn-primary',
                    action: function(){
                        
                        var appr_hours =   this.$content.find('.appr_hours').val();
						var reason     =   this.$content.find('.reason').val();

                        if(!appr_hours){
                            $.alert('Enter approve # of hours');
                            return false;
                        }

						$.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
							data:{
								// lv_id:	lv_id,
								appr_hours:	appr_hours,
								reason: reason
							},
							url: '/edit_hrs/overtime/'+refno+'/'+ot_id+'/'+empno,
							success: function(response){
								// console.log(response);

								toastr.success('Overtime Approved.');
                                location.reload();
								// window.location.href = '/pending_lv';
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
					btnClass: "btn-link",
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

    $('a#disapprove_one_ot').click(function(){

        var ot_id   =   $(this).attr('ot_id');
        var refno   =   $(this).attr('refno');
        var empno   =   $(this).attr('empno');


        $.alert({
            columnClass: 'col-xs-12 col-sm-4 col-md-3',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'red',
            icon: 'fa fa-clock',
            title: 'Disapprove OT',
            content: 
                '<form>'+
                    '<div class="col-md-12">'+
                        '<div class="row g-1">'+
                            '<div class="col-md-12">'+
                                '<label class="form-label"><small>Are you sure to disapprove this overtime? </small></label>'+
                            '</div>'+
                            '<div class="col-md-12">'+
                                '<label class="form-label"><small>Reason :</small></label>'+
                                '<textarea class="form-control form-control-sm reason" style="resize: none;" cols="3" rows="3" ></textarea>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</form>',
            buttons:{
				formSubmit:{
                    text: 'Confirm',
                    btnClass: 'btn-primary',
                    action: function(){

						var reason     =   this.$content.find('.reason').val();

                        if(!reason){
                            $.alert('Provide reason for disapproving.');
                            return false;
                        }

						$.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PATCH",
							data:{
								reason: reason
							},
							url: '/disapprove/overtime/'+refno+'/'+ot_id+'/'+empno,
							success: function(response){
								// console.log(response);

								toastr.success('Overtime Disapproved.');

                                location.reload();
								// window.location.href = '/pending_lv';
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
					btnClass: "btn-link",
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

    $('a#view_status').click(function(){

        var ot_id   =   $(this).attr('ot_id');

        $.ajax({
            method: 'GET',
            url: '/status/'+ ot_id,
            success: function(res){
                $.alert({
                    columnClass: 'col-xs-12 col-sm-8 col-md-5',
                    containerFluid: true, // this will add 'container-fluid' instead of 'container'
                    animation: 'top',
                    animateFromElement: false,
                    draggable: false,
                    type: 'dark',
                    icon: 'fa fa-calendar',
                    title: '---',
                    content: res, // BLADE VIEW
                    buttons:{
                        close:{
                            btnClass: 'btn-danger',
                            action: function(){

                            }
                        }
                    }
                });
            }
        });
    });

});