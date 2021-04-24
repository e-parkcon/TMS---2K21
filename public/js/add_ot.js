
$(document).ready(function(){

    $(document).on('change', '.upload-file1', function() {

        var files = $(this)[0].files;
        var newLine;
    
        for (var i = 0; i < files.length; i++) {
            console.log(files[i].name);
            newLine = "<div class='col-md-12'>"+
                            "<i class=' text-success fa fa-file'></i><small> "+files[i].name +"</small></div>"+
                        "</div>";
    
            $("#to_be_appended1 .selected-files1").append(newLine);
            
            // Clone the file selector, assign the name, hide and append it
            $(this).clone().hide().attr('name', 'upload1[]').insertAfter($(this));
        }
        });

    // code to read selected table row cell data (values).
    $("#tbl_add_ot tbody").on('click','.dropdown .dropdown-menu a',function(){

        if($(this).attr('id') == 'edit_ot'){
			
            $('#add_ot_modal_edit').on('show.bs.modal', function (event) {
                // console.log('Modal Open sesame');
                var button 		= $(event.relatedTarget) // Button that triggered the modal
                var ot_id 		= button.data('id') // Extract info from data-* attributes
                var clientname 	= button.data('clientname')
                var dateot2 	= button.data('dateot')
                var timestart 	= button.data('timestart')
                var timefinish 	= button.data('timefinish') 
                var hours 		= button.data('hours')
                var workdone 	= button.data('workdone')
                var shift 		= button.data('shift')
                var shift_desc 	= button.data('shift_desc')
                // var refno 	    = button.data('refno')

                var modal = $(this)
                modal.find('.modal-body #ot_id').val(ot_id)
                modal.find('.modal-body #clientname_edit').val(clientname)
                modal.find('.modal-body #dateot_edit').val(dateot2)
                modal.find('.modal-body #dt6_edit').val(timestart)
                modal.find('.modal-body #dt7_edit').val(timefinish)
                modal.find('.modal-body #hours_edit').val(hours)
                modal.find('.modal-body #workdone_edit').val(workdone)
                modal.find('.modal-body #shift_code_edit').val(shift)
                modal.find('.modal-body #shift_desc_edit').val(shift_desc)
                // modal.find('.modal-body #refno').val(refno)

                var today = new Date()
                var yesterday = new Date(today)
                yesterday.setDate(yesterday.getDate() - 1)
                //Date Of OT
                $( "#dateot_edit" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    useCurrent: false,
                    minDate: '-30d',
                    maxDate: yesterday,
                });

                //EDIT OT MODAL
                $('#dt6_edit').datetimepicker({
                	format: 'YYYY-MM-DD HH:mm',
                	useCurrent: false,
                	autoclose: true,
                	minDate: timestart, 
                	maxDate: new Date(),
                });

                //EDIT OT MODAL
                $('#dt7_edit').datetimepicker({
                	format: 'YYYY-MM-DD HH:mm',
                	useCurrent: false,
                	autoclose: true,
                	minDate: timefinish,
                	maxDate: new Date(),
                });
            });	
        }
        
        if($(this).attr('id') == 'del_ot'){
            var refno   =   $(this).attr('refno')
            var ot_id   =   $(this).attr('ot_id')

			$.confirm({
                columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'top',
				animateFromElement: false,
                type: 'red',
                icon: 'fa fa-trash',
                title: 'Delete Overtime',
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

        if($(this).attr('id') == 'del_attach'){

            var refno   =   $(this).attr('refno')
            var ot_id   =   $(this).attr('ot_id')

            $.confirm({
                columnClass: 'col-xs-12 col-sm-6 col-md-4',
				containerFluid: true, // this will add 'container-fluid' instead of 'container'
				animation: 'top',
				animateFromElement: false,
                type: 'red',
                icon: 'fa fa-trash',
                title: 'Delete Attachment',
                buttons:{
                    confirm:{
                        btnClass: "btn-primary",
                        action: function(){
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "DELETE",
                                url: '/delete/attachment/'+refno+'/'+ot_id,
                                success: function(){

                                    toastr.success('Attachment deleted');

                                    location.reload();

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

    });



    $('#dateot_edit').change(function(){
        var selectedDate	=	$(this).val();
        console.log(selectedDate);

        $.ajax({
            type: 'GET',
            data: {
                dateot: selectedDate
            },
            url: '/ot_shift',
            success: function(response){
                console.log(response);
                var nextday_out	=	new Date(selectedDate);
                var txndate	=	nextday_out.getFullYear()+'-'+(nextday_out.getMonth()+1)+'-'+nextday_out.getDate();

                if(response.nextday_out == 'Y'){
                    // nextday_out.setDate(nextday_out.getDate() +1)
                    txndate	=	nextday_out.getFullYear()+'-'+(nextday_out.getMonth()+1)+'-'+(nextday_out.getDate()+1);
                }

                $('#shift_desc_edit').val(response.desc);
                $('#shift_code_edit').val(response.shift);
                
                if(response.shift == 'X'){
                    $('#timestart_edit').datetimepicker('date', selectedDate);
                    $('#timefinish_edit').datetimepicker('date', selectedDate+' '+response.out);
                }else{
                    $('#timestart_edit').datetimepicker('date', txndate+' '+response.out);
                    $('#timestart_edit').datetimepicker('minDate', txndate);
                    $('#timefinish_edit').datetimepicker('date', txndate+' '+response.out);
                    $('#timefinish_edit').datetimepicker('minDate', txndate+' '+response.out);
                }
                $('#timestart_2').datetimepicker('minDate', selectedDate);
            }
        });
    });

    $('#dt6_edit').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true,
        maxDate: new Date(),
    });

    $('#dt7_edit').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false,
        autoclose: true, 
        maxDate: new Date(),
    });


    $('#submit_ot').click(function(){
        var refno   =   $(this).attr('refno')
        console.log(refno);
        $.confirm({
            columnClass: 'col-xs-12 col-sm-6 col-md-4',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            animation: 'top',
            animateFromElement: false,
            draggable: false,
            type: 'blue',
            icon: 'fa fa-question-circle',
            title: 'Submit Overtime',
            buttons:{
                confirm:{
                    btnClass: 'btn-primary',
                    action: function(){
                        $('#submit_ot').html('<small><i class="spinner-border spinner-border-sm text-warning mr-2 pr-2"></i> Submitting ..</small>');
                        $('#submit_ot').prop('disabled', true);

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            data:{
                                refno   :   refno,
                            },
                            url: '/submit/overtime',
                            success:function(){
                                window.location.href =  '/overtime';
                            },
                            error: function(xhr, status, error){
                                var errorMessage = xhr.status + ': ' + xhr.statusText
                                console.log(status, xhr, error);
                                $.alert('Error - ' + errorMessage);

                                $('#submit_ot').html('<small>Submit overtime</small>');
                                $('#submit_ot').prop('disabled', false);
                            }
                        });
                    }
                },
                cancel:{
                    btnClass: 'btn-link'
                }
            }
        });
    });

});


$(document).mousemove(function() {
    function calculateTime() {

            var datestart = $("input[id='dt6_edit']").val();
            var datefinish = $("input[id='dt7_edit']").val();
			var splitstart = datestart .split(' ');
			var splitfinish = datefinish .split(' ');
			var date1 = new Date(splitstart[0]);
			var date2 = new Date(splitfinish[0]);
            var valuestart = splitstart[1];
            var valuestop = splitfinish[1];

            // date
   			// var date1 = new Date(datestart);
			// var date2 = new Date(datefinish);
			var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            var prod = (diffDays) * 24;

			// time
			var timeStart = new Date("01/01/2017 " + valuestart).getHours();
			var timeEnd = new Date("01/01/2017 " + valuestop).getHours();

			var hourDiff = timeEnd - timeStart;

			var timeStartm = new Date("01/01/2007 " + valuestart);
			var timeEndm = new Date("01/01/2007 " + valuestop);

			var minute1 = timeStartm.getMinutes();
			var minute2 = timeEndm.getMinutes();

			var minuteDiff = (minute2 - minute1)/60;
			// console.log(minuteDiff);
			var sum = hourDiff + minuteDiff;

			// var date1_1 = new Date($("input[id='dt6']").val());
			// var date2_2 = new Date($("input[id='dt7']").val());
			// console.log(date1_1, date2_2);

			// var msec 	= 	date2_2 - date1_1;
			// var mins 	= 	Math.floor(msec / 60000);
			// var hrs 	= 	Math.floor(mins / 60);
			// var days	= 	Math.floor(hrs / 24);
			// var yrs		= 	Math.floor(days / 365);

			// mins = mins % 60;
			// hrs = hrs % 24;
			// console.log(hrs+' '+mins);

			// var ot_hrs	=	0;
			// if(hrs > 8){
			// 	// var fsum = prod + sum - 1;
			// 	// if (isNaN(fsum)) fsum = 0;
			// 	ot_hrs 	=	hrs - 1;
			// 	$("#hours").val(ot_hrs+'.'+mins)
			// }
			// else{
			// 	// var fsum = prod + sum;
			// 	// if (isNaN(fsum)) fsum = 0;
			// 	$("#hours").val(hrs+'.'+mins)	
			// }
			
			if(sum > 8){
				var fsum = prod + sum - 1;
				if (isNaN(fsum)) fsum = 0;
				$("#hours_edit").val(fsum)
			}
			else{
				var fsum = prod + sum;
				if (isNaN(fsum)) fsum = 0;
				$("#hours_edit").val(fsum)	
			}

    }
    $("input").change(calculateTime);
    	calculateTime();
});