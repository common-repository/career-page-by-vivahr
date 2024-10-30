<?php namespace VIVAHR\Controllers\VIVAHR;

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

if( !class_exists( 'VIVAHR_Settings') )
{
	class VIVAHR_Settings
    {   
	    public function __construct()
		{
			add_action( 'settings_menu',    array( $this, 'generate_settings_menu' ), 10, 1 );
			
			add_action( 'settings_content', array( $this, 'load_settings_content'  ), 10, 1 );
		}
		
	    public function init()
		{	
            $core = new CoreController();
			
			add_action('menu_tabs', array($core, 'generate_menu_tabs'));
			$settings_section = 'general';
			
            if( isset( $_GET['page']) && !empty( $_GET['page'] ) )
			{
				$settings_page = sanitize_text_field( $_GET['page'] );
			}
			
			if( isset( $_GET['section']) && !empty( $_GET['section'] ) )
			{
				$settings_section = sanitize_text_field( $_GET['section'] );
			}
			
			$allowed_settings_pages = array( 'vivahr_settings' );
			$allowed_settings_sections = array( 'general', 'api-key' );

		    if ( in_array( sanitize_text_field( $settings_page ), $allowed_settings_pages, true ) && in_array( sanitize_text_field($settings_section), $allowed_settings_sections, true ) )
			{	 
			   $page_class = $settings_section;

			   $section_title = $core->vivahr_config_item('menu_tabs');
			   $section_title = $section_title[$settings_section]['name'];
			   
			   require_once $core->vivahr_admin_views_path.'vivahr/templates/vivahr_admin_header.php';
		       require_once $core->vivahr_admin_views_path.'vivahr/settings/vivahr_settings_main.php';
            }
			else
			{
				//$response = array( 'error' => true, 'message' => esc_html( 'Requested page/section is not allowed', 'career-page-by-vivahr' ) );
			    //wp_send_json( $response );	

                wp_die(esc_html_e('Requested page/section is not allowed', 'career-page-by-vivahr'));			
			} 
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
		   
		    $menu_tabs = $core->vivahr_config_item('menu_tabs_vivahr');
		  
		    $menu = array();
		    foreach($menu_tabs as $menu_tab => $menu_val)
		    {
		        if( $menu_tab == 'general' )
			    {
			        $menu_slug = 'admin.php?page=vivahr_settings';  
			    }
			    else
			    {
				    $menu_slug = "admin.php?page=vivahr_settings&section=$menu_tab";
			    }
				?>
				<li id="<?php echo esc_attr($menu_tab);?>" class="vsm-item <?php echo (($section == $menu_tab) ? 'active': '');?>"><a href="<?php echo esc_html( $menu_slug );?>"><?php echo esc_html($menu_val['name']);?></a></li>
				<?php
			   
			    //$menu[] = '';
		    }
		   
		    //echo implode(' ', $menu);
		}
		
		public function load_settings_content( $section )
		{
			
			$core = new CoreController();
 
            $response = '';        

			if( !isset( $section ) || empty( $section ))
			{
				// TODO: Return error message
				return;
			}

			$settings_content_file = $core->vivahr_admin_views_path.'/vivahr/settings/'.$section.'_section.php';
       
            if( file_exists( $settings_content_file ) ) 
            {
	            require_once $settings_content_file;
            }
            else
            {
         	    $response = array('error' => true, 'message' => 'File '.$section.'_section.php is missing in '.$core->vivahr_admin_views_path.'/wp/settings/');

         	    wp_send_json( $response );
            }	
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
            $postData = array();
			$message = '';
	
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'company_information_setup' ) 
			{
				
				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['company_information_form_field'] ) ), 'company_information_form_nonce_action' ) ) {
				   $error = true;
		           wp_die('Nonce error');
                }
				
               /*  if( !isset( $_POST['company_information']['listing_page'] ) || empty( $_POST['company_information']['listing_page'] ) )
				{
					$error = true;
					$message = 'Job Listing Page is Required Field!';	
				} */
			
				if( !empty($_POST['company_information']['listing_page']) && is_numeric( $_POST['company_information']['listing_page'] ) == false )
				{
					$error = true;
					$message = 'Job Listing Page Field has wrong format!';	
				}	
				
				if( isset( $_POST['company_information']['url_slug'] ) )
				{
					if( preg_match('/[^a-z_\-0-9]/i', sanitize_text_field( $_POST['company_information']['url_slug'] ) ) )
                    {                    
                        $error = true;
					    $message = 'URL Slug has wrong format!';	
                    }
					
			        $postData['vivahr_url_slug'] = sanitize_text_field( $_POST['company_information']['url_slug'] );
				}
				
				if( isset( $_POST['company_information']['career_type'] ) )
				{
					$allowed_types = array('jobs', 'culture');
					
					if ( !in_array( $_POST['company_information']['career_type'], $allowed_types ) ) 
                    {                    
                        $error = true;
					    $message = 'Career Type is Incorect. Please try again!';	
                    }
					
			        $postData['vivahr_career_type'] = sanitize_text_field( $_POST['company_information']['career_type'] );
				}
				
				if($error == false)
				{
				    $listing_page = intval($_POST['company_information']['listing_page']);
				
				    $postData['vivahr_jobs_listing_page'] = $listing_page;
					
					foreach( $postData as $field => $value )
					{
						update_option( $field, $value );
					}	
					
					$this->general_settings_submited();
					
					$message = 'Company Informations Saved Successfully!';
				}
		    }
			
            $response = array( 'code' => (($error == true) ? 404 : 200), 'error' => $error, 'message' => $message);	
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
			$error = false;
			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'api_key_setup' ) 
			{

				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['api_key_form_field'] ) ), 'api_key_form_nonce_action' ) ) {
				   return;
                }
                
				$error = false;
				
				//TODO: Do validation
               /*  if(preg_match('/[^a-z_\-0-9]/i', sanitize_text_field($_POST['api_key'])))
                {                    
                    $error = true;
					$response = array( 'code' => 404, 'error' => true, 'message' => 'API Key has invalid format!' );
                } */
				
				if($error == false)
				{
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
		    }
			
			wp_send_json( $response );
			
		}
		
		/*
		 * Additional company information update
		 *
		 */
		public function general_settings_submited()
        {
            $vivahr_url_slug = sanitize_text_field(get_option('vivahr_url_slug'));
            $selectedListingPage = intval(get_option( 'vivahr_jobs_listing_page' ));
            $selectedListingPageLatest = intval(get_option( 'vivahr_jobs_listing_page_latest' ));
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
                        'ID'           => $selectedListingPageLatest,
                        'post_content' => $new_post_content,
                        'post_name'    => $vivahr_url_slug
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
                    'ID'           => $selectedListingPage,
                    'post_content' => $post_content,
                    'post_name'    => $vivahr_url_slug
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
                        'ID'           => $selectedListingPageLatest,
                        'post_content' => $new_post_content,
                        'post_name'    => $vivahr_url_slug
                    );

                    wp_update_post( $postData );
                }

                $user = intval(get_current_user_id());
            
                $postData = array(
                    'post_author'  => sanitize_text_field( $user ),
                    'post_name'    => $vivahr_url_slug,
                    'post_status'  => 'publish',
                    'post_content' => $careerPagePlaceholder,
                    'post_title'   => esc_html__( 'Jobs', 'career-page-by-vivahr' ),
                    'post_type'    => 'page',
                );
            
                $page_id = wp_insert_post($postData);
            
                update_option( 'vivahr_jobs_listing_page', intval( $page_id ) );
                update_option( 'vivahr_jobs_listing_page_latest', intval( $page_id ) );
               
            }
        }
	}
}