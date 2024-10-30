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

if( !class_exists( 'VIVAHR_Overview') )
{
	class VIVAHR_Overview
    {   
	    public function init()
		{	
		    $core = new CoreController();
			
          //  add_action('menu_tabs', array($core, 'generate_menu_tabs'));
			
			// Load HandleBars templates
            add_action('overview_box_1', array($this, 'load_overview_1'));
		
            require_once $core->vivahr_admin_views_path.'vivahr/templates/vivahr_admin_header.php';
			require_once $core->vivahr_admin_views_path.'vivahr/overview/vivahr_overview.php';
		}
		
		public function load_overview_1()
		{
			$core = new CoreController();
			require_once $core->vivahr_admin_views_path.'vivahr/overview/vivahr_overview_box_1.php';
		}
		
		public function load_overview()
		{
			
			check_ajax_referer( 'overview_details', 'nonce' );
			
			$core = new CoreController();
			$vivahrAPI = new VIVAHR_WP_API();
			$range = 'week';
			$allowed_ranges = array('week', 'month', 'year');
			
		    if( isset( $_POST['range'] ) && !empty( $_POST['range'] ) )
		    {	
		       $selected_range = sanitize_text_field($_POST['range']);
		    }
			
			if( in_array( $selected_range, $allowed_ranges ) )
			{
				$range = $selected_range;
			}
			
			// Firstly refresh access token
			$refresh_access_token = $vivahrAPI->refresh_api_access_token();
			
			if( empty( $refresh_access_token ) )
			{
				$response = array('error' => true, 'message' => 'Unable to get a response from API');
				wp_send_json($response);
			}

			$api_url = $vivahrAPI->generate_api_url('wordpress/overview?range='.$range);
        	$api_token_data = get_option('vivahr_api_token_data');
			
            $args = array(
                'headers' => array(
                   'Authorization' => $api_token_data['token_type'].' '.$api_token_data['access_token']
                )
            );
			
            $response = wp_remote_get( $api_url, $args );
			
            if( $response['response']['code'] == 200 )
            {
                $overview = json_decode($response['body'], true);
            }
			
			wp_send_json($overview);
			
		}
	}
}
