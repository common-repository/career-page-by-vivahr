<?php defined('ABSPATH') OR exit('No direct script access allowed');

    $postData = array(
        'vivahr_jobs_listing_page',
	    'vivahr_url_slug',
		'vivahr_career_type'
    );

    $fieldData = array();
    foreach($postData as $field){
	    $fieldData[$field] = get_option($field);
    }
	
	$pages = get_pages();

?>

<div class="row">

    <div class="col-xs-12">
	
        <h4 class="section-title"><?php esc_html_e('Company Information', 'career-page-by-vivahr');?></h4>
		
	</div>
	
</div>

<div class="row">

    <div class="col-xs-12">
	
        <form method="post" id="company-information-form-vivahr" enctype="multipart/form-data">
            
			<div class="row ci-form-group">	
			
	            <div class="col-xs-12 col-sm-12 col-md-3">
				
					<label><?php esc_html_e('Job Listing Page', 'career-page-by-vivahr' );?></label>
					
			    </div>
					
			    <div class="col-xs-12 col-sm-12 col-md-5">
				
                    <select id="ci_listing_page" name="company_information[listing_page]">
					
						<option value="0"><?php esc_html_e( 'Create New Page', 'career-page-by-vivahr' ); ?></option>   
						<?php 
                        foreach ( $pages as $page ) 
						{
							$selected = (( $fieldData['vivahr_jobs_listing_page'] == intval($page->ID)) ? 'SELECTED': '' );
							
							?>
							<option  <?php echo esc_attr($selected);?> value="<?php echo esc_attr(intval($page->ID)); ?>"><?php echo esc_html($page->post_title); ?></option>
							<?php
							
						}
                        ?>
						
				    </select>
					
	            </div> 

	        </div>
	
		    <div class="row ci-form-group">
				
	            <div class="col-xs-12 col-sm-12 col-md-3">
			   
					<label><?php esc_html_e('URL Slug', 'career-page-by-vivahr' );?></label>
					
				</div>
		
				<div class="col-xs-12 col-sm-12 col-md-5">
	            
					<input required="required" type="text" name="company_information[url_slug]" value="<?php echo esc_attr($fieldData['vivahr_url_slug']);?>" />
	                
				</div> 
			
	        </div>	 
			
			<div class="row ci-form-group">	
			
	            <div class="col-xs-12 col-sm-12 col-md-3">
				
					<label><?php esc_html_e('Career Page Type', 'career-page-by-vivahr' );?></label>
					
			    </div>
					
			    <div class="col-xs-12 col-sm-12 col-md-5">
				
                    <select id="ci_career_type" name="company_information[career_type]">
					
						<option <?php if($fieldData['vivahr_career_type'] == 'culture'){ echo 'SELECTED'; }?> value="culture"><?php esc_html_e( 'Career Page with Job List', 'career-page-by-vivahr' ); ?></option>   
						<option <?php if($fieldData['vivahr_career_type'] == 'jobs'){ echo 'SELECTED'; }?> value="jobs"><?php esc_html_e( 'Job List Only', 'career-page-by-vivahr' ); ?></option>   
						
						
				    </select>
					
	            </div> 

	        </div>

            <input type="hidden" name="action" id="action" value="company_information_setup" /> 
            <?php wp_nonce_field( 'company_information_form_nonce_action', 'company_information_form_field' ); ?>
	  
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
					
                        <button class="cp_info_btn" id="submit"><?php esc_html_e( 'Save Changes', 'career-page-by-vivahr' ); ?></button>
                    
					</div>
		        
				</div>
	            
			</div>
      
        </form>
   
    </div>
	
</div>
   
<script>
jQuery ( function($) {
	
    $(document).on('submit', '#company-information-form-vivahr', function(e) {
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
				
				$('.alert-message').html(res.message);
		$('.alert-message-box').css('opacity','1');
		
	    setTimeout(function(){ 
           $('.alert-message-box').css('opacity','0')
        }, 3000);
            }
        });
    });
	
} );
</script>