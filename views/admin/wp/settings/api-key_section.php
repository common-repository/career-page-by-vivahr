<?php defined('ABSPATH') OR exit('No direct script access allowed'); 
    
	$client_id = get_option('vivahr_client_id');
    $client_secret = get_option('vivahr_client_secret');
    $callback_uri = get_option('vivahr_redirect_uri');
	
?>
<style>
#api-key-form input{
	width:100%;
}

.ci-form-group{
	padding:15px;
	align-items:center;
}

.ci-form-group label{
	font-weight: 500;
    font-size: 14px;
    line-height: 30px;
    display: flex;
    align-items: center;
    
    color: #000000;
}

.vivahr-get-started-btn{
	background: #0868F6;
    border-radius: 6px;
    height: 55px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #ffffff;
    text-decoration: none;
    max-width: 161px;
    width: 100%;
	font-weight: 700;
    font-size: 18px;
    line-height: 22px;
    text-align: center;
    color: #FFFFFF;
	margin: 0 10px;
}

.vivahr-get-started-btn:hover{
	opacity:0.5;
	color:#fff;
}

.vivahr-demo-btn{
	border: 1px solid #0868F6;
    background: #fff;
    border-radius: 6px;
    max-width: 161px;
    width: 100%;
    height: 55px;
    justify-content: center;
    font-weight: 400;
    font-size: 16px;
    line-height: 16px;
    display: flex;
    align-items: center;
    text-align: center;
    color: #0868F6;
    text-decoration: none;
	margin: 0 10px;
}

.get_started_title{
	    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 30px;
    display: flex;
    align-items: center;
    color: #000000;
    margin: 0;
}
</style>


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
		         
				    <div style="margin-top:75px;">
                        
						<span id="notice_message"></span>
			            
					</div>
		            
				</div>
	            
			</div>
				
			<div class="col-xs-12 col-sm-12 col-md-12">
		
     		    <h3 class="get_started_title"><?php esc_html_e('Sign up for a VIVAHR account here', 'career-page-by-vivahr');?></h3>
	            <div class="row ci-form-group">
				
					<a target="_blank" class="vivahr-get-started-btn" href="https://app.vivahr.com/get-started" ><?php esc_html_e('Get Started', 'career-page-by-vivahr');?></a> 
					<a target="_blank" class="vivahr-demo-btn" href="https://vivahr.com/demo" > <img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/learn_more.png');?>"/></a>
	           
			   </div>
				
			</div>
	
	        <div class="row">
	            
				<div class="col-xs-12">
		    
					<div style="float:right;">
		      
                        <button class="api_key_btn vivahr-btn-primary" id="submit"><?php esc_html_e( 'Save Changes', 'career-page-by-vivahr' ); ?></button>
                    
					</div>
		            
				</div>
	        
			</div>
      
        </form>

    </div>
	
</div>