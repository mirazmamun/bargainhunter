(function($){
    $(function(){
        //if there is register link, make the overlay appear on click
        if($('.menu-item-register').find('a').attr('href') == bh_registration['url']){
                    var html = '<div class="modal fade" id="user-registration-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' + 
                                '<div class="modal-dialog">' + 
                                    '<div class="modal-content">' + 
                                      '<div class="modal-header">' +
                                        '<h4 class="modal-title" id="myModalLabel">Modal title</h4>' + 
                                      '</div>' +
                                      '<div class="modal-body">' + 
                                        
                                      '</div>' +
                                      '<div class="modal-footer">' +
                                        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
                                        '<button type="button" class="btn btn-primary">Save changes</button>' +
                                      '</div>' + 
                                    '</div><!-- /.modal-content -->' +
                                  '</div><!-- /.modal-dialog -->' +
                                '</div><!-- /.modal -->';
            $('body').append(html);
            
           $('.menu-item').find('a').bind('click',function(e){ 
                                                                e.preventDefault();
                                                                e.stopPropagation();
                                                               createRegistrationForm();
                                                                $('#user-registration-modal').modal('toggle');
                                                            } //end of click event handler
                                                        ); 
        }
    });
    
    //create the registration form
    var createRegistrationForm = function(){
        $.ajax({
            type:'post',
            url:bh_registration.ajaxUrl,
            data:{
             'action':'bh_registration_form'   
            },
            success:function(data){
                var JSONData = $.parseJSON(data);
                if(JSONData.response == 'success'){
                   $('#user-registration-modal').find('.modal-body').append(JSONData.data.content); 
                }
            },
            failure:function(){
                console.log('The AJAX request has failed. Plese try again later.');
            }
        });
    }
    
    //The registration form submission handler
    $('#user-registration-form').submit(function(e){
        e.preventDefault();
        //do some frontend validation
        //merge the form data with the action element for the ajax script receiver
        var formData = $('#user-registration-form').serialize();
        $.merge(formData,{'action':'bh_register_user'});
        
        $.ajax({
            type:'post',
            url:bh_registration.ajaxUrl,
            data:formData,
            success:function(data){
                //Do the processing after the form has been succefully submitted and processed in the backend
            },
            failure:function(){
                console.log('The AJAX request has failed. Plese try again later.');
            }
        });
    });
})(jQuery)


