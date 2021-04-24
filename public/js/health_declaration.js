$('a#HLTH_DCLTN').click(function(){

    $.confirm({
        onOpen: function(){
            $( "#txndate" ).datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1
            });
        },
        onClose: function(){
            $("#txndate").datepicker("destroy");
        },
        columnClass: 'col-xs-8 col-sm-8 col-md-5',
        containerFluid: true, // this will add 'container-fluid' instead of 'container'
        animation: 'zoom',
        animateFromElement: false,
        draggable: false,
        type: 'blue',
        icon: 'fa fa-file',
        title: 'Health Declaration Reports',
        content:
            '<form>'+
                '<div class="col-xs-12 col-sm-12 col-md-12">'+
                    '<div class="form-group row mt-2">'+
                        '<div class="col-xs-6 col-sm-6 col-md-6"><small>Date </small><span class="float-right">:</span></div>'+
                        '<div class="col-xs-6 col-sm-6 col-md-6"><input type="text" class="form-control form-control-sm text-center txndate" name="txndate" id="txndate" autocomplete="off" placeholder="YYYY-MM-DD" required /></div>'+
                    '</div>'+
                    '<div class="form-group row mt-2">'+
                        '<div class="col-xs-6 col-sm-6 col-md-6"><small>Health Declaration of </small><span class="float-right">:</span></div>'+
                        '<div class="col-xs-3 col-sm-3 col-md-3">'+
                            '<input type="radio" class="icheck-primary d-inline healthOf" name="healthOf" id="visitors" value="Visitors"> <label for="visitors"><small>Visitors</small></label>'+
                        '</div>'+
                        '<div class="col-xs-3 col-sm-3 col-md-3">'+
                            '<input type="radio" class="icheck-primary d-inline healthOf" name="healthOf" id="employees" value="Employees"> <label for="employees"><small>Employees</small></label>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</form>',
        buttons:{ 
            formSubmit:{
                text: 'Generate Report',
                btnClass: 'btn-primary',
                action: function(){
                    var txndate     =   this.$content.find('.txndate').val();
                    var healthOf    =   $('input[name="healthOf"]:checked').val();

                    if(!txndate){
                        $.alert('Date field required!');
                        return false;
                    }

                    if(!healthOf){
                        $.alert('Please select radio button!');
                        return false;
                    }

                    window.location.href = 'http://tms.ideaserv.com.ph:8080/health_reports?txndate='+txndate+'&healthOf='+healthOf
                    // window.location.href = 'http://192.168.0.64:8000/health_reports?txndate='+txndate+'&healthOf='+healthOf
                    // var url = 'http://tms.ideaserv.com.ph:8080/mntc/health_reports?txndate='+txndate+'&healthOf='+healthOf
                    // myWindow = window.open(url, '', 'width=800,height=900,scrollbars=1');
                    // myWindow.focus();   
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
});