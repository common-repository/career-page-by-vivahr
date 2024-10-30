<?php 

/**
 * Initial Setup Library
 *
 * @link      
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Libraries
 */

namespace VIVAHR\Libraries;

defined('ABSPATH') or die();

use VIVAHR\Controllers\CoreController;

if( !class_exists( 'VIVAHR_WP_API') )
{
    class VIVAHR_WP_API
    {
        /**
	     * Generates VIVAHR API URL Link
		 * vivahr_api_url should be defined in the /vivahr/config/Config.php
	     * @since    1.0.0
	     */
        public function generate_api_url($endpoint)
        {
		    $core = new CoreController();
			
            $api_base_url = $core->vivahr_config_item('vivahr_api_url');

            return $api_base_url.$endpoint;
        }
		
		public function generate_api_tokens()
	    {
/* 		    $unique_state_id = uniqid('wp_');
		
		    $api_key = sanitize_text_field(get_option('vivahr_api_key'));
		
		    // IF API Key is empty then just return
		    if( empty( $api_key ) )
		    {
		    	update_option( 'vivahr_api_token_data', '' );
				
				$response = array('error' => true, 'message' => "API Key is missing!", 'empty_api_key' => true);
				return $response;
		    }
		    else
		    {
		
		        // GET Authorization code
		        $api_url = $this->generate_api_url('oauth/authorize?response_type=code&client_id=wp_'.$api_key.'&state='.$unique_state_id);
			    
		        $response = wp_remote_get( $api_url );
			 
		        if( $response['response']['code'] != 200 )
                {
		            update_option('vivahr_api_token_data', '');
		        		
                    $response = array('error' => true, 'message' => "Unable to get response from VIVAHR API. </br>Please check your API or contact support!");
				    return $response;
                }
                else
                {
                    $array_response = json_decode($response['body'], true);
            
			        // IF state is not same just return
	                if($array_response['state'] != $unique_state_id)
			        {
						$response = array('error' => true, 'message' => "Unable to get response from VIVAHR API.</br> Please check your API or contact support!");
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
                       $response = array('error' => true, 'message' => "Unable to get API toke! Please check your API Key");
			           return;
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
					
			            update_option('vivahr_api_token_data', $token_api_response);
						
						$response = array('error' => false, 'message' => "API key successfully authorized.");
			            return $response;
			        }
                }
		    }
			
			die; */
			$unique_state_id = uniqid('wp_');
		
		    $api = [
			    'vivahr_client_id'     => sanitize_text_field( get_option('vivahr_client_id') ),
			    'vivahr_client_secret' => sanitize_text_field( get_option('vivahr_client_secret') ),
			    'vivahr_redirect_uri'  => sanitize_text_field( get_option('vivahr_redirect_uri') )
			];
			
			// Validation 
			if( empty($api['vivahr_client_id']) || empty($api['vivahr_client_secret']) || empty($api['vivahr_redirect_uri']) ) 
			{
				update_option('vivahr_api_token_data', '');
				$response['error'] = true;
				$response['empty_api_data'] = true;
				return $response;
			}
			
			$core = new CoreController();
			
            $api_base_url = $core->vivahr_config_item('vivahr_api_url');
			
            $unique_state_id = uniqid('wp_');
			$url = $api_base_url."oauth/authorize?response_type=code&client_id=".$api['vivahr_client_id']."&state=".$unique_state_id."&redirect_uri=".$api['vivahr_redirect_uri']."";
            
            $response['error'] = false;			
			$response['redirect_url']  = $url;
			$response['message'] = '';
			
			return $response;

	    }
		
		/**
	     * Refresh VIVAHR API Access Token
	     *
	     * @since    1.0.0
	     */		
		public function refresh_api_access_token()
		{
			$error = false;
			$response = '';
			$message = '';
			
			$api = get_option( 'vivahr_api_token_data' );

			$client_id = get_option('vivahr_client_id');
			$client_secret = get_option('vivahr_client_secret');
			
			if($api == '')
			{
				$error = true;
				$message = 'Refresh token is missing';
			}
			
			if( $error == true )
			{
				$response = array( 'error' => $error, 'message' => $message );
				return $response;
			}
				
			$api_url = $this->generate_api_url( 'oauth/token' );	
			
			$endpoint = $api_url;

            $body = [
	            'grant_type'    => 'refresh_token',
	            'refresh_token' => $api['refresh_token'],
            ];

            $body = wp_json_encode( $body );

            $options = [
	            'body'        => $body,
	            'headers'     => [
	                'Content-Type'  => 'application/json',
	                'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
	            ]
            ];

            $token_response = wp_remote_post( $endpoint, $options );

			if( $token_response['response']['code'] != 200 )
            {
				$error = true;
                $response = array( 'error' => $error, 'message' => 'Unable to refresh access token' );
				return $response;
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
					
				update_option( 'vivahr_api_token_data', $token_api_response );
				
				$error = false;
                $response = array( 'error' => $error, 'message' => 'Access Token refreshed successfully!' );
				return $response;
			}
		}
	}
}