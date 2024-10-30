(function( $ ) {
	'use strict';
	
	$(document).on('click', '.vivahr-setup-submit', function(e) {
	
        e.preventDefault();

        $('.vivahr-setup-submit').prop('disabled', true);

	    var $form = $('#vivahr-setup-form');
	    var formData = $form.serializeArray();
		$('.field-error').html("");
		
		
		$.ajax({
			url: ajaxurl,
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if (typeof res.redirect !== 'undefined' && res.redirect) {
				window.location.replace(res.redirect);
			} else {
				
			
            $.each(res.error, function (i, item) {
                
				$('#error_'+i).append('<div style="float:left;" class="notice notice-error is-dismissible"><small>'+item+'</small></div>');
            });

				//$('#vivahr-setup-form').prepend('<div class="notice notice-error is-dismissible"><p>'+res.error+'</p></div>').fadeIn;
			}
		}).always(function() {
			$('.vivahr-setup-submit').prop('disabled', false);
		}); 
		
	});

	$(document).on('click', '#save-application-form', function(e) {
	
        e.preventDefault();

        $(this).prop('disabled', true);
 
        $.post( ajaxurl, $( "#application-form-form" ).serialize(), function( data ) {
			
			if(data['error'] == false)
			{
				sT();
               	notify(data['message']);
				$('.jdm-item#location').trigger('click');
			}
  
        }, "json");
		
		$(this).prop('disabled', false);
	  
	});
	
    $(document).on('click', '.btn-application-label', function() {
			
			var id = $(this).attr('for');
			
		    var field = $(this).attr('for').split('-');
			
			
			if($(this).hasClass('active')){

			}else{
				
				
				if(id.includes('required')){
				
				    $(this).addClass('active');
			        var optional = id.replace('required', 'optional');
					
					$("[for="+optional+"]").removeClass('active');
					
					var optional = id.replace('optional', 'required');
					 
					$("#"+optional).val("1");
					
					$("[for="+field[0]+"-on-off-label]").removeClass('active');
					$("#"+field[0]+"-on-off").val('0');
					
			    }				
				
				if(id.includes('optional')){
			
				    $(this).addClass('active');
			        var required = id.replace('optional', 'required');
					
					
					
					$("[for="+required+"]").removeClass('active');
					
					
					//var required = id.replace('required', 'optional');
					 
					$("#"+required).val("0");
					
					$("[for="+field[0]+"-on-off-label]").removeClass('active');
				    $("#"+field[0]+"-on-off").val('0');
			    }	
			}
			
	});
		
	$(document).on('click', '.btn-application-input', function() {
			
			var field = $(this).attr('for').split('-');
						
			var id = $(this).attr('for').replace('-label', '');
			
			
			
			if($(this).hasClass('active')){

			}else{
				
				$(this).toggleClass('active');
				
			if($('#'+id).val() == 0){
				$('#'+id).val('1');
				
				$("[for="+field[0]+"-required]").removeClass('active');
				$("#"+field[0]+"-required").val('0');
				
			    $("[for="+field[0]+"-optional]").removeClass('active');
			}else{
				$('#'+id).val('0');
				
				$("[for="+field[0]+"-required]").removeClass('active');
			    $("[for="+field[0]+"-optional]").removeClass('active');
			}
			}
	});
    
	/*
	 * SETTINGS PAGE
	 *
	 */
	 
	
        $(document).on('click', '.jdm-item', function() {
	
           var nonce = $('#job_details_nonce').val();
           var section = $(this).attr('id');
           
		   $('.jdm-item').removeClass('active');
		   $(this).toggleClass('active');
		   
           $('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:section, nonce:nonce}, function() {
                
				if(section == 'location'){
					
					$('.add_location_btn').click(function() {
      
                        var section = $(this).attr('id');
           
                        $('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:section, nonce:nonce}, function() {
                            $('.add-location-submit').on('click', function(e) {
                            e.preventDefault();
                                save_location();
                            });
                        });
                    }) 
				}else if (section == 'department'){
					$('.add_department_btn').click(function() {
      
                        var section = $(this).attr('id');
           
                        $('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:section, nonce:nonce}, function() {
                            $('.add-department-submit').on('click', function(e) {
                            e.preventDefault();
                                save_department();
                            });
                        });
                    }) 
				}else if (section == 'general'){
					$('.job-details-submit').on('click', function(e) {
                        e.preventDefault();
                        save_job_details();
	                });    
				}
           });
       }) 
	   
	   $(document).on('click', '.notifications-menu .jdm-item', function() {
		
		    $('.notifications-form').toggleClass('hidden');
	   });
	     
  

	$(document).on('click', '.location_delete', function() {
		
		var id = $(this).attr('id');
		var button = $(this);
		$.ajax({
		    type: "POST",
		    url: "admin-ajax.php",
		    dataType: "json",
		    data: {
		        action: 'job-details-delete-location',
				location_id: id,
				nonce: vivahr_nonce.nonce
		    },
			success: function(data) {
				if(data.error == false)
				{
					$(button).html(data.message).css({'border': '1px solid red', 'color' : 'red'});
					$( ".location_"+id ).delay( 800 ).fadeOut( 400 );
				}
				else
				{
					alert(data.message);
				}
			 	
			},
			error: function(xhr, status, error) {
			 	var err = JSON.parse(xhr.responseText);
				alert(err.message);
			}
		});
		
	});	
	
	$(document).on('click', '.edit_location', function() {
		
		var id = $(this).attr('id');
		var section = 'edit_location';
		var nonce = $('#job_details_nonce').val();
		$('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:section, location_id:id, nonce:nonce}, function() {
            $('.edit-location-submit').on('click', function(e) {
                e.preventDefault();
                edit_location();
            });
        });
		
	});	
	
	$(document).on('click', '.department_delete', function() {
		
		var id = $(this).attr('id');
		var button = $(this);
		
		$.ajax({
		    type: "POST",
		    url: "admin-ajax.php",
		    dataType: "json",
		    data: {
		        action: 'job-details-delete-department',
				department_id: id,
				nonce: vivahr_nonce.nonce
		    },
			success: function(data) {
				if(data.error == false)
				{
					$(button).html(data.message).css({'border': '1px solid red', 'color' : 'red'});
					$( ".department_"+id ).delay( 800 ).fadeOut( 400 );
				}
				else
				{
					alert(data.message);
				}
			 	
			},
			error: function(xhr, status, error) {
			 	var err = JSON.parse(xhr.responseText);
				alert(err.message);
			}
		});
		
	});	
	
	$(document).on('click', '.edit_department', function() {
		
		var id = $(this).attr('id');
		var section = 'edit_department';
		var nonce = $('#job_details_nonce').val();
		$('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:section, department_id:id, nonce:nonce}, function() {
            $('.edit-department-submit').on('click', function(e) {
                e.preventDefault();
                edit_department();
            });
        });
		
	});	
	
	function save_location(){
		
        $('.add-location-submit').prop('disabled', true);

	    var $form = $('#add-location-form');
	    var formData = $form.serializeArray();
		
		$.ajax({
			url: 'admin-ajax.php',
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if( res['code'] == 200)
			{
				sT();
               	notify(res['message']);
				$('.jdm-item#location').trigger('click');
			}
		}).always(function() {
			$('.add-location-submit').prop('disabled', false);
		});  
	}	
	
	function edit_location(){
		
        $('.edit-location-submit').prop('disabled', true);

	    var $form = $('#edit-location-form');
	    var formData = $form.serializeArray();
		
		$.ajax({
			url: 'admin-ajax.php',
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if( res['code'] == 200)
			{
				sT();
               	notify(res['message']);
				$('.jdm-item#location').trigger('click');
			}
		}).always(function() {
			$('.edit-location-submit').prop('disabled', false);
		});  
	}	
	
	function save_department(){
		
        $('.add-department-submit').prop('disabled', true);

	    var $form = $('#add-department-form');
	    var formData = $form.serializeArray();
		
		$.ajax({
			url: 'admin-ajax.php',
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if( res['code'] == 200)
			{
				sT();
               	notify(res['message']);
				$('.jdm-item#department').trigger('click');
			}
		}).always(function() {
			$('.add-department-submit').prop('disabled', false);
		});  
	}
	
	function edit_department(){
		
        $('.edit-department-submit').prop('disabled', true);

	    var $form = $('#edit-department-form');
	    var formData = $form.serializeArray();
		
		$.ajax({
			url: 'admin-ajax.php',
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if( res['code'] == 200)
			{
				sT();
               	notify(res['message']);
				$('.jdm-item#department').trigger('click');
			}
		}).always(function() {
			$('.edit-department-submit').prop('disabled', false);
		});  
	}	
	
	
	
	function notification(message){
		$('.alert-message').html(message);
	    $('#one').trigger("click");
	}
	
	$(document).on('click', '.api_key_btn', function(e) {

        e.preventDefault();
		
        $('.api_key_btn').prop('disabled', true);
		
		var $form = $('#api-key-form');
	    var formData = $form.serializeArray();
		
		$.ajax({
			url: 'admin-ajax.php',
			data: formData,
			type: 'POST'
		}).done(function(res) {
			if( res['code'] == 200)
			{
			    //notify(res['message']);
				console.log(res);
				window.location.replace( res.redirect_url );
			}
			else
			{
				notify(res['message']);
			}
		}).always(function() {
			$('.api_key_btn').prop('disabled', false);
		});  
         
    } );

    function sT(){
		var body = jQuery("html, body");
        body.stop().animate({scrollTop:0}, 500, function() { 

        }); 
	}
	
	$(document).on('submit', '#company-information-form', function(e) {
        e.preventDefault();
		
        $.ajax({
            type: 'POST',
            url: 'admin-ajax.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                $('#fupForm').css("opacity",".5");
            },
            success: function(res){
				
					if(res['logo'] === null)
					{
						//
					}
					else
					{
						$('#logo-form-group').html('<div class="col-xs-12 col-sm-12 col-md-3"><label>Logo</label></div><div class="col-xs-12 col-sm-12 col-md-5"><img style="max-width: 173px; max-height:125px;" src="'+res['logo']+'"/><a id="delete-logo-btn" class="delete-logo-btn" href="javascript:void(0);">Delete logo</a></div>');
					}
					
				sT();

               	notify(res['message']);
	            
			
                $('#ref-permalink').prepend('<div class="notice notice-info is-dismissible"><p>'+res['notice']+'</p></div>').fadeIn;
	
            }
        });
    });
	
	function notify(message){
		
		$('.alert-message').html(message);
		$('.alert-message-box').css('opacity','1');
		
	    setTimeout(function(){ 
           $('.alert-message-box').css('opacity','0')
        }, 3000);
	}
	
	$(document).on('change', '#location-country', function(e) {
		e.preventDefault();
		
		var nonce = $('#job_details_nonce').val();
		var country = $(this).val();
		
        $.post( "admin-ajax.php", { action: "filter-states", country: country, nonce:nonce }, function( data ) {
            
			$('#location-state').html("");
			$.each(data, function (i, item) {
                $('#location-state').append($('<option>', { 
                    value: item['state_short'],
                    text : item['state']
                }));
            });
			
        }, "json");
	}); 	
	
	$(document).on('click', '#delete-logo-btn', function(e) {
		 
        e.preventDefault();
		
        $.post( "admin-ajax.php", { action: "delete-logo" }, function( data ) {
            if(data['error'] == false){
			    $('.alert-message').html(data['message']);
	            $('#one').trigger("click");
				
			
			    $('#logo-form-group').html('<div class="col-xs-12 col-sm-12 col-md-3"><label>Logo</label></div><div class="col-xs-12 col-sm-12 col-md-5"><input type="file" name="b_logo" id="b_logo"/></div>')
			
		    }
        }, "json");
    }); 
	
	$(document).on('click', '#cancel-location', function(e) {
		 e.preventDefault();
		 
        $('.jdm-item#location').trigger('click');
	});	
	
	$(document).on('click', '#cancel-department', function(e) {
		 e.preventDefault();
		 
        $('.jdm-item#department').trigger('click');
	}); 
	
	$(window).load(function () {
		$('.vivahr-job-details-field').trigger('change');
	});	
	
	$(document).on('submit', '#application-notifications-form', function(e) {
        e.preventDefault();
		
        $.ajax({
            type: 'POST',
            url: 'admin-ajax.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                $('#fupForm').css("opacity",".5");
            },
            success: function(res){
				sT();
               	notify(res['message']);
            }
        });
    });
	
	$(document).on('submit', '#hr-notifications-form', function(e) {
        e.preventDefault();
		
        $.ajax({
            type: 'POST',
            url: 'admin-ajax.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                $('#fupForm').css("opacity",".5");
            },
            success: function(res){
				sT();
               	notify(res['message']);
            }
        });
    });
	
	$(document).on('change', '.vivahr-job-details-field', function(e) {
		
		var requiredFields = [];
		var requiredFieldLabels = [];
		
		wp.data.dispatch( 'core/editor' ).unlockPostSaving( 'title-lock' );

		$(".vivahr-job-details-field").each(function() {
            
		    if($(this).attr('required') == 'required' && $(this).val() == '') {
			    
				var labelFor = 'job_details_'+$(this).attr('id');
				
				var label = $("label[for=" + labelFor + "]");

				
				requiredFields.push($(this).attr('id'));
				requiredFieldLabels.push($(this).prev('label').html().replace(/[_\W]+/g, " "));
			}
			
        });
		
		if (requiredFields.length === 0) { 
		    
		    wp.data.dispatch( 'core/editor' ).unlockPostSaving( 'title-lock' );
			
			wp.data.dispatch( 'core/notices' ).removeNotice( 'title-lock' );
			
		}else{
			
			if(requiredFields.length == '1'){
				var message = requiredFieldLabels.join()+' is required field.';
			}else{
				var message = requiredFieldLabels.join()+' are required fields.';
			}

		    wp.data.dispatch( 'core/editor' ).lockPostSaving( 'title-lock' );
			
			wp.data.dispatch( 'core/notices' ).createNotice(
                'warning',
                message,
                { 
				    id: 'title-lock', 
					isDismissible: true 
				}
            );	
	    }
	}); 	

})( jQuery );