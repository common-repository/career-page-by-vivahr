<?php namespace VIVAHR\Controllers\WP;

/**
 * Admin functionality of the plugin
 * This section is intended for VIVAHR users
 * 
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers/WP
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;
use VIVAHR\Libraries\VIVAHR_WP_API;
use VIVAHR\Controllers\WP\VIVAHR_WP_Jobs;

if( !class_exists( 'VIVAHR_WP_Settings') )
{
	class VIVAHR_WP_Settings
    {   
	    public function __construct()
		{
			add_action( 'settings_menu',    array( $this, 'generate_settings_menu' ), 10, 1 );
			add_action( 'support_us_box',   array( $this, 'support_us_box'         ), 10 );
			add_action( 'settings_content', array( $this, 'load_settings_content'  ), 10, 1 );
		}
		
	    public function init()
		{	
            $core = new CoreController();
			
		    if(isset($_GET['page']))
		    {
			    $page = sanitize_text_field( $_GET['page'] );
		    }	
		
		    if(isset($_GET['section']) )
		    {
		    	$section = sanitize_text_field( $_GET['section'] );
		    }
		

            if( isset( $page ) && !empty( $page ) )
			{
				$settings_page = $page;
			}
			
			if( isset( $section ) && !empty( $section ) )
			{
				$settings_section = $section;
			}
			else
			{
				$settings_section = 'general';
			}
				
			$allowed_settings_pages = array( 'vivahr_settings' );
			$allowed_settings_sections = array( 'general', 'job-details', 'application-form', 'api-key', 'notifications' );

		    if ( in_array( $settings_page, $allowed_settings_pages, true ) && in_array( $settings_section, $allowed_settings_sections, true ) )
			{	
			   $page_class = $settings_section;

			   $section_title = $core->vivahr_config_item('menu_tabs');
			   $section_title = $section_title[$settings_section]['name'];
			   
			   require_once $core->vivahr_admin_views_path.'wp/templates/vivahr_admin_header.php';
		       require_once $core->vivahr_admin_views_path.'wp/settings/vivahr_settings_main.php';
            }
			else
			{
				$response = array( 'error' => true, 'message' => esc_html( 'Requested page/section is not allowed' ) );
			    wp_send_json( $response );		
			}
		}
		
	    /**
	     * Shows Support Us Box in Settings Page
	     * http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
		 *
	     * @since    1.0.0
	     */
		public function support_us_box()
		{
			$core = new CoreController();
			require_once $core->vivahr_admin_views_path.'wp/settings/support_us_box.php';
		}
		
		/**
	     * Returns Settings Menu tabs for not VIVAHR Customers
	     * 
		 *
	     * @since    1.0.0
	     */
		public function generate_settings_menu($section)
		{
		    $core = new CoreController();
		    $section = sanitize_text_field($section);
		    $menu_tabs = $core->vivahr_config_item('menu_tabs');
		   
		    $menu = array();
		    foreach($menu_tabs as $menu_tab => $menu_val)
		    {
		        if( $menu_tab == 'general' )
			    {
			        $menu_slug = 'admin.php?page=vivahr_settings';  
			    }
			    else
			    {
				    $menu_slug = "admin.php?page=vivahr_settings&section=".$menu_tab."";
			    }
			    
				?>
				<li id="<?php echo esc_attr($menu_tab);?>" class="vsm-item <?php echo (($section == $menu_tab) ? 'active': '');?>"><a href="<?php echo esc_url($menu_slug);?>"><?php echo esc_html($menu_val['name']);?></a></li>
				<?php
				/* $allowed_link_elements = [
                    'li' => [
                        'id' => true,
                        'class' => true
                    ],
					'a' => [
					    'href' => true
					]
                ]; 
				
                $clean_link = wp_kses( $link, $allowed_link_elements );
				
			    $menu[] = $clean_link; */
		    }
		   
		    //echo implode(' ', $menu);
		}
		
		public function load_settings_content( $section )
		{
			
			$core = new CoreController();
 
            $response = '';        
            
			$section = sanitize_text_field($section);
			
			if( !isset( $section ) || empty( $section ))
			{
				// TODO: Return error message
				return;
			}

			$settings_content_file = $core->vivahr_admin_views_path.'/wp/settings/'.$section.'_section.php';
       
            if( file_exists( $settings_content_file ) ) 
            {
                if( $section == 'job-detail')
                {
               
                }
         	    // TODO: here can be added more additional data to load inside section
	            require_once $settings_content_file;
            }
            else
            {
         	    $response = array('error' => true, 'message' => 'File '.esc_html( $section ).'_section.php is missing in '.$core->vivahr_admin_views_path.'/wp/settings/');

         	    echo wp_json_encode( $response, JSON_UNESCAPED_SLASHES );
            }
			
		}
		
		/**
	     * Load Job Details Section
		 *
		 * @section General
		 * @section Locations
		 * @section Departments
	     *
	     * @since    1.0.0
	     */
		public function load_job_details_section()
		{
			check_ajax_referer( 'job_details_validation', 'nonce' );
			
			$response = '';
			$additional = array();
			
			$action = sanitize_text_field( $_POST['action'] );
			$section = sanitize_text_field( $_POST['section'] );
			
			$allowed_actions = array( 'job_details_section' );
			$allowed_sections = array( 'general', 'location', 'department', 'add_location', 'add_department', 'edit_location', 'edit_department');

            if ( in_array( $action, $allowed_actions, true ) && in_array( $section, $allowed_sections, true ) )
			{	
                if( $action == 'job_details_section' )
				{
					if($section == 'edit_location')
					{
						$additional['location_id'] = intval($_POST['location_id']);
					}
					elseif($section == 'edit_department')
					{
						$additional['department_id'] = intval($_POST['department_id']);
					}
					
					$section = "load_section_$section";

					$this->$section($additional);
				}
            }
			else
			{
				$response = array( 'error' => true, 'message' => esc_html( $section ) );
			    wp_send_json( $response, JSON_UNESCAPED_SLASHES );		
			}
		}
		
		/**
		 * Job Details - General Settings
	     * http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
		 *
	     * @since    1.0.0
	     */
        private function load_section_general($additional = '')
		{
	        $job_details = get_option('vivahr_job_details');
		
		    ?>
         
			<div class="row">
         	    <div class="col-xs-12">
         		    <form method="post" id="job-details-form">
                        <table class="table job-details-table" id="job-details-table">
                            <thead>
                                <tr>
                                    <th style="width:25%"><?php esc_html_e('Job Detail', 'career-page-by-vivahr');?></th>
                                    <th style="width:60%"><?php esc_html_e('Options', 'career-page-by-vivahr');?></th>
                                    <th style="width:15%"><?php esc_html_e('Required', 'career-page-by-vivahr');?></th>
                                </tr>
                            </thead>
                  
                            <tbody>
                                <?php 
								global $wpdb;
								
								$allowed_tables = array( 
								    $wpdb->prefix.'vivahr_location',
									$wpdb->prefix.'vivahr_department', 
									$wpdb->prefix.'vivahr_position_type', 
									$wpdb->prefix.'vivahr_salary_type', 
									$wpdb->prefix.'vivahr_skill_level', 
								);
								
                                foreach($job_details as $job_detail => $job_value)
                                {
									$job_detail = sanitize_text_field($job_detail);
									
                                    if($job_detail != 'vivahr_salary_range')
									{	
										
										$table = $wpdb->prefix . $job_detail;
										$status = 1;
										if(in_array($table, $allowed_tables))
										{
										    $results = $wpdb->get_results( $wpdb->prepare( "SELECT name FROM %i WHERE status = %d", $table, $status ) );
										
										    $options = array();
										    foreach($results as $result)
										    {
											    $options[] = esc_html($result->name);
										    }
										
		                                    $options = implode(', ', $options); 
										}
										
								    }
									else
									{
										$options = 'From, To';
									}
	                                ?>

                                    <tr>
									
                                        <td>
										
										    <?php 
											/* translators: %s: Job Details Field Name */
                                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $job_value['field_title'] ) );
											?>
											
										</td>
										
                                        <td>
										    
											<?php 
											/* translators: %s: Job Details Field Name Option */
                                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $options ) );
											?>
										
										</td>
										
                                        <td>
										    <input type="checkbox" name="<?php echo esc_attr($job_detail);?>" <?php checked( isset( $job_value['required'] ) && intval($job_value['required']) == 1 ); ?> />
										</td>
                                    </tr>
									
                                <?php 
                                }
                                ?>
                            </tbody>
                        </table>

                        <input type="hidden" name="action" id="action" value="job_details_setup" /> 
                        <?php wp_nonce_field( 'job_details_form_nonce_action', 'job_details_form_field' ); ?>

                        <div class="row">
	                        <div class="col-xs-12">
		                        <div class="fr" style="margin-top:35px;">
               
			                        
                                    <button class="job-details-submit vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr') ?></button>
            
			                    </div>
		                    </div>
	                    </div>
                    </form>
                </div>
            </div>			
			<?php			
			die;
		}

		/**
		 * Job Details - Location Settings
	     * http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
		 *
	     * @since    1.0.0
	     */
		private function load_section_location($additional = '')
		{
	        global $wpdb;
			$status = 1;
			$locations = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, address, city, country, state, zip_code FROM ".$wpdb->prefix."vivahr_location WHERE deleted_at IS NULL AND status = %d", $status ));
		    ?>
            <div class="row">
         	    <div class="col-xs-12">
				    <a class="add_location_btn" id="add_location" href="javascript:void(0);"><span>+</span> <?php esc_html_e('Add Location', 'career-page-by-vivahr');?></a>
				</div>
			</div>
			
            <div class="row">
         	    <div class="col-xs-12">
         		    <table class="table job-details-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Name', 'career-page-by-vivahr');?></th>
                                <th><?php esc_html_e('Address', 'career-page-by-vivahr');?></th>
                                <th><?php esc_html_e('City, State, Zip', 'career-page-by-vivahr');?></th>
                                <th><?php esc_html_e('Jobs', 'career-page-by-vivahr');?></th>
                                <th><?php esc_html_e('Options', 'career-page-by-vivahr');?></th>
                      
                            </tr>
                        </thead>
                  
                        <tbody>
                            <?php 
                            foreach($locations as $location)
                            {
	                        ?>
                                <tr class="location_<?php echo intval($location->id);?>">
                                    <td><?php echo esc_html($location->name);?></td>
                                    <td><?php echo esc_html($location->address);?></td>
                                    <td><?php echo esc_html($location->city);?>, <?php echo esc_html($location->state);?> <?php echo esc_html($location->zip_code);?></br><?php echo esc_html($location->country);?></td>
                                    <td></td>
                                    <td>
									    <a class="location_delete" href="javascript:void(0);" id="<?php echo esc_attr(intval($location->id));?>"><?php esc_html_e('Delete', 'career-page-by-vivahr');?></a>
										<a class="edit_location" href="javascript:void(0);" id="<?php echo esc_attr(intval($location->id));?>"><?php esc_html_e('Edit', 'career-page-by-vivahr');?></a>
									</td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                    </table>
					<?php wp_nonce_field( 'location_delete_nonce_action', 'location_delete_field' ); ?>
                </div>
            </div>
            <?php
			die;
		}
		
		/**
		 * Load Add Location Form
		 *
		 * @since    1.0.0
		 */
		private function load_section_add_location($additional = '')
		{
			global $wpdb;
			
			$countries_table = $wpdb->prefix . 'vivahr_countries';
			
			$countries = $wpdb->get_results( $wpdb->prepare( "SELECT 
			country, full_name
			FROM %i", $countries_table ) );
			
			$states_table = $wpdb->prefix . 'vivahr_states';
			
			$states = $wpdb->get_results( $wpdb->prepare( "SELECT 
			state_short, state
			FROM %i", $states_tables ) );
			
			
			?>
			<form method="post" id="add-location-form">
    
	            <h3><?php esc_html_e('Add Location', 'career-page-by-vivahr');?></h3>
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Name', 'career-page-by-vivahr');?></label>
		                  <input type="text" name="location[name]"/>
	                   </div>
	                </div> 
	            </div>	 

	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                    <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                        <label><?php esc_html_e('Address', 'career-page-by-vivahr');?></label>
		                    <input type="text" name="location[address]"/>
	                    </div>
	                </div> 
	            </div>	  
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('City', 'career-page-by-vivahr');?></label>
		                  <input type="text" name="location[city]"/>
	                   </div>
	                </div>  
		 
		            <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Country', 'career-page-by-vivahr');?></label>
						  <select name="location[country]" id="location-country">
						     <option value=""><?php esc_html_e('Select', 'career-page-by-vivahr');?></option>
							 <?php
							 if(isset($countries))
							 {
								 foreach($countries as $country)
								 {
									/* $link = '<option value="'.esc_attr($country->full_name).'">'.esc_html($country->full_name).'</option>';
				
				                    $allowed_option_data = [
                                        'option' => [
                                            'value' => true
                                        ]
                                    ]; 
				
                                    echo wp_kses( $link, $allowed_option_data ); */
									
									?>
									<option value="<?php echo esc_attr($country->full_name);?>"><?php echo esc_html($country->full_name);?></option>
									<?php
								 }
							 }
							 
							 ?>
						  </select>
	                   </div>
	                </div> 
	            </div>	 

	            <div class="row">
	                <div class="col-xs-12 col-md-5">
	                    <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                        <label><?php esc_html_e('State', 'career-page-by-vivahr');?></label>
							<select name="location[state]" id="location-state">
						     <option value=""><?php esc_html_e('Select', 'career-page-by-vivahr');?></option>
							 <?php
							 if(isset($states))
							 {
								foreach($states as $state)
								{
									/* $link = '<option value="'.esc_attr( $state->state ).'">'.esc_html( $state->state ).'</option>';
				
				                    $allowed_option_data = [
                                        'option' => [
                                            'value' => true
                                        ]
                                    ]; 
				
                                    echo wp_kses( $link, $allowed_option_data ); */
									?>
									<option value="<?php echo esc_attr( $state->state );?>"><?php echo esc_html( $state->state );?></option>
									<?php
								}
							 }
							 
							 ?>
						  </select>
	                    </div>
	                </div>  
		 
		            <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Zip Code', 'career-page-by-vivahr');?></label>
		                  <input type="text" name="location[zip_code]"/>
	                   </div>
	                </div> 
	            </div>	  
	  

                <input type="hidden" name="action" id="action" value="add_location_setup" /> 
                <?php wp_nonce_field( 'add_location_form_nonce_action', 'add_location_form_field' ); ?>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div style="margin-top:35px;">
                            <span id="notice_message"></span>
			            </div>
		            </div>
	            </div>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div class="fr d-flex" style="gap: 10px;">
		        	        <button class="vivahr-btn-default" id="cancel-location"><?php esc_html_e('Cancel', 'career-page-by-vivahr'); ?></button>
                            <button class="add-location-submit vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                        </div>
		            </div>
	            </div>
      
            </form>
			<?php
			
			die;
		}
		
		/**
		 * Load Add Location Form
		 *
		 * @since    1.0.0
		 */
		private function load_section_edit_location($additional = '')
		{
			global $wpdb;
			
			$location_id = (int)$additional['location_id'];
			$table = $wpdb->prefix . 'vivahr_location';
			
			$location = $wpdb->get_row( $wpdb->prepare( "SELECT 
			id, name, address, city, country, state, zip_code 
			FROM %i 
			WHERE id = %d", $table, $location_id ) );
			
			$countries_table = $wpdb->prefix . 'vivahr_countries';
			
			$countries = $wpdb->get_results( $wpdb->prepare( "SELECT 
			country, full_name
			FROM %i", $countries_table ) );
			
			$states_table = $wpdb->prefix . 'vivahr_states';
			
			$states = $wpdb->get_results($wpdb->prepare( "SELECT 
			state_short, state
			FROM %i", $states_table ));
          
			?>
			<form method="post" id="edit-location-form">
    
	            <h3><?php esc_html_e('Edit Location', 'career-page-by-vivahr'); ?></h3>
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Name', 'career-page-by-vivahr'); ?></label>
		                  <input type="text" name="location[name]" value="<?php echo esc_attr($location->name);?>"/>
	                   </div>
	                </div> 
	            </div>	 

	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                    <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                        <label><?php esc_html_e('Address', 'career-page-by-vivahr'); ?></label>
		                    <input type="text" name="location[address]" value="<?php echo esc_attr($location->address);?>"/>
	                    </div>
	                </div> 
	            </div>	  
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('City', 'career-page-by-vivahr'); ?></label>
		                  <input type="text" name="location[city]" value="<?php echo esc_attr($location->city);?>"/>
	                   </div>
	                </div>  
		 
		            <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Country', 'career-page-by-vivahr'); ?></label>
		                 
						  <select name="location[country]" id="location-country">
						     <option value=""><?php esc_html_e('Select', 'career-page-by-vivahr');?></option>
							 <?php
							 if(isset($countries))
							 {
								 foreach($countries as $country)
								 {
									 $selected = (($location->country == $country->full_name) ? 'SELECTED' : '');
									 
									 /* $link = '<option '.esc_attr( $selected ).' value="'.esc_attr( $country->full_name ).'">'.esc_html( $country->full_name ).'</option>';
				
				                        $allowed_option_data = [
                                            'option' => [
                                                'SELECTED' => true,
                                                'value' => true
                                            ]
                                        ]; 
				
                                        echo wp_kses( $link, $allowed_option_data ); */
										
										?>
										<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $country->full_name );?>"><?php echo esc_html( $country->full_name );?></option>
										<?php
									 
								 }
							 }
							 
							 ?>
						  </select>
	                   </div>
	                </div> 
	            </div>	 

	            <div class="row">
				
	                <div class="col-xs-12 col-md-5">
	                    <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                        <label><?php esc_html_e('State', 'career-page-by-vivahr'); ?></label>
							<select name="location[state]" id="location-state">
						        <option value=""><?php esc_html_e('Select', 'career-page-by-vivahr');?></option>
							    <?php
							    if(isset($states))
							    {
							        foreach($states as $state)
								    {
										$selected = (($location->state == $state->state_short) ? 'SELECTED' : '');

										/* $link = '<option '.esc_attr( $selected ).' value="'.esc_attr( $state->state_short ).'">'.esc_html( $state->state ).'</option>';
				
				                        $allowed_option_data = [
                                            'option' => [
                                                'SELECTED' => true,
                                                'value' => true
                                            ]
                                        ]; 
				
                                        echo wp_kses( $link, $allowed_option_data ); */
										?>
										<option <?php echo esc_attr( $selected );?> value="<?php echo esc_attr( $state->state_short );?>"><?php echo esc_html( $state->state );?></option>
										<?php
								    }
							    }
							    ?>
						    </select>
	                    </div>
	                </div>  
		 
		            <div class="col-xs-12 col-md-5">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Zip Code', 'career-page-by-vivahr');?></label>
		                  <input type="text" name="location[zip_code]" value="<?php echo esc_attr($location->zip_code);?>"/>
	                   </div>
	                </div> 
	            </div>	  

                <input type="hidden" name="location[id]" id="location[id]" value="<?php echo esc_attr($location->id);?>" /> 
                <input type="hidden" name="action" id="action" value="edit_location_setup" /> 
                <?php wp_nonce_field( 'edit_location_form_nonce_action', 'edit_location_form_field' ); ?>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div style="margin-top:35px;">
                            <span id="notice_message"></span>
			            </div>
		            </div>
	            </div>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div class="fr d-flex" style="gap: 10px;">
		        	        <button class="vivahr-btn-default" id="cancel-location"><?php esc_html_e('Cancel', 'career-page-by-vivahr'); ?></button>
                            <button class="edit-location-submit vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                        </div>
		            </div>
	            </div>
      
            </form>
			<?php
			
			die;
		}

		/**
		 * Job Details - Department Settings
	     * http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
		 *
	     * @since    1.0.0
	     */
		private function load_section_department($additional = '')
		{
	        global $wpdb;
			$status = 1;
		 	$departments_table = $wpdb->prefix . 'vivahr_department';
			$departments = $wpdb->get_results( $wpdb->prepare( "SELECT id, name FROM %i WHERE deleted_at IS null AND status = %d", $departments_table, $status));
		    ?>
		    
		    <div class="row">
         	    <div class="col-xs-12">
				    <a class="add_department_btn" id="add_department" href="javascript:void(0);"><span>+</span> <?php esc_html_e('Add Department', 'career-page-by-vivahr'); ?></a>
				</div>
			</div>
			
            <div class="row">
         	    <div class="col-xs-12">
         		    <table class="table job-details-table" style="width: 100%;">
                        <thead>
						    <tr>
						        <th style="width:10%"><?php esc_html_e('Name', 'career-page-by-vivahr'); ?></th>
                                <th style="width:10%"><?php esc_html_e('Options', 'career-page-by-vivahr'); ?></th>
							</tr>
						</thead>
                        <tbody>
                            <?php 
                            foreach($departments as $department)
                            {
	                            ?>
                                <tr class="department_<?php echo esc_attr(intval($department->id));?>">
									<td><?php echo esc_html($department->name);?></td>
                                    <td>
									    <a class="department_delete" href="javascript:void(0);" id="<?php echo esc_attr(intval($department->id));?>" ><?php esc_html_e('Delete', 'career-page-by-vivahr'); ?></a>
										<a class="edit_department" href="javascript:void(0);" id="<?php echo esc_attr(intval($department->id));?>" ><?php esc_html_e('Edit', 'career-page-by-vivahr'); ?></a>
									</td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                    </table>
				</div>
            </div>
		    
			<?php
		    die;
		}

		/**
		 * Load Add Location Form
		 *
		 * @since    1.0.0
		 */
		private function load_section_add_department($additional = '')
		{
			?>
			<form method="post" id="add-department-form">
    
	            <h3><?php esc_html_e('Add Department', 'career-page-by-vivahr'); ?></h3>
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Name', 'career-page-by-vivahr'); ?></label>
		                  <input type="text" name="department[name]"/>
	                   </div>
	                </div> 
	            </div>	 

                <input type="hidden" name="action" id="action" value="add_department_setup" /> 
                <?php wp_nonce_field( 'add_department_form_nonce_action', 'add_department_form_field' ); ?>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div style="margin-top:35px;">
                            <span id="notice_message"></span>
			            </div>
		            </div>
	            </div>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div class="fr d-flex" style="gap: 10px;">
		        	        <button class="vivahr-btn-default" id="cancel-department"><?php esc_html_e('Cancel', 'career-page-by-vivahr'); ?></button>
                            <button class="add-department-submit vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
                        </div>
		            </div>
	            </div>
      
            </form>
			<?php
			
			die;
		}
		
		/**
		 * Load Edit Department Form
		 *
		 * @since    1.0.0
		 */
		private function load_section_edit_department($additional = '')
		{
			global $wpdb;
			
			$department_id = (int)$additional['department_id'];
			$table = $wpdb->prefix . 'vivahr_department';
			
			$department = $wpdb->get_row( $wpdb->prepare( "SELECT 
			id, name
			FROM %i 
			WHERE id = %d", $table, $department_id ) );
          
			?>
			<form method="post" id="edit-department-form">
    
	            <h3><?php esc_html_e('Edit Department', 'career-page-by-vivahr'); ?></h3>
	  
	            <div class="row">
	                <div class="col-xs-12 col-md-10">
	                   <div class="" style="display: flex; flex-direction: column;margin-bottom:35px;">
	                      <label><?php esc_html_e('Name', 'career-page-by-vivahr'); ?></label>
		                  <input type="text" name="department[name]" value="<?php echo esc_attr($department->name);?>"/>
	                   </div>
	                </div> 
	            </div>	 

	          

                <input type="hidden" name="department[id]" id="department[id]" value="<?php echo esc_attr(intval($department->id));?>" /> 
                <input type="hidden" name="action" id="action" value="edit_department_setup" /> 
                <?php wp_nonce_field( 'edit_department_form_nonce_action', 'edit_department_form_field' ); ?>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div style="margin-top:35px;">
                            <span id="notice_message"></span>
			            </div>
		            </div>
	            </div>
	  
	            <div class="row">
	                <div class="col-xs-12">
		                <div class="fr d-flex" style="gap: 10px;">
		        	        <button class="vivahr-btn-default" id="cancel-department"><?php esc_html_e('Cancel', 'career-page-by-vivahr') ?></button>
                            <button class="edit-department-submit vivahr-btn-primary" id="submit"><?php esc_html_e('Save Changes', 'career-page-by-vivahr') ?></button>
                        </div>
		            </div>
	            </div>
      
            </form>
			<?php
			
			die;
		}
		
/*
 **************************************************************************************************
 **************************************************************************************************
 MANAGE SETTINGS FORMS
 
 1. JOB DETAILS -> SAVE LOCATIONS
 2. JOB DETAILS -> EDIT LOCATIONS
 3. JOB DETAILS -> DELETE LOCATIONS

 4. JOB DETAILS -> SAVE DEPARTMENTS
 5. JOB DETAILS -> EDIT DEPARTMENTS
 6. JOB DETAILS -> DELETE DEPARTMENT

 4. JOB DETAILS -> SAVE GENERAL JOB DETAILS
 **************************************************************************************************
 */
	
		/**
		 * SAVE LOCATIONS
	     * Saves Add Location Form
	     *
	     * @since    1.0.0
	     */
		public function add_location_setup()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'add_location_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['add_location_form_field'] ) ), 'add_location_form_nonce_action' ) ) {
                    return;
                }
				
				// TODO: Validation process
				
                global $wpdb;
             
	            $table = $wpdb->prefix . 'vivahr_location';
	            
                $data = array( 
	            	'name'     => sanitize_text_field( $_POST['location']['name'] ),
					'address'  => sanitize_text_field( $_POST['location']['address'] ),
	            	'city'     => sanitize_text_field( $_POST['location']['city'] ), 
	            	'country'  => sanitize_text_field( $_POST['location']['country'] ),
	            	'state'    => sanitize_text_field( $_POST['location']['state'] ),
	            	'zip_code' => intval($_POST['location']['zip_code']),
					'created_at' => current_time( 'mysql' ),
					'updated_at' => current_time( 'mysql' ) 
	            );
				
                $format = array('%s','%s','%s','%s','%s','%d','%s','%s');
                $wpdb->insert( $table, $data, $format );
                
				$location_id = $wpdb->insert_id;
				
	            if( $location_id != '')
				{
					$response = array( 'code' => 200, 'error' => false, 'message' => 'Location was successfully saved!');
				}
				else
				{
					$response = array( 'code' => 404, 'error' => true, 'message' => 'Error Occured!');
				}
		    }

			wp_send_json( $response );
		}		
		
		/**
		 * EDIT LOCATIONS
	     * Edits Location row in DB
	     *
	     * @since    1.0.0
	     */
		public function edit_location_setup()
		{
			$response = '';
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'edit_location_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edit_location_form_field'] ) ), 'edit_location_form_nonce_action' ) ) {
                    return;
                }
				
				// TODO: Validation process
				
                global $wpdb;

	            $table = $wpdb->prefix . 'vivahr_location';
	            
                $data = array( 
	            	'name'     => sanitize_text_field( $_POST['location']['name'] ), 
	            	'address'  => sanitize_text_field( $_POST['location']['address'] ), 
	            	'city'     => sanitize_text_field( $_POST['location']['city'] ), 
	            	'country'  => sanitize_text_field( $_POST['location']['country'] ),
	            	'state'    => sanitize_text_field( $_POST['location']['state'] ),
	            	'zip_code' => intval( $_POST['location']['zip_code']),
					'updated_at' => current_time( 'mysql' ) 
	            );
				
				$where = [ 'id' => (int)$_POST['location']['id'] ];
				
				$format = array('%s','%s','%s','%s','%s','%d','%s','%d');
				$updated = $wpdb->update( $table, $data, $where, $format );

                if ( false === $updated ) 
				{
                    $response = array( 'code' => 404, 'error' => true, 'message' => 'Error Occured!');
                } 
				else 
				{
					$response = array( 'code' => 200, 'error' => false, 'message' => 'Location was successfully saved!');
                }
		    }

			wp_send_json( $response );
		}
		
		/**
		 * DELETE LOCATIONS
	     * Deletes Job Locations from DB
	     *
	     * @since    1.0.0
	     */
		public function delete_location()
		{
		    $response = '';	
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
			{
							
			    // Check for nonce security      
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'vivahr_nonce' ) ) {
                    $response = array('error' => true, 'message' => 'Nonce verifcation error!');
			    	wp_send_json( $response );
                }
			
			    if( !isset( $_POST['location_id'] ) || is_numeric( $_POST['location_id'] ) == false )
			    {
                    $response = array('error' => true, 'message' => 'Location ID error!');
			    	wp_send_json( $response );
                }			
			
			    if( !isset( $_POST['action']) || empty( $_POST['action'] ) || $_POST['action'] != 'job-details-delete-location' )
			    {
                    $response = array('error' => true, 'message' => 'Action error!');
			    	wp_send_json( $response );
                }
            
			    global $wpdb;

	            $table = $wpdb->prefix . 'vivahr_location';
	            
                $data = array( 
					'deleted_at' => current_time( 'mysql' ) 
	            );
				
				$where = [ 'id' => (int)$_POST['location_id'] ];
				
				$format = array('%s', '%d');
				
				$updated = $wpdb->update( $table, $data, $where, $format );
				
				if( $updated == '1' )
			    {
			    	$response = array( 'error' => false, 'message' => esc_html('Deleted'));
					wp_send_json( $response );
			    }
			    else
			    {
			    	$response = array( 'error' => false, 'message' => esc_html('Error!'));
					wp_send_json( $response );
			    }
			}

            die;			
		}
		
	    /**
		 * SAVE DEPARTMENTS
	     * Saves Add Location Form
	     *
	     * @since    1.0.0
	     */
		public function add_department_setup()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'add_department_setup' ) 
			{				
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['add_department_form_field'] ) ), 'add_department_form_nonce_action' ) ) {
                    return;
                }
				
				// TODO: Validation process
				
                global $wpdb;
                
	            $table = $wpdb->prefix . 'vivahr_department';
	            
                $data = array( 
	            	'name'       => sanitize_text_field($_POST['department']['name']),
                    'created_at' => current_time('mysql'),					
					'updated_at' => current_time('mysql')
	            );
				
                $format = array('%s', '%s', '%s');
                $wpdb->insert( $table, $data, $format );
                
				$department_id = $wpdb->insert_id;
				
	            if( $department_id != '')
				{
					$response = array( 'code' => 200, 'error' => false, 'message' => 'Department was successfully saved!');
				}
				else
				{
					$response = array( 'code' => 404, 'error' => true, 'message' => 'Error Occured!');
				}
		    }

			wp_send_json( $response );
		}
		
		/**
		 * EDIT DEPARTMENT
	     * Edits Departments row in DB
	     *
	     * @since    1.0.0
	     */
		public function edit_department_setup()
		{
			$response = '';
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'edit_department_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edit_department_form_field'] ) ), 'edit_department_form_nonce_action' ) ) {
                    return;
                }
				
				// TODO: Validation process
				
                global $wpdb;

	            $table = $wpdb->prefix . 'vivahr_department';
	            
                $data = array( 
	            	'name'       => sanitize_text_field( $_POST['department']['name'] ), 
					'updated_at' => current_time( 'mysql' )
	            );
				
				$where = [ 'id' => (int)$_POST['department']['id'] ];
				
				$format = array('%s','%s','%d');
				$updated = $wpdb->update( $table, $data, $where, $format );

                if ( false === $updated ) 
				{
                    $response = array( 'code' => 404, 'error' => true, 'message' => 'Error Occured!');
                } 
				else 
				{
					$response = array( 'code' => 200, 'error' => false, 'message' => 'Department was successfully saved!');
                }
		    }

			wp_send_json( $response );
		}
		
		/**
		 * DELETE DEPARTMENT
	     * Deletes Job department from DB
	     *
	     * @since    1.0.0
	     */
		public function delete_department()
		{
		    $response = '';	
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
			{
			    // Check for nonce security      
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'vivahr_nonce' ) ) {
                    $response = array('error' => true, 'message' => 'Nonce verifcation error!');
			    	wp_send_json( $response );
                }
			
			    if( !isset( $_POST['department_id'] ) || is_numeric( $_POST['department_id'] ) == false )
			    {
                    $response = array('error' => true, 'message' => 'Department ID error!');
			    	wp_send_json( $response );
                }			
			
			    if( !isset( $_POST['action']) || empty( $_POST['action'] ) || $_POST['action'] != 'job-details-delete-department' )
			    {
                    $response = array('error' => true, 'message' => 'Action error!');
			    	wp_send_json( $response );
                }
            
				global $wpdb;
				
				$table = $wpdb->prefix . 'vivahr_department';

                $data = array( 
					'deleted_at' => current_time( 'mysql' ) 
	            );
				
				$format = array('%s', '%d');
				
				$where = [ 'id' => (int)$_POST['department_id'] ];
				
				$deleted = $wpdb->update( $table, $data, $where, $format );
				
				if( $deleted == '1' )
			    {
			    	$response = array( 'error' => false, 'message' => 'Deleted');

			    }
			    else
			    {
			    	$response = array( 'error' => false, 'message' => 'Error!');
					
			    }
				
				wp_send_json( $response );
			}
			
			die;	
		}
		
		/**
		 * SAVE GENERAL JOB DETAILS
	     * Saves Job Details Form
	     *
	     * @since    1.0.0
	     */
		public function job_details_setup()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'job_details_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['job_details_form_field'] ) ), 'job_details_form_nonce_action' ) ) {
                    return;
                }
				
				// TODO: Validation process
				
				$vivahr_job_details = array(
			        'vivahr_position_type' => array(
				        'field_title' => 'Position Type',
				        'required'    => (isset($_POST['vivahr_position_type']) && $_POST['vivahr_position_type'] == 'on' ? 1 : 0)
				    ),			    
				    'vivahr_skill_level' => array(
				        'field_title' => 'Skill Level',
				        'required'    => (isset($_POST['vivahr_skill_level']) && $_POST['vivahr_skill_level'] == 'on' ? 1 : 0)
				    ),				
				    'vivahr_salary_type' => array(
				        'field_title' => 'Salary Type',
				        'required'    => (isset($_POST['vivahr_salary_type']) && $_POST['vivahr_salary_type'] == 'on' ? 1 : 0)
				    ),				
				    'vivahr_salary_range' => array(
				        'field_title' => 'Salary Range',
				        'required'    => (isset($_POST['vivahr_salary_range']) && $_POST['vivahr_salary_range'] == 'on' ? 1 : 0)
				    ),				
				    'vivahr_department' => array(
				        'field_title' => 'Department',
				        'required'    => (isset($_POST['vivahr_department']) && $_POST['vivahr_department'] == 'on' ? 1 : 0)
				    ),				
				    'vivahr_location' => array(
				        'field_title' => 'Location',
				        'required'    => (isset($_POST['vivahr_location']) && $_POST['vivahr_location'] == 'on' ? 1 : 0)
				    ),
			    );
			
			   update_option( 'vivahr_job_details', $vivahr_job_details);
				
			   do_action( 'update_option', 'vivahr_job_details' );
				
		    }
			
			$response = array( 'code' => 200, 'error' => false, 'message' => 'Job Details Updated Successfully!');
			
			wp_send_json( $response );
		} 
		
		/**
		 * COMPANY INFORMATION SETUP
	     * Saves/updates company information data
	     *
	     * @since    1.0.0
	     */
		public function company_information_setup()
		{
			$response = '';
			$error = false;
		
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'company_information_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['company_information_form_field'] ) ), 'company_information_form_nonce_action' ) ) {
				   $error = true;
		           return;
                }

            	// TODO: DO VALIDATIOn PROCESS HERE	
				
				
				$postData = array(
				    'vivahr_jobs_listing_page' => (int)$_POST['company_information']['listing_page'],
					'vivahr_business_name'     => sanitize_text_field( $_POST['company_information']['b_name']    ),
					'vivahr_business_address'  => sanitize_text_field( $_POST['company_information']['b_address'] ),
					'vivahr_business_phone'    => sanitize_text_field( $_POST['company_information']['b_phone']     ),
					'vivahr_business_email'    => sanitize_text_field( $_POST['company_information']['b_email']     ),
					'vivahr_business_website'  => sanitize_text_field( $_POST['company_information']['b_website']   ),
					'vivahr_url_slug'          => sanitize_text_field( $_POST['company_information']['url_slug']  )
				);
				
				
				if( isset( $postData ) && !empty( $postData ) && $error == false )
				{
					foreach( $postData as $field => $value )
					{
						update_option( $field, $value );
					}	
				}
				
				if( $error == false)
				{
					if ( ! function_exists( 'wp_handle_upload' ) ) 
					{
                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    } 

                    $uploadedfile = $_FILES['b_logo'];
					
					$upload_overrides = array( 'test_form' => false );
					
					//wp_send_json( $uploadedfile );

                    /* You can use wp_check_filetype() function to check the
                       file type and go on wit the upload or stop it.*/

                    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides);

                    if ( !isset( $movefile['error'] ) ) 
					{
                        // check if logo is already uploaded
						$currentLogo = get_option('vivahr_company_logo');
						
						if( !empty( $currentLogo ) )
						{
							$url = $currentLogo;
							$path = parse_url($url, PHP_URL_PATH); // Remove "http://localhost"
							$fullPath = get_home_path() . $path;
							//wp_send_json( $fullPath );
                            
                          // wp_send_json( unlink($fullPath) );
						}
		
						update_option('vivahr_company_logo', $movefile);
						
                    } 
					else 
					{
                        /**
                         * Error generated by _wp_handle_upload()
                         * @see _wp_handle_upload() in wp-admin/includes/file.php
                         */
                     //   echo $movefile['error'];
						
						//wp_send_json( $movefile['error'] );
                    }
				}
				
				$this->general_settings_submited();
				
			    $jobs = new VIVAHR_WP_Jobs();
				
				$jobs->unregister_vivahr_jobs_post_type();
		        $jobs->init();
		        flush_rewrite_rules();

				$response = array( 'code' => 200, 'error' => false, 'message' => 'Company Informations Saved Successfully!', 'logo' => $movefile['url'], 'notice' => sprintf( esc_html__( 'Please refresh the %1$sPermalink Settings%2$s to reflect the changes.' ), '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">', '</a>' ));
		    }

			wp_send_json( $response );
		}		
		
		/**
		 * COMPANY INFORMATION SETUP
	     * Saves/updates company information data
	     *
	     * @since    1.0.0
	     */
		public function api_key_setup()
		{
			$response = '';
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'api_key_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['api_key_form_field'] ) ), 'api_key_form_nonce_action' ) ) {
		           return;
                }

            	/* if( !isset($_POST['api_key']) || empty($_POST['api_key']) )
				{
					$response = array( 'code' => 404, 'error' => true, 'message' => 'API Key Field is required!');
					wp_send_json( $response );
				} */
				
				$postData = array(
				    'vivahr_client_id'     => sanitize_text_field($_POST['vivahr_client_id']),
				    'vivahr_client_secret' => sanitize_text_field($_POST['vivahr_client_secret']),
				    'vivahr_redirect_uri'  => sanitize_text_field($_POST['vivahr_redirect_uri'])
				);
				
				foreach( $postData as $field => $value )
				{
					update_option( $field, $value );
				}
					
				$vivahrAPI = new VIVAHR_WP_API();	
				$results = $vivahrAPI->generate_api_tokens();
				
				if( $results['error'] == true )
				{
                    if(isset($results['empty_api_data']) && $results['empty_api_data'] == true)
					{
						$response = array( 'code' => 404, 'error' => true, 'message' => 'API Data Removed Successfully!' );
					}
					else
					{
						$response = array( 'code' => 404, 'error' => true, 'message' => $results['message'] );
					}
					    
				}
				else
				{
					$url = ((isset($results['redirect_url'])) ? $results['redirect_url'] : '');
							
					$response = array( 'code' => 200, 'error' => true, 'message' => $results['message'], 'redirect_url' => $url );
				}
				
			
		    }
			
			wp_send_json( $response );
			die;
		}
		
		/*
		 * Additional company information update
		 *
		 */
		public function general_settings_submited()
        {
            $vivahr_url_slug = sanitize_text_field(get_option('vivahr_url_slug'));
            $selectedListingPage = (int)get_option( 'vivahr_jobs_listing_page' );
            $selectedListingPageLatest = (int)get_option( 'vivahr_jobs_listing_page_latest' );
            $careerPagePlaceholder = '<p>[vivahr_jobs]</p>';
 
            if( isset( $selectedListingPage ) && !empty( $selectedListingPage ) )
            {
            
                /*
                 * The career page is already selected
                 * Remove short code from the current page
                 * Add short code to new selected page
                 * Save current selected listing page id in DB for next update
                 */
                if( isset( $selectedListingPageLatest ) && !empty( $selectedListingPageLatest ) )
                {
                    $post = get_post( $selectedListingPageLatest );

                    $post_content =  apply_filters( 'the_content', $post->post_content );
            
                    if ( str_contains( $post_content, $careerPagePlaceholder ) ) 
                    {
                        // If old page already has shortcode into the content, then remove it from there
                        $new_post_content = str_replace( $careerPagePlaceholder, '', $post_content );
                    }
                    else
                    {
                        // If shortcode is removed from page manually then just keep same post content
                        $new_post_content = $post_content;
                    }
                
                    $postData = array(
                        'ID'  => $selectedListingPageLatest,
                        'post_content' => $new_post_content,
                        'post_name' => $vivahr_url_slug
                    );

                    wp_update_post( $postData );
                }
             
                /*
                 * Get current selected listing page 
                 * Update the page with shortcode to inject career page  
                 */
                $post = get_post( $selectedListingPage );

                $post_content =  apply_filters( 'the_content', $post->post_content );
            
                if ( str_contains( $post_content, $careerPagePlaceholder ) )
                {
                
                }
                else
                {
                
                    $post_content .= $careerPagePlaceholder;
                }
                
                $postData = array(
                    'ID'  => $selectedListingPage,
                    'post_content' => $post_content,
                    'post_name' => $vivahr_url_slug
                );

                wp_update_post( $postData );
            
			
			    update_option('vivahr_jobs_listing_page', $selectedListingPage);
                update_option( 'vivahr_jobs_listing_page_latest', $selectedListingPage );
            }
            else
            {
                if( isset( $selectedListingPageLatest ) && !empty( $selectedListingPageLatest ) )
                {
                    $post = get_post( $selectedListingPageLatest );

                    $post_content =  apply_filters( 'the_content', $post->post_content );

                    if ( str_contains( $post_content, $careerPagePlaceholder ) ) 
                    {
						
                        $new_post_content = str_replace( $careerPagePlaceholder, '', $post_content );
                    }
                    else
                    {
                    
                        $new_post_content = $post_content;
                    }
                
                    $postData = array(
                        'ID'  => $selectedListingPageLatest,
                        'post_content' => $new_post_content,
                        'post_name' => $vivahr_url_slug
                    );

                    wp_update_post( $postData );
                }

                $user = get_current_user_id();
            
                $postData = array(
                    'post_author'  => $user,
                    'post_name'    => $vivahr_url_slug,
                    'post_status'  => 'publish',
                    'post_content' => $careerPagePlaceholder,
                    'post_title'   => esc_html__( 'Jobs', 'career-page-by-vivahr' ),
                    'post_type'    => 'page',
                );
            
                $page_id = wp_insert_post($postData);
            
                 //if(!empty($page_id)) 
                 //{
                
                update_option('vivahr_jobs_listing_page', intval($page_id));
                update_option('vivahr_jobs_listing_page_latest', intval($page_id));
                // }
            }
			
        }
		
		/*
		 * Deletes Company Logo
		 *
		 */
		public function delete_company_logo()
		{
			// Get Current Logo so we can delete the file
			$current_logo = get_option('vivahr_company_logo');
			
			// Just check but should not happen
			if( isset($current_logo) && empty($current_logo))
			{
				// just return no file to delete
				wp_send_json(array('error' => true, 'message' => 'Company Logo not exists.'));
			}
			
			if( file_exists( $current_logo['file'] ) )
			{
				unlink($current_logo['file']);
				update_option('vivahr_company_logo', '');
				$response = array('code' => 200, 'error' => false, 'message' => 'Company Logo Deleted Successfully!');
			}
			else
			{
				update_option('vivahr_company_logo', '');
				$response = array('code' => 200, 'error' => false, 'message' => 'Company Logo Deleted Successfully!');
			}
			
			
			wp_send_json($response);
			
		}
		
		public function getStatesFromCountry()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'filter-states' ) 
			{
				check_ajax_referer( 'job_details_validation', 'nonce' );
				
			    $country = (isset($_POST['country']) && !empty($_POST['country'])) ? sanitize_text_field($_POST['country']) : '';
				
				global $wpdb;
			
			    $table = $wpdb->prefix . 'vivahr_states';
			
			    $sql = "SELECT b.state_short, b.state, a.country, a.full_name 
				FROM wp_vivahr_states b 
				LEFT JOIN wp_vivahr_countries a ON a.country = b.country 
				WHERE a.full_name = %s";
			
			    $states = $wpdb->get_results( $wpdb->prepare( $sql, $country ) );
			
				wp_send_json($states);
			}
			
			exit;
		}
		
		public function application_form_save()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'application-form-submit' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['application_form_field'] ) ), 'application_form_nonce_action' ) ) {
		           return;
                }
				
				$resume_required =            ( isset( $_POST['resume']['required']            ) ? intval($_POST['resume']['required'])            : '0');
	            $coverletter_required =       ( isset( $_POST['coverletter']['required']       ) ? intval($_POST['coverletter']['required'])       : '0');
	            $phone_required =             ( isset( $_POST['phone']['required']             ) ? intval($_POST['phone']['required'])             : '0');
	            $applicant_address_required = ( isset( $_POST['applicant_address']['required'] ) ? intval($_POST['applicant_address']['required']) : '0');
	            $linkedin_required =          ( isset( $_POST['linkedin']['required']          ) ? intval($_POST['linkedin']['required'])          : '0');
	            $portfolio_required =         ( isset( $_POST['portfolio']['required']         ) ? intval($_POST['portfolio']['required'])         : '0');
	            $website_required =           ( isset( $_POST['website']['required']           ) ? intval($_POST['website']['required'])           : '0');
		        
				$inputData = array(
				    'resume'            => $resume_required,         
	                'coverletter'       => $coverletter_required,    
	                'phone'             => $phone_required,          
	                'applicant_address' => $applicant_address_required,
	                'linkedin'          => $linkedin_required,       
	                'portfolio'         => $portfolio_required,      
	                'website'           => $website_required        
				);
				
				foreach($inputData as $inputData_key => $inputData_value)
				{
					if ( ! ctype_digit( $inputData_value ) ) 
				    {
                        $application_form_validation[sanitize_text_field($inputData_key)] = 'The field '.sanitize_text_field($inputData_key).' has input value which is not int!';
                    } 
				}

				// Application form
		        $application_form = array(
		            0 => array(
		                'label'    => 'Name',
		                'name'     => 'name',
		                'type'     => 'text',
		                'required' => 'required',
		                'disabled'  => 'no'
			        ),
			        1 => array(
		                'label'    => 'Email',
		                'name'     => 'email',
		                'type'     => 'email',
		                'required' => 'required',
		                'disabled'  => 'no'
		            ),		   
		            2 => array(
		                'label'    => 'Resume',
		                'name'     => 'resume',
		                'type'     => 'file',
		                'required' => ((intval($resume_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['resume']['disabled']) == 1) ? 'yes' : 'no')
		            ),	
			        3 => array(
		                'label'    => 'Cover Letter',
		                'name'     => 'coverletter',
		                'type'     => 'textarea',
		                'required' => ((intval($coverletter_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['coverletter']['disabled']) == 1) ? 'yes' : 'no')
		            ),			
		            4 => array(
		                'label'    => 'Phone number',
		                'name'     => 'phone',
		                'type'     => 'tel',
		                'required' => ((intval($phone_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['phone']['disabled']) == 1) ? 'yes' : 'no')
		            ),	
			        5 => array(
		                'label'    => 'Address',
		                'name'     => 'applicant_address',
		                'type'     => 'text',
		                'required' => ((intval($applicant_address_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['applicant_address']['disabled']) == 1) ? 'yes' : 'no')
		            ),			
		            6 => array(
		                'label'    => 'LinkedIn URL',
		                'name'     => 'linkedin',
		                'type'     => 'url',
		                'required' => ((intval($linkedin_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['linkedin']['disabled']) == 1) ? 'yes' : 'no')
		            ),			
                    7 => array(
		                'label'    => 'Portfolio URL',
		                'name'     => 'portfolio',
		                'type'     => 'url',
		                'required' => ((intval($portfolio_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['portfolio']['disabled']) == 1) ? 'yes' : 'no')
		            ),			
		            8 => array(
		                'label'    => 'Website URL',
		                'name'     => 'website',
		                'type'     => 'url',
		                'required' => ((intval($website_required) == 0) ? '' : 'required'),
		                'disabled'  => ((intval($_POST['website']['disabled']) == 1) ? 'yes' : 'no')
		            )
	            );
				
				update_option('vivahr_application_form', json_encode($application_form));
				
				$response = array('code' => 200, 'error' => false, 'message' => 'The application form has been successfully saved!');
				
				wp_send_json($response);
			}
			
			exit;
		}
		
		public function notifications_setup_save()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'notifications_setup' ) 
			{
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['notifications-form_field'] ) ), 'notifications-form_nonce_action' ) ) {
		           return;
                }
				
				$insertData = array(
				    'from'     => sanitize_email($_POST['notification']['from']),
				    'reply_to' => sanitize_email($_POST['notification']['reply_to']),
				    'to'       => '{applicant_email}',
				    'cc'       => sanitize_email($_POST['notification']['cc']),
				    'subject'  => sanitize_text_field($_POST['notification']['subject']),
				    'content'  => sanitize_text_field($_POST['notification_content'])
				);
				
				update_option('vivahr_applicant_notifications' , $insertData);
				
				$response = array('code' => 200, 'error' => false, 'message' => 'The notifications form has been successfully saved!');
				
				wp_send_json($response);
			}
			
			die;
		}		
		
		public function hr_notifications_setup_save()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'hr_notifications_setup' ) 
			{
				
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hr_notifications-form_field'] ) ), 'hr_notifications-form_nonce_action' ) ) {
		           return;
                }
				
				$insertData = array(
				    'from'     => sanitize_email($_POST['hr_notification']['from']),
				    'reply_to' => sanitize_email($_POST['hr_notification']['reply_to']),
				   // 'to'       => '{applicant_email}',
				    'cc'       => sanitize_email($_POST['hr_notification']['cc']),
				    'subject'  => sanitize_text_field($_POST['hr_notification']['subject']),
				    'content'  => sanitize_text_field($_POST['hr_notification_content'])
				);
				
				update_option('vivahr_hr_notifications' , $insertData);
				update_option('vivahr_business_email' , sanitize_email($_POST['hr_notification']['to']));
				
				$response = array('code' => 200, 'error' => false, 'message' => 'The notifications form has been successfully saved!');
				
				wp_send_json($response);
			}
			
			die;
		}
		
	}
}
