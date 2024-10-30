<?php namespace VIVAHR\Controllers;

/**
 * Initial Setup Library
 *
 * @link      
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Libraries
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;
use VIVAHR\Libraries\VIVAHR_WP_Common;

if( !class_exists( 'InitialSetupController') )
{
    class InitialSetupController extends CoreController
    {
	    /**
         * Once the plugin is activated redirect to the setup page
         * 
         * @since    1.0.0
         */
        public function redirect_to_initial_setup() 
        {	
            $initialSetup = intval( get_option( 'vivahr_setup' ) );
			
			if( isset( $initialSetup ) && is_numeric($initialSetup) &&  $initialSetup == 0)
			{
                update_option( 'vivahr_setup', 1 );
                wp_safe_redirect( add_query_arg( array( 'page' => 'vivahr_setup' ), admin_url( 'admin.php?' ) ) );
                exit;
			}

            return;
        }
		
		/**
         * Shows Initial Setup Page
         * 
         * @since    1.0.0
         */
	    public function vivahr_setup() 
	    {
		    $setup_data = array(
		        'vivahr_company_name'      => get_option('vivahr_company_name'),
		        'vivahr_hr_email_address'  => get_option('vivahr_hr_email_address'),
		        'vivahr_jobs_listing_page' => get_option('vivahr_jobs_listing_page'),
		        //'vivahr_api_key'         => get_option('vivahr_api_key')
		        'vivahr_client_id'         => get_option('vivahr_client_id'),
		        'vivahr_client_secret'     => get_option('vivahr_client_secret'),
		        'vivahr_redirect_uri'      => get_option('vivahr_redirect_uri')
		    );
			
		    require_once $this->vivahr_admin_views_path.'setup.php';
	    }
		
		public function initial_setup_submited()
	    {			
			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'vivahr_setup' )
			{
				$common = new VIVAHR_WP_Common();
				
				$response = array(
		    	    'success' => array(),
		    	    'error'   => array(),
		        );
				

				if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vivahr_setup_nonce'] ) ), 'vivahr-setup' ) ) {
				    
					return;
                }
				
				if ( empty( $response['error'] ) ) 
		        {
                    $update_options = array();
			        $setup_fields = $common->initial_setup_fields();
				    
			        foreach ( $setup_fields as $field => $field_details ) 
			        {
			            if ( ! isset( $_POST[ $field ] ) || empty( $_POST[ $field ] ) && $field_details['required'] == true ) 
				        {
				            $response['error'][$field] = sprintf( esc_html__( '%s is required!', 'career-page-by-vivahr' ), esc_html( $field_details['title'] ) );
			            } 
                        else					
				        {
					        if( empty( $field_details['sanitize'] ) )
					        {
					        	$field_val = sanitize_text_field( $_POST[ $field ] );
					        }
					        else
					        {
					        	$field_val = call_user_func( $field_details['sanitize'], sanitize_text_field($_POST[ $field ]) );
					        }
						
			                if ( $field === 'vivahr_hr_email' ) 
					        {
					            if ( ! is_email( $field_val ) ) 
					    	    {
					    		    $response['error'][] = esc_html__( 'HR Email Address is invalid!', 'career-page-by-vivahr' );
					    	    }
					        }
					
					        if ( empty( $response['error'] ) ) 
					        {
					            if( $field == 'page_id' )
						        {
							        $update_options['vivahr_jobs_listing_page'] = $field_val;
						        }
						        else
						        {
							        $update_options[ $field ] = $field_val;
						        }
					        }
				        }
			        }
				
			        if ( count( $update_options ) === count( $setup_fields ) ) 
			        {
			            foreach ( $update_options as $update_option => $field_val ) 
				        {
				            update_option( $update_option, $field_val );
			            }
				       
				        $this->setup_submited();  
		            }
					
					$response['redirect']  = esc_url_raw( add_query_arg( array( 'page' => 'vivahr_overview' ), admin_url( 'admin.php?page=vivahr_overview' ) ) );
					
					if ( isset($_POST['vivahr_client_id']) && isset($_POST['vivahr_client_secret']) )
					{
						//wp_send_json( 'tests' );
						$unique_state_id = uniqid('wp_');
						$url = "https://auth.vivahr.com/oauth/authorize?response_type=code&client_id=".$_POST['vivahr_client_id']."&state=".$unique_state_id."&redirect_uri=".$_POST['vivahr_redirect_uri']."";
                       
						$response['redirect']  = $url;
					}
					
			        $response['success'][] = esc_html__( 'Setup successfully completed!', 'career-page-by-vivahr' );
		        }
			
		        wp_send_json( $response );
			}
	    }
	
	    private function setup_submited()
	    {
            $selectedListingPage = intval( get_option( 'vivahr_jobs_listing_page' ) );
            $selectedListingPageLatest = intval( get_option( 'vivahr_jobs_listing_page_latest' ) );
            $careerPagePlaceholder = '<p>['.$this->vivahr_config_item('career_page_shortcode').']</p>';
            		
            if( isset( $selectedListingPage ) && !empty( $selectedListingPage ) )
            {
			
			    /*
                 * The career page is already selected
                 * Remove short code from the current page
                 * Add short code to new selected page
                 * Save current selected listing page id in DB for next update
                 */
			    if( isset( $selectedListingPageLatest ) && !empty( $selectedListingPageLatest ) && is_numeric( $selectedListingPageLatest ) )
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
			    	    'ID'           => intval( $selectedListingPageLatest ),
			            'post_content' => $new_post_content
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
			    	'ID'           => intval( $selectedListingPage ),
			    	'post_content' => $post_content
			    );

        	    wp_update_post( $postData );
			
			    update_option( 'vivahr_jobs_listing_page_latest', intval( $selectedListingPage ) );
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
			    	'ID'           => intval( $selectedListingPageLatest ),
			    	'post_content' => $new_post_content
			    );

        	    wp_update_post( $postData );
			}

        	$user = get_current_user_id();
			
			$postData = array(
				'post_author'  => sanitize_text_field( $user ),
				'post_name'    => 'Jobs',
				'post_status'  => 'publish',
				'post_content' => $careerPagePlaceholder,
				'post_title'   => esc_html__( 'Jobs', 'career-page-by-vivahr' ),
				'post_type'    => 'page',
			);
			
			$page_id = wp_insert_post($postData);
			
			if( !empty( $page_id ) ) 
			{
		        update_option( 'vivahr_jobs_listing_page', intval( $page_id ) );
		        update_option( 'vivahr_jobs_listing_page_latest', intval( $page_id ) );
			}
			
        }
       
	    // GET API KEY AND Generate access and refresh token
       // $this->generate_api_tokens();
	}
	
