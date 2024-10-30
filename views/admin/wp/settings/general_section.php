<?php defined('ABSPATH') OR exit('No direct script access allowed'); 

$postData = array(
	'vivahr_jobs_listing_page',
	'vivahr_business_name',
	'vivahr_business_address',
	'vivahr_business_phone',
	'vivahr_business_email',
	'vivahr_business_website',
	'vivahr_company_logo',
	'vivahr_url_slug'
);

$fieldData = array();
foreach($postData as $field)
{
	$fieldData[$field] = get_option($field);
}

$pages = get_pages();


?>
<style>
#company-information-form input, #company-information-form select{
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

.delete-logo-btn{
    text-decoration: none;
    color: #0868F6;
    font-weight: 400;
    font-size: 14px;
	border-bottom: 2px solid transparent;
}

.delete-logo-btn:hover{
	color: #0868F6;
	border-bottom: 2px solid #0868F6;
}
</style>

<div class="row">

    <div class="col-xs-12">
	
        <h4 class="section-title"><?php esc_html_e( 'Company Information', 'career-page-by-vivahr' );?></h4>
		
	</div>
	
</div>

<div class="row">

    <div class="col-xs-12">
	
        <form method="post" id="company-information-form" enctype="multipart/form-data">
            
			<div class="row ci-form-group">	
			
	            <div class="col-xs-12 col-sm-12 col-md-3">
				
					<label><?php esc_html_e( 'Job Listing Page', 'career-page-by-vivahr' );?></label>
					
			    </div>
					
			    <div class="col-xs-12 col-sm-12 col-md-5">
				
                    <select  name="company_information[listing_page]">
					
						<option value="0"><?php esc_html_e( 'Create New Page', 'career-page-by-vivahr' ); ?></option>
   
						<?php 
                        foreach ( $pages as $page ) 
						{
							$selected = (( $fieldData['vivahr_jobs_listing_page'] == intval($page->ID)) ? 'SELECTED': '' );
							
						/* 	$options = '<option '.$selected.' value="' . esc_attr(intval($page->ID)) . '">'.esc_html(sanitize_text_field($page->post_title)).'</option>';
							
							$allowed_html = [
                                'option' => [
                                    'value' => true,
                                    'selected' => true
                                ]
                            ]; 
						
                            $options_esc = wp_kses( $options, $allowed_html );
                           
						    echo $options_esc; */
							
							?>
							<option <?php echo esc_attr($selected);?> value="<?php echo esc_attr(intval($page->ID));?>"><?php echo esc_html($page->post_title);?></option>
							<?php
						}
                        ?>
						
						
				    </select>
					
	            </div> 
					
	        </div>
				
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					    
					<label><?php esc_html_e( 'Business Name', 'career-page-by-vivahr' );?></label>
				
				</div>
					
				<div class="col-xs-12 col-sm-12 col-md-5">
	                    
					<input required="required" type="text" name="company_information[b_name]" value="<?php echo esc_attr($fieldData['vivahr_business_name']);?>" />
	            
				</div> 
					
	        </div>				
				
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					    
					<label><?php esc_html_e( 'Business Address', 'career-page-by-vivahr' );?></label>
				
				</div>
					
				<div class="col-xs-12 col-sm-12 col-md-5">
 
					<input required="required" type="text" name="company_information[b_address]" value="<?php echo esc_attr($fieldData['vivahr_business_address']);?>" />
	            
				</div> 
					
	        </div>	 
				
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					    
					<label><?php esc_html_e( 'Business Phone', 'career-page-by-vivahr' );?></label>
					
				</div>
					
			    <div class="col-xs-12 col-sm-12 col-md-5">
	                    
					<input required="required" type="text" name="company_information[b_phone]" value="<?php echo esc_attr($fieldData['vivahr_business_phone']);?>" />
	                
				</div> 
					
	        </div>	

			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					
					<label><?php esc_html_e( 'Business Email', 'career-page-by-vivahr' );?></label>
				
				</div>
					
		        <div class="col-xs-12 col-sm-12 col-md-5">
	                
					<input required="required" type="text" name="company_information[b_email]" value="<?php echo esc_attr($fieldData['vivahr_business_email']);?>" />
	            
				</div> 
					
	        </div>	

			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					
					<label><?php esc_html_e( 'Website', 'career-page-by-vivahr' );?></label>
			    
				</div>
					
				<div class="col-xs-12 col-sm-12 col-md-5">
	                
					<input required="required" type="text" name="company_information[b_website]" value="<?php echo esc_attr($fieldData['vivahr_business_website']);?>" />
	            
				</div> 
					
	        </div>	

			<div class="row ci-form-group" id="logo-form-group">
				    
				<?php
				if(isset($fieldData['vivahr_company_logo']) && empty($fieldData['vivahr_company_logo']))
				{
					?>
	                <div class="col-xs-12 col-sm-12 col-md-3">
					        
						<label><?php esc_html_e( 'Logo', 'career-page-by-vivahr' );?></label>
				
				    </div>
					
					<div class="col-xs-12 col-sm-12 col-md-5">
	                        
						<input type="file" name="b_logo" id="b_logo"/>
	                    
					</div> 						
					<?php
				}
				else
				{
				
				    ?>
					<div class="col-xs-12 col-sm-12 col-md-3">
					       
						<label><?php esc_html_e( 'Logo', 'career-page-by-vivahr' );?></label>
						
					</div>
						
				    <div class="col-xs-12 col-sm-12 col-md-5">
	                        
						<img style="max-width: 173;max-height:125px;" src="<?php echo esc_url($fieldData['vivahr_company_logo']['url'])?>"/>
							
						<a id="delete-logo-btn" class="delete-logo-btn" href="javascript:void(0);"><?php esc_html_e( 'Delete logo', 'career-page-by-vivahr' );?></a>
							
	                </div> 	
						
					<?php
				}
			    ?>

		    </div>
				
			<div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
					    
					<label><?php esc_html_e( 'URL Slug', 'career-page-by-vivahr' );?></label>
				
				</div>
					
			    <div class="col-xs-12 col-sm-12 col-md-5">
	                    
					<input required="required" type="text" name="company_information[url_slug]" value="<?php echo esc_attr($fieldData['vivahr_url_slug']);?>" />
	            
				</div> 
					
	        </div>	 

            <input type="hidden" name="action" id="action" value="company_information_setup" /> 
            <?php wp_nonce_field( 'company_information_form_nonce_action', 'company_information_form_field' ); ?>
	  
	        <div class="row" style="margin-top:35px;">
	                
				<div class="col-xs-12">
		            
					<div style="float:right;">
		        	      
                        <button class="cp_info_btn vivahr-btn-primary" id="submit"><?php esc_html_e( 'Save Changes', 'career-page-by-vivahr' ) ?></button>
                        
					</div>
		            
				</div>
	            
			</div>
      
        </form>
   
    </div>

</div>
