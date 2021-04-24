$(document).ready(function(){

    $('a#add_new_appr').click(function(){
        $.confirm({
            columnClass: 'col-xs-12 col-sm-4 col-md-4',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'blue',
            icon: 'fa fa-users',
            title: 'New Approving Group',
            content: 
                '<form>'+
                    '<div class="col-md-12">'+
                        '<div class="row g-1">'+
                            '<div class="col-md-4">'+
                                '<label class="form-label"><small>Name : </small></label>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<input type="text" name="app_desc" id="app_desc" class="form-control form-control-sm app_desc" />'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<label class="form-label"><small>Category : </small></label>'+
                            '</div>'+
                            '<div class="col-md-8">'+
                                '<select name="category" id="category" class="form-control form-control-sm custom-select text-uppercase category">'+
                                    '<option class="text-uppercase" selected hidden disabled>Choose Category</option>'+
                                    '<option class="text-uppercase" value="O">Overtime</option>'+
                                    '<option class="text-uppercase" value="L">Leave</option>'+
                                '</select>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</form>',
            buttons:{
                formSubmit:{
                    text: 'Add New Group',
                    btnClass: 'btn-primary',
                    action: function(){

                        var app_desc     =   this.$content.find('.app_desc').val();
                        var category     =   this.$content.find('.category').val();

                        if(!app_desc || !category){
                            $.alert('Input field is required.');
                            return false;
                        }

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'POST',
                            data:{
                                category: category,
                                app_desc: app_desc
                            },
                            url: '/utilities/approving_group',
                            success: function(data){
                                toastr.success('New approving group added.');

                                table.ajax.reload();
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
        })
    });

    var table = $('#tbl_approving_group').DataTable( {
        // "scrollX": true,
        "ajax": "/utilities/appgrp_json_list",
        "columns": [
			{ "data": "app_desc", "className": "p-2" },
            { 
                "className": "p-2",
                "data": "otlv",
                render: function(data){
                    if(data == 'L'){
                        return "Leave";
                    }
                    if(data == 'O'){
                        return "Overtime";
                    }
                }
            },
            {
				"className": "p-2",
				"data": "app_code",
				render: function(data){
                    return "<div class='dropdown dropleft m-0'>"+
                                "<button class='btn btn-primary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"+
                                "</button>"+
                                "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>"+
                                    "<a href='#' class='dropdown-item' id='view_appgrp_dtls'><small>View details</small></a>"+
                                    "<a class='dropdown-item' href='#' id='edit_group_name'><small>Edit group name</small></a>"+
                                    "<a class='dropdown-item' href='#' id='delete_group'><small>Delete group</small></a>"+
                                "</div>"+
                            "</div>";
				}
			},
        ]
    } );

    $('#tbl_approving_group tbody').on( 'click', '.dropdown .dropdown-menu a', function () {
        var data = table.row( $(this).parents('tr') ).data();
		console.log($(this).attr('id'));

        if($(this).attr('id') == 'view_appgrp_dtls'){
            window.location.href    =   '/utilities/approving_group/'+data['app_code']+'?category='+data['otlv'];
        }

        if($(this).attr('id') == 'edit_group_name'){

            console.log(data['otlv'], data['app_code']);

            $.alert({
                columnClass: 'col-xs-12 col-sm-4 col-md-4',
                containerFluid: true, // this will add 'container-fluid' instead of 'container'
                animation: 'top',
                animateFromElement: false,
                draggable: false,
                type: 'blue',
                icon: 'fa fa-users',
                title: 'Change Approving Name',
                content: 
                    '<form>'+
                        '<div class="col-md-12">'+
                            '<div class="row g-1">'+
                                '<div class="col-md-12">'+
                                    '<label class="form-label"><small>Enter new approving name : </small></label>'+
                                    '<input type="text" name="new_app_desc" id="new_app_desc" class="form-control form-control-sm new_app_desc" value="'+data['app_desc']+'" />'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</form>',
                buttons:{
                    formSubmit:{
                        text: 'Confirm',
                        btnClass: 'btn-primary',
                        action: function(){
                            
                            var app_desc     =   this.$content.find('.new_app_desc').val();

                            if(!app_desc){
                                $.alert('Input field is required.');
                                return false;
                            }

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "PATCH",
                                data:{
                                    app_desc: app_desc,
                                    category: data['otlv']
                                },
                                url: '/utilities/approving_group/'+data['app_code'],
                                success: function(response){
                                    console.log(response);

                                    toastr.success('Approving group name changed.');
                                    table.ajax.reload();
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
        }

        if($(this).attr('id') == 'delete_group'){
            $.alert({
                columnClass: 'col-xs-12 col-sm-4 col-md-4',
                containerFluid: true, // this will add 'container-fluid' instead of 'container'
                animation: 'top',
                animateFromElement: false,
                draggable: false,
                type: 'red',
                icon: 'fa fa-users',
                title: 'Delete Approving Name',
                buttons:{
                    confirm:{
                        btnClass: 'btn-primary',
                        action: function(){
                            $.ajax({
                                method: 'DELETE',
                                data:{
                                    category: data['otlv'],
                                    "_token": $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/utilities/approving_group/'+data['app_code'],
                                success: function(data1){

                                    toastr.success('Approving group deleted.');

                                    table.ajax.reload();

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
        }

    });

});