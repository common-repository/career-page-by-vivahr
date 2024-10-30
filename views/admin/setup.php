<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<style>
.notice-dismiss {
	padding:0;
}
</style>

<div class="wrap vivahr-initial-setup" style="margin:0;">
   
    <div class="vivahr-initial-setup-box-1">
      
	    <div class="visb1-content">
            
			<div class="">
			
                <h3><?php esc_html_e( 'Career Page by VIVAHR', 'career-page-by-vivahr' );?></h3>
                <p><?php esc_html_e( 'Powered  by', 'career-page-by-vivahr' );?></p>
                <img class="lazyloaded" style="" src="<?php echo esc_url($this->vivahrAdminAssetsPath.'/images/Logo.png');?>"/>
         
		    </div>
        
            <strong>
		   
		        </br>
		        <?php esc_html_e( 'We appreciate installing the Careers Page by VIVAHR WordPress plugin.', 'career-page-by-vivahr' );?>
		        </br>
		        <?php esc_html_e( 'We have tried to make this process as seamless as possible,', 'career-page-by-vivahr' );?>
                </br>			
			    <?php esc_html_e( 'so you can start hiring and adding Careers Pages on your website in a few clicks.', 'career-page-by-vivahr' );?>
		    
			</strong>
		
            <form method="POST" action="" id="vivahr-setup-form">
           
	            <div class="row d-flex align-items-center justify-content-center">
		      
			        <div class="col-xs-12 col-sm-12 col-md-8">
                        
						<input placeholder="<?php esc_html_e('Company Name', 'career-page-by-vivahr');?>" type="text" name="vivahr_company_name" id="vivahr_company_name" value="<?php echo esc_attr( $setup_data['vivahr_company_name'] ); ?>" required />
                        <div class="field-error" id="error_vivahr_company_name"></div>
			        
					</div>
		       
			    </div>  
		   
		        <div class="row d-flex align-items-center justify-content-center">
			  
			        <div class="col-xs-12 col-sm-12 col-md-8">
					
                        <input placeholder="<?php esc_html_e('HR Email Address', 'career-page-by-vivahr');?>" type="email" name="vivahr_hr_email_address" id="vivahr_hr_email_address" value="<?php echo esc_attr( $setup_data['vivahr_hr_email_address'] ); ?>" required />
                        <div class="field-error" id="error_vivahr_hr_email_address"></div>
			  
			        </div>
		   
		        </div>  
		   
		        <div class="row d-flex align-items-center justify-content-center">
			  
			        <div class="col-xs-12 col-sm-12 col-md-8">
               
                        <select name="page_id"> 
                            
							<option value=""><?php esc_html_e( 'Create New', 'career-page-by-vivahr' ); ?></option> 
                            <?php 
                            $pages = get_pages(); 
                            foreach ( $pages as $page ) {
								?>
								<option value="<?php echo esc_attr(sanitize_text_field(intval($page->ID)));?>"><?php echo esc_html(sanitize_text_field($page->post_title));?></option>
								<?php
                                //$option = '<option value="' . intval($page->ID) . '">';
                                //$option .= esc_html(sanitize_text_field($page->post_title));
                                //$option .= '</option>';
                                //echo $option;
                            }
                            ?>
                
				        </select>
				
				    </div>		   
		        
				</div>     

                <div style="max-width: 287px;margin: 0 auto;">
		            
					<h3 style="font-weight: 700;font-size: 16px;line-height: 19px;text-align: center;color: #464A53;"><?php esc_html_e('Current VIVAHR Customers Only', 'career-page-by-vivahr');?></h3>
		      
		        </div>		

                <div class="row d-flex align-items-center justify-content-center">
		            
					<div class="col-xs-12 col-sm-12 col-md-8">
			      
				        <a target="_blank" href="<?php echo esc_url(VIVAHR_APP_URL.'settings/profile');?>" style="margin-bottom: 5px;float: left;font-weight: 500;font-size: 14px;line-height: 17px;color: #0868F6;text-decoration:none;"><?php esc_html_e('Where to find my API Client Data?', 'career-page-by-vivahr');?></a>
                        <input placeholder="<?php echo esc_html_e('Client ID', 'career-page-by-vivahr');?>" type="text" name="vivahr_client_id" id="vivahr_client_id" value="<?php echo esc_attr( $setup_data['vivahr_client_id'] ); ?>"/>
                    
					</div>
		        
				</div>                 
				
				<div class="row d-flex align-items-center justify-content-center">
		            
					<div class="col-xs-12 col-sm-12 col-md-8">
			      
                        <input placeholder="<?php echo esc_html_e('Client Secret', 'career-page-by-vivahr');?>" type="password" name="vivahr_client_secret" id="vivahr_client_secret" value="<?php echo esc_attr( $setup_data['vivahr_client_secret'] ); ?>"/>
                    
					</div>
		        
				</div>
				
				<div class="row d-flex align-items-center justify-content-center">
		            
					<div class="col-xs-12 col-sm-12 col-md-8">
			      
                        <input placeholder="<?php echo esc_html_e('Callback URL', 'career-page-by-vivahr');?>" type="text" name="vivahr_redirect_uri" id="vivahr_redirect_uri" value="<?php echo esc_attr( $setup_data['vivahr_redirect_uri'] ); ?>"/>
                    
					</div>
		        
				</div>  	
		   
		        <input type="hidden" name="action" value="vivahr_setup" />
		        <?php wp_nonce_field( 'vivahr-setup', 'vivahr_setup_nonce' ); ?>
		        <input style="max-width: 180px;margin-top: 20px;height: 44px;margin-top:20px;" type="submit" class="btn vivahr-setup-submit" id="submit" value="<?php esc_html_e( 'Get Started', 'career-page-by-vivahr' ); ?>" />		
		
		    </form>
        
		</div>
    
	</div>
    
	<div class="vivahr-initial-setup-box-2">
        
		<div class="visb2-content">
            
			<div class="">
                
				<img class="lazyloaded" style="max-width:567px; width:100%;" src="<?php echo esc_url($this->vivahrAdminAssetsPath.'/images/Big_Image.png');?>"/>
         
		    </div>
         
		    <div class="" style="max-width:567px; width:100%;margin:0 auto">
                
				<h3><?php esc_html_e( 'Save time and boost your hiring', 'career-page-by-vivahr' ); ?> <br/><?php esc_html_e( 'with VIVAHR automation', 'career-page-by-vivahr' ); ?></h3>
                <ul style="">
                    <li><?php esc_html_e( 'Your job published to 50+ job boards', 'career-page-by-vivahr' ); ?></li>
                    <li><?php esc_html_e( 'Leverage automated actions to improve speed', 'career-page-by-vivahr' ); ?></li>
                    <li><?php esc_html_e( 'Create templates for emails, scorecards, SMS and more', 'career-page-by-vivahr' ); ?></li>
                    <li><?php esc_html_e( 'Personalize hiring pipelines for each job', 'career-page-by-vivahr' ); ?></li>
                </ul>
            
			</div>
         
		    <div class="">
                
				<img style="max-width:648px; width:100%;" title="G2 Summer 2022 Badges" alt="G2 Summer 2022 Badges" src="<?php echo esc_url($this->vivahrAdminAssetsPath.'/images/G2-Summer-2022-Badges-1.png');?>" class="lazyloaded" data-ll-status="loaded">
           
		   </div>
			
        </div>
        
		<br/><br/>
		
    </div>
	
</div>