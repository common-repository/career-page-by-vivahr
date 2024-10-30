<?php defined('ABSPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
   <div class="col-xs-12">
      <h4 class="section-title"><?php esc_html_e('Manage Job Details', 'career-page-by-vivahr');?></h4>
   </div>
</div>

<div class="vivahr-content-body general-section"></div>

<?php wp_nonce_field( 'job_details_validation', 'job_details_nonce' ); ?>

<script>
(function( $ ) {
	'use strict';

	$(window).load(function () {
		
		 var nonce = $('#job_details_nonce').val();
		
        $('.vivahr-content-body').load('admin-ajax.php', {action:'job_details_section', section:'general', nonce:nonce}, function() {
					
            $('.job-details-submit').on('click', function(e) {
                e.preventDefault();
                save_job_details();
	        });    
        });
	  })	
	  
	  function save_job_details(){
		$('.job-details-submit').prop('disabled', true);

	    var $form = $('#job-details-form');
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
			
			}
		}).always(function() {
			$('.job-details-submit').prop('disabled', false);
		});  
	}
	
	function sT(){
		var body = jQuery("html, body");
        body.stop().animate({scrollTop:0}, 500, function() { 

        }); 
	}
	
	function notify(message){
		
		$('.alert-message').html(message);
		$('.alert-message-box').css('opacity','1');
		
	    setTimeout(function(){ 
           $('.alert-message-box').css('opacity','0')
        }, 3000);
	}

})( jQuery );
</script>
