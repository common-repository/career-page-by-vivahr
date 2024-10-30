<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<div class="wrap container-fluid vivahr-settings">
    
	<div id="ref-permalink"></div>
    
	<div class="row">
        
		<div class="col-xs-12 vivahr-settings-navigation">
	        
			<ul class="vivahr-settings-menu">
	            <?php do_action('settings_menu', $settings_section); ?>
		    </ul>
        
		</div>
    
	</div>
  
    <div class="row vivahr-settings-section"> 	
        
		<div class="col-xs-12 col-sm-12 col-md-8">
            
			<div class="vivahr-settings-content <?php esc_attr( $page_class ); ?>-settings">
            
                <div class="vsc-header">
                    
					<h3 class="vsc-h-title">
					    <?php 
                            /* translators: %s: Section Title */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $section_title ) );
						?>
					</h3>

                    <div class="row">
               	        
						<div class="col-xs-12">
               		        
							<?php
                            if ($settings_section == 'job-details') 
                            {
                                ?>
                                <div class="job-details-menu">
                                    <ul class="d-flex" style="margin-block-end: 0;">
                                        <li>
                                           <a class="jdm-item active" id="general" href="javascript:void(0);"><?php esc_html_e('General', 'career-page-by-vivahr'); ?></a>
                                        </li>
                           
                                        <li>
                                           <a class="jdm-item" id="location" href="javascript:void(0);"><?php esc_html_e('Locations', 'career-page-by-vivahr'); ?></a>
                                        </li>
                         
                                        <li>
                                           <a class="jdm-item" id="department" href="javascript:void(0);"><?php esc_html_e('Departments', 'career-page-by-vivahr'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                                <?php
                            }
							elseif($settings_section == 'notifications')
							{
                                ?>
                                <div class="notifications-menu">
                                    <ul class="d-flex" style="margin-block-end: 0;">
                                        <li>
                                           <a class="jdm-item active" id="applicant-notifications" href="javascript:void(0);"><?php esc_html_e('Application Notifications', 'career-page-by-vivahr'); ?></a>
                                        </li>
                           
                                        <li>
                                           <a class="jdm-item" id="hr-notifications" href="javascript:void(0);"><?php esc_html_e('HR Notifications', 'career-page-by-vivahr'); ?></a>
                                        </li>

                                    </ul>
                                </div>
                                <?php								
							}
            	            ?>
                        </div>
                    </div>
                </div>

                <div class="vsc-body">
                    <?php do_action( 'settings_content', $settings_section );?>
                </div>

            </div>        
            
        </div>	   

	    <div class="col-xs-12 col-sm-12 col-md-4">
            <?php do_action('support_us_box'); ?>
        </div>	
	</div>
	
</div>