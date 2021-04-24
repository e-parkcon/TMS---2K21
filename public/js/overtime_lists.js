$(document).ready(function(){
    $("#tbl_ot_lists tbody").on('click','.dropdown .dropdown-menu a',function(){

        if($(this).attr('id') == 'cancel_ot'){

            var refno   =   $(this).attr('refno')
            var ot_id   =   $(this).attr('ot_id')

            console.log('Cancel', refno, ot_id);
            $.confirm({
                columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'top',
				animateFromElement: false,
                type: 'orange',
                icon: 'fa fa-ban',
                title: 'Cancel Overtime',
                buttons:{
                    confirm:{
                        btnClass: "btn-primary",
                        action: function(){
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "DELETE",
                                data:{
                                    refno   :   refno,
                                    ot_id   :   ot_id
                                },
                                url: '/delete/overtime',
                                success: function(response){
                                    $.alert({
                                        columnClass: 'col-xs-12 col-sm-6 col-md-4',
                                        containerFluid: true, // this will add 'container-fluid' instead of 'container'
                                        animation: 'top',
                                        animateFromElement: false,
                                        type: 'blue',
                                        title: '',
                                        content: "Overtime Deleted!",
                                        buttons:{
                                            okay:{
                                                btnClass: 'btn-primary',
                                                action: function(){
                                                    // window.location.reload;
                                                    if(response == 0){
                                                        location.reload();
                                                    }
                                                    else{
                                                        window.location.href = '/overtime';
                                                    }
                                                }
                                            }
                                        }
                                    });
                                },
                                error: function(xhr, status, error){
                                    var errorMessage = xhr.status + ': ' + xhr.statusText
                                    console.log(status, xhr, error);
                                    $.alert('Error - ' + errorMessage);
                                }
                            });
                        }
                    },
                    cancel:{
                        btnClass: "btn-link",
                        action: function(){

                        }
                    }
                }
            });

        }

        if($(this).attr('id') == 'view_status'){
            var refno   =   $(this).attr('refno')
            var ot_id   =   $(this).attr('ot_id')
            
            console.log('View Status');
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
        }

        if($(this).attr('id') == 'printOT_pdf'){
            var refno   =   $(this).attr('refno')
            var ot_id   =   $(this).attr('ot_id')
            var empno   =   $(this).attr('empno')

            $.confirm({
                columnClass: 'col-xs-12 col-sm-6 col-md-3',
                containerFluid: true, // this will add 'container-fluid' instead of 'container'
                animation: 'zoom',
                animateFromElement: false,
                draggable: false,
                type: 'dark',
                icon: 'fa fa-file',
                title: 'Download PDF',
                buttons:{
                    close:{
                        btnClass: 'btn-danger',
                        action: function(){
        
                        }
                    },
                    confirm:{
                        text: 'ok',
                        btnClass: 'btn-primary',
                        action: function(){
                            // var url = 'http://tms.ideaserv.com.ph:8080/ot_form/'+refno+'/'+ot_id+'/'+empno;
                            var url = 'http://192.168.0.64:8000/ot_form/'+refno+'/'+ot_id+'/'+empno;
                            myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
                            myWindow.focus();
                        }
                    }
                }
            });
        }

    });
});