<?php defined('ABSPATH') OR exit('No direct script access allowed'); 

    $client_id = get_option('vivahr_client_id');
    $client_secret = get_option('vivahr_client_secret');
    $callback_uri = get_option('vivahr_redirect_uri');

?>

<div class="row">
    
	<div class="col-xs-12">

   	    <form method="post" id="api-key-form">
    
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-2">
	
					<label><?php esc_html_e('Client ID', 'career-page-by-vivahr');?></label>
				
				</div>

				<div class="col-xs-12 col-sm-12 col-md-5">
	          
					<input type="text" name="vivahr_client_id" value="<?php echo esc_attr( $client_id );?>" />
				
	            </div> 

	        </div>	
			
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-2">
	
					<label><?php esc_html_e('Client Secret', 'career-page-by-vivahr');?></label>
				
				</div>

				<div class="col-xs-12 col-sm-12 col-md-5">
	          
					<input type="password" name="vivahr_client_secret" value="<?php echo esc_attr( $client_secret );?>" />
				
	            </div> 

	        </div>
			
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-2">
	
					<label><?php esc_html_e('Callback URL', 'career-page-by-vivahr');?></label>
				
				</div>

				<div class="col-xs-12 col-sm-12 col-md-5">
	          
					<input type="text" name="vivahr_redirect_uri" value="<?php echo esc_attr( $callback_uri );?>" />
				
	            </div> 

	        </div>				

            <input type="hidden" name="action" id="action" value="api_key_setup"/> 
            <?php wp_nonce_field( 'api_key_form_nonce_action', 'api_key_form_field' ); ?>

	        <div class="row">

	            <div class="col-xs-12">
		        
				    <div style="margin-top:35px;">
                   
					    <span id="notice_message"></span>
			        
					</div>
		            
				</div>
	            
			</div>
			
	        <div class="row">
	            
				<div class="col-xs-12">
		         
				    <div style="float:right;">
 
                        <button class="api_key_btn" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                    
					</div>
		            
				</div>
	            
			</div>
      
        </form>
	
    </div>

</div>