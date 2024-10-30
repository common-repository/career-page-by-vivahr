<?php defined('ABSPATH') OR exit('No direct script access allowed');

$notification = get_option('vivahr_applicant_notifications');
$hr_notification = get_option('vivahr_hr_notifications');

$hr_to = get_option('vivahr_business_email');

$hr_to = ((isset($hr_to) && empty($hr_to) ? '' : $hr_to ));

$to = '{applicant_email}';

?>


<style>

#application-notifications-form input, #hr-notifications-form input{
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

</style>

<div class="row notifications-form">
    
	<div class="col-xs-12">
      
	    <h4 class="section-title"><?php esc_html_e('Application Notification', 'career-page-by-vivahr');?></h4>
	    <p><?php esc_html_e('Email sent to applicant when application is received', 'career-page-by-vivahr');?></p>
    
	</div>
   
    <div class="col-xs-12">

   		<form method="post" id="application-notifications-form">
    
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
				    
					<label><?php esc_html_e('From', 'career-page-by-vivahr');?></label>
	                <input type="email" name="notification[from]" value="<?php echo isset($notification['from']) ? esc_attr($notification['from']) : '';?>" />
	            
				</div> 
					
				<div class="col-xs-12 col-sm-6 col-md-6">
					
					<label><?php esc_html_e('Reply to', 'career-page-by-vivahr');?></label>
	                <input type="email" name="notification[reply_to]" value="<?php echo isset($notification['reply_to']) ? esc_attr($notification['reply_to']) : '';?>" />
	            
				</div> 
					
	        </div>				
				
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
					
					<label><?php esc_html_e('To', 'career-page-by-vivahr');?></label>
	                <input readonly type="text" name="notification[to]" value="<?php echo esc_attr($to);?>" />
	           
			   </div> 
					
			    <div class="col-xs-12 col-sm-6 col-md-6">
					
					<label><?php esc_html_e('CC', 'career-page-by-vivahr');?></label>
	                <input type="email" name="notification[cc]" value="<?php echo isset($notification['cc']) ? esc_attr($notification['cc']) : '';?>" />
	            
				</div> 
					
	        </div>				
				
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
					
					<label><?php esc_html_e('Subject', 'career-page-by-vivahr');?></label>
	                <input type="text" name="notification[subject]" value="<?php echo isset($notification['subject']) ? esc_attr($notification['subject']) : '';?>" />
	            
				</div> 

	        </div>	
			
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label><?php esc_html_e('Content', 'career-page-by-vivahr');?></label>
	                <?php
					$content = isset($notification['content']) ? $notification['content'] : '';
                                    $custom_editor_id = "notification_content";
                                    $custom_editor_name = "notification_content";
                                    $args = array(
		                                'media_buttons' => false, // This setting removes the media button.
		                                'textarea_name' => $custom_editor_name, // Set custom name.
		                                'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
		                                'quicktags' => false, // Remove view as HTML button.
	                                );
                                    wp_editor( $content, $custom_editor_id, $args );
					
					?>
	            </div> 

	        </div>				
				
            <input type="hidden" name="action" id="action" value="notifications_setup"/> 
            <?php wp_nonce_field( 'notifications-form_nonce_action', 'notifications-form_field' ); ?>

	        <div class="row">
	            <div class="col-xs-12">
		            <div style="float:right;">
		        	    <button class="notifications_btn vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                    </div>
		        </div>
	        </div>
        </form>
		
		
		
    </div>
	
</div>

<div class="row notifications-form hidden">
    
	<div class="col-xs-12">
      <h4 class="section-title"><?php esc_html_e('HR Notification', 'career-page-by-vivahr'); ?></h4>
	  <p><?php esc_html_e('Email sent to HR when application is received', 'career-page-by-vivahr'); ?></p>
    </div>
   
    <div class="col-xs-12"> 
        <form method="post" id="hr-notifications-form">
		    <div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
				    <label><?php esc_html_e('From', 'career-page-by-vivahr'); ?></label>
	                <input type="email" name="hr_notification[from]" value="<?php echo isset($hr_notification['from']) ? esc_attr($hr_notification['from']) : '';?>" />
	            </div> 
					
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label><?php esc_html_e('Reply to', 'career-page-by-vivahr'); ?></label>
	                <input type="email" name="hr_notification[reply_to]" value="<?php echo isset($hr_notification['reply_to']) ? esc_attr($hr_notification['reply_to']) : '';?>" />
	            </div> 
					
	        </div>				
				
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label><?php esc_html_e('To', 'career-page-by-vivahr'); ?></label>
	                <input type="text" name="hr_notification[to]" value="<?php echo esc_attr($hr_to);?>" />
	            </div> 
					
			    <div class="col-xs-12 col-sm-6 col-md-6">
					<label><?php esc_html_e('CC', 'career-page-by-vivahr'); ?></label>
	                <input type="email" name="hr_notification[cc]" value="<?php echo isset($hr_notification['cc']) ? esc_attr($hr_notification['cc']) : '';?>" />
	            </div> 
					
	        </div>				
				
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label><?php esc_html_e('Subject', 'career-page-by-vivahr'); ?></label>
	                <input type="text" name="hr_notification[subject]" value="<?php echo isset($hr_notification['subject']) ? esc_attr($hr_notification['subject']) : '';?>" />
	            </div> 

	        </div>	
			
			<div class="row ci-form-group">
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label><?php esc_html_e('Content', 'career-page-by-vivahr'); ?></label>
	                <?php
					$content = isset($hr_notification['content']) ? $hr_notification['content'] : '';
                                    $custom_editor_id = "hr_notification_content";
                                    $custom_editor_name = "hr_notification_content";
                                    $args = array(
		                                'media_buttons' => false, // This setting removes the media button.
		                                'textarea_name' => $custom_editor_name, // Set custom name.
		                                'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
		                                'quicktags' => false, // Remove view as HTML button.
	                                );
                                    wp_editor( $content, $custom_editor_id, $args );
					
					?>
	            </div> 

	        </div>				
				
            <input type="hidden" name="action" id="action" value="hr_notifications_setup"/> 
            <?php wp_nonce_field( 'hr_notifications-form_nonce_action', 'hr_notifications-form_field' ); ?>

	        <div class="row">
	            <div class="col-xs-12">
		            <div style="float:right;">
		        	    <button class="hr_notifications_btn vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                    </div>
		        </div>
	        </div>
		</form>
	</div>
</div>