/* 	public function generate_api_tokens()
	{
		$unique_state_id = uniqid('wp_');
		
		$api_key = get_option('vivahr_api_key');
		
		// IF API Key is empty then just return
		if( empty( $api_key ) )
		{
			update_option( 'vivahr_api_token_data', '' );
		  
		}
		else
		{
			
		    // GET Authorization code
		    $api_url = $this->generate_api_url('oauth/authorize?response_type=code&client_id=wp_'.$api_key.'&state='.$unique_state_id);
		
		    $response = wp_remote_get( $api_url );
						
		    if( $response['response']['code'] != 200 )
            {
		        update_option('vivahr_api_token_data', '');
				
                return 'error';
            }
            else
            {
                $array_response = json_decode($response['body'], true);
            
			    // IF state is not same just return
	            if($array_response['state'] != $unique_state_id)
			    {
			    	return;
			    }
			
                $api_code = $array_response['code'];
			
			    // GET AUTH TOKENS
                $api_url = $this->generate_api_url('oauth/token');	
			
			    $endpoint = $api_url;

                $body = [
	                'grant_type' => 'authorization_code',
	                'code'       => $api_code,
                ];

                $body = wp_json_encode( $body );

                $options = [
	                'body'        => $body,
	                'headers'     => [
	                    'Content-Type'  => 'application/json',
	                    'Authorization' => 'Basic ' . base64_encode( 'wp_'.$api_key.'' . ':' . 'wp_'.$api_key.'' ),
	                ]
                ];

                $token_response = wp_remote_post( $endpoint, $options );
			
			    if( $token_response['response']['code'] != 200 )
                {
                    return 'error';
                }
			    else
			    {
				    $array_response = json_decode($token_response['body'], true);
					
			        $token_api_response = array(
					    'access_token'  => $array_response['access_token'],
					    'expires_in'    => $array_response['expires_in'],
					    'token_type'    => $array_response['token_type'],
					    'scope'         => $array_response['scope'],
					    'refresh_token' => $array_response['refresh_token']					
				    );
			    
				    // wp_send_json($token_api_response);die;	
			        update_option('vivahr_api_token_data', $token_api_response);
			    }
				
            }
			
		}
	} */
	
	public function generate_api_url($segment)
	{
		$api_url = $this->vivahr_config_item('vivahr_api_url').'/'.$segment;
		
		return $api_url;
	}
	
	public function vivahr_api_callback()
	{
		$api = [
		    'code'          => ( isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '' ),
			'state'         => ( isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '' ),
			'endopint_url'  => $this->generate_api_url('oauth/token'),	
			'client_id'     => sanitize_text_field(get_option('vivahr_client_id')),
			'client_secret' => sanitize_text_field(get_option('vivahr_client_secret')),
			'redirect_uri'  => sanitize_text_field(get_option('vivahr_redirect_uri'))
		];

		$error_array = '';
		
		// Validation
		if( empty($api['code']) ){
			$error_array = ['error' => true, 'message' => esc_html_e('Code is not set!', 'career-page-by-vivahr')];
		}
		
		if( empty($api['state']) ){
			$error_array = ['error' => true, 'message' => esc_html_e('State is not set!', 'career-page-by-vivahr')];
		}
		
		if( empty($api['redirect_uri']) ){
			$error_array = ['error' => true, 'message' => esc_html_e('Callback URL is required field!', 'career-page-by-vivahr')];
		}
		
        // Verify State here
	
		//
		if(empty($error_array))
		{
			// GET AUTH TOKENS
            $body = [
	            'grant_type'    => 'authorization_code',
	            'code'          => $api['code'],
				'client_id'     => $api['client_id'],
				'client_secret' => $api['client_secret'],
				'redirect_uri'  => $api['redirect_uri']
				
            ];

            $body = wp_json_encode( $body );

            $options = [
	            'body'        => $body,
	            'headers'     => [
	                'Content-Type'  => 'application/json',
	            ]
            ];

            
            $token_response = wp_remote_post( $api['endopint_url'], $options );
		
			if( $token_response['response']['code'] != 200 )
            {
				// redirect back to setup page
               
            }
			else
			{
				$array_response = json_decode($token_response['body'], true);
					
			    $token_api_response = array(
					'access_token'  => esc_html($array_response['access_token']),
					'expires_in'    => esc_html($array_response['expires_in']),
					'token_type'    => esc_html($array_response['token_type']),
					'scope'         => esc_html($array_response['scope']),
					'refresh_token' => esc_html($array_response['refresh_token'])					
				);
			    
				
			    update_option('vivahr_api_token_data', $token_api_response);
				
				$overview_url = get_admin_url('').'admin.php?page=vivahr_overview';
				
				?>
				<script>
				window.location.replace( '<?php echo esc_url($overview_url);?>' );
				</script>
				<?php
			}
		}
	}
}
}