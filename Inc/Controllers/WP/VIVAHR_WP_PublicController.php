<?php namespace VIVAHR\Controllers\WP;
 
defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;

use VIVAHR\Libraries\VIVAHR_WP_Enqueue;

use VIVAHR\Controllers\WP\VIVAHR_WP_JobsList;

if( !class_exists( 'VIVAHR_WP_PublicController' ) )
{
	class VIVAHR_WP_PublicController extends CoreController
	{
        public function url()
		{
			$current_url = esc_url( sanitize_text_field( $_SERVER["REQUEST_URI"] ) );
            $slug = explode( "/", $current_url );
			
			return $slug;
		}

	    public function init()
		{
            $slug = $this->url();
			
			$jobs_slug = get_option('vivahr_url_slug');
			
			if($slug[1] == $jobs_slug)
			{
			
				$enqueue = new VIVAHR_WP_Enqueue();
			    add_action( 'wp_enqueue_scripts', array( $enqueue, 'public_enqueue_styles'  ), 1 );
                add_action( 'wp_enqueue_scripts', array( $enqueue, 'public_enqueue_scripts' ), 1 );
				
				add_action( 'init',                  array( $this, 'shortcode'              ) );
			    add_filter( 'the_content',           array( $this, 'vivahr_jobs_content'    ) );
				
				add_action( 'application_form_init', array( $this, 'vivahr_application_form_init' ) );
			    add_action( 'save_candidate_application', array( $this, 'save_candidate_application' ) );

			    add_action( 'application_submited', array($this, 'end_session') );
			
			    add_filter( 'the_title', function ($title) { return "";});
				
			}
	
		}
		
        /**
         * Replace the shortcode in WP page with career page
         * 
         * @since    1.0.0
         */
        public function shortcode()
        {
			$jobsList = new VIVAHR_WP_JobsList();
            add_shortcode( $this->vivahr_config_item('career_page_shortcode'), array( $jobsList, 'init' ) );
        }
		
		public function vivahr_jobs_content( $content ) 
		{
		    if ( ! is_singular( 'vivahr_jobs' ) || ! in_the_loop() || ! is_main_query() ) 
			{
		    	return $content;
		    }
            
			global $wpdb;
			$locations_table = $wpdb->prefix . 'vivahr_location';
			$departments_table = $wpdb->prefix . 'vivahr_department';
			$positions_table = $wpdb->prefix . 'vivahr_position_type';
			
		    ob_start();
			$application_fields = json_decode( get_option( 'vivahr_application_form' ), true );
			$job_id = get_the_ID();
			
			$meta = get_post_meta( $job_id, 'vivahr_job_details' );
					
			if(isset($meta) && !empty($meta))
			{
				if(!empty($meta[0]['vivahr_location']))
				{
					$location_id = (int)$meta[0]['vivahr_location'];
					$location_data = $wpdb->get_row( $wpdb->prepare( "SELECT id, name, address, city, country, state, zip_code FROM %i WHERE id = %d", $locations_table, $location_id ) );
				}
				else
				{
					$location_data = '';
				}
					
				if(!empty($meta[0]['vivahr_department']))
				{
					$department_id = (int)$meta[0]['vivahr_department'];
					$department_data = $wpdb->get_row( $wpdb->prepare( "SELECT id, name FROM %i WHERE id = %d", $departments_table, $department_id ) );
				}
				else
				{
					$department_data = '';
				}
			}
					
			if(isset($meta[0]['vivahr_position_type']))
			{
				$position_type = $meta[0]['vivahr_position_type'];
				
				$position_name = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM %i WHERE id = %d", $positions_table, $position_type ) );
			}
			else
			{
				$position_type = '';
			}
			
			$post   = get_post( $job_id );
            /* $output =  apply_filters( 'the_content', $post->post_content ); */
		    

		    include  VIVAHR_PLUGIN_DIR_URL.'/views/public/application_form.php';
		    return ob_get_clean();
        }
		
        public function save_candidate_application()
		{
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) ) 
			{
			    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpt_nonce_field'] ) ), 'cpt_nonce_action' ) ) {
		            return;
                }

				add_action('init', 'start_session', 1);

				$error = false;
				
                // Get Application form
                $application_form_fields = get_option( 'vivahr_application_form' );

                if( $application_form_fields == '' )
                {
                	$error = true;
                	$response = esc_html( 'Application form is missing. Please try to reinstall the plugin!' );

                	return $response;
                } 
               
                $application_form_fields = json_decode( $application_form_fields, true );
             
                foreach( $application_form_fields as $field )
                {

                	if( $field['disabled'] == 'no' && $field['required'] == 'required' && $field['name'] != 'resume')
                	{ 
                        //TODO: Do more validation based on field type
                        $vars[$field['name']] = sanitize_text_field($_POST[$field['name']]);

                        if( empty( $_POST[$field['name']] ) )
                        {
                        	$error = true;
                        	$error_array[$field['name']] = ucfirst( $field['label'] ).' is required field!';

                        }
                	}
                	elseif( $field['disabled'] == 'no' && $field['required'] == '' )
                	{
                		$vars[$field['name']] = sanitize_text_field($_POST[$field['name']]);
                	}
                }

                //TODO: Extra validation for parent post -> job post

                if($error == true)
                {
                	$_SESSION['application_success'] = implode('</br>', $error_array);
                }
                else
                {
					if ( ! function_exists( 'wp_handle_upload' ) ) 
					{
                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    } 

                    $uploadedfile = $_FILES['resume'];
					
					$upload_overrides = array( 'test_form' => false );
					
                    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides);

                  
                    if ( $movefile && !isset( $movefile['error'] ) ) 
					{
                        $resume = $movefile['url'];
						
                    } 
					else 
					{
                      
                    }
					
					
					
                   // Sanitize post fields
				   $post_title             = sanitize_text_field( $vars['name'] );
				   $post_parent            = intval( $_POST['job_post_id'] );
				   $post_status            = 'publish';
				   $post_type              = 'vivahr_candidates';
				   $post_content           = sanitize_textarea_field( $vars['coverletter'] );
				   $post_email             = sanitize_email( $vars['email'] );
                   $post_phone             = sanitize_text_field( $vars['phone'] );				
                   $post_applicant_address = sanitize_text_field( $vars['applicant_address'] );				
                   $post_linkedin          = sanitize_url( $vars['linkedin'] );				
                   $post_portfolio         = sanitize_url( $vars['portfolio'] );				
                   $post_website           = sanitize_url( $vars['website'] );	

                   $cpt_args = array(
                       'post_title'   => $post_title,
                       'post_parent'  => $post_parent,
                       'post_status'  => $post_status,
                       'post_type'    => $post_type,
				       'post_content' => $post_content
                   );


                   $cpt_post_meta_args = array(
			          'post_email'             => $post_email,
                      'post_phone'             => $post_phone,				
                      'post_applicant_address' => $post_applicant_address,				
                      'post_linkedin'          => $post_linkedin,				
                      'post_portfolio'         => $post_portfolio,				
                      'post_website'           => $post_website,
					  'post_resume'            => $resume
			       );

			       //$cpt_id = wp_insert_post( $cpt_args, $wp_error);
			       $cpt_id = wp_insert_post( $cpt_args );

			       if( !isset( $cpt_id ) || empty( $cpt_id) )
			       {
                      $error = true;
                      //TODO: Return error -> post is not saved
			       }
			       else
			       {
			       	    add_post_meta( $cpt_id, 'vivarh_applicant_additional', $cpt_post_meta_args, true );
			       }


                   $notification = get_option('vivahr_applicant_notifications');
				   $hr_notification = get_option('vivahr_hr_notifications');
				   $hr_to = get_option('vivahr_business_email');
				   
				   if(isset($notification) && !empty($notification))
				   {
					   
					    if($notification['to'] == '{applicant_email}')
					    {

                            

                            $variables = array(
                                '{applicant_email}' => sanitize_email($post_email)
                            );

                            $msg_to = strtr($notification['to'], $variables);
                                

						}
						
					   $to =  $msg_to;
				       $subject = sanitize_text_field($notification['subject']);
				       $message = sanitize_text_field($notification['content']);
 				       $headers = array(
					   'Content-Type: text/html; charset=UTF-8',
					   'From: <'.sanitize_email($notification['from']).'>',
					   'Cc: <'.sanitize_email($notification['cc']).'>',
					   'Reply-To: <'.sanitize_email($notification['reply_to']).'>'
					   );
				       $attachments = '';
				   
                       wp_mail( $to, $subject, $message, $headers, $attachments );
				   }				   
				   
				   if(isset($hr_notification) && !empty($hr_notification) && !empty($hr_to))
				   {

					   $to =  sanitize_email($hr_to);
				       $subject = sanitize_text_field($hr_notification['subject']);
				       $message = sanitize_text_field($hr_notification['content']);
 				       $headers = array(
					   'Content-Type: text/html; charset=UTF-8',
					   'From: <'.sanitize_email($hr_notification['from']).'>',
					   'Cc: <'.sanitize_email($hr_notification['cc']).'>',
					   'Reply-To: <'.sanitize_email($hr_notification['reply_to']).'>'
					   );
				       $attachments = '';
				   
                       wp_mail( $to, $subject, $message, $headers, $attachments );
				   }
				   
			       $_SESSION['application_success'] = 'Application submited successufully!';
                }			
			}
		}

        public function start_session() 
        {
            if( !session_id() ) 
            {
                session_start();
            }      
        }

        function end_session() 
        {
        	if( session_id() ) 
            {
                 session_destroy();
            }    
           
        } 
		
		function search_jobs()
		{
			
			$response = array('code'=>200);
			wp_send_json($response);
		}
		
		
	}
}