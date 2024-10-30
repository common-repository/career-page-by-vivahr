<?php defined('ABSPATH') OR exit('No direct script access allowed');

/**
 * Career Page by VIVAHR
 *
 * @package           VIVAHR
 * @author            https://vivahr.com
 * @copyright         2024 VIVAHR
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Career Page by VIVAHR
 * Plugin URI:        
 * Description:       The Career Page by VIVAHR plugin offers a straightforward solution for creating a job listings page on your WordPress website, combining simplicity with powerful functionality.
 * Version:           1.0.6
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            VIVAHR
 * Author URI:        https://vivahr.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       career-page-by-vivahr
 */

 if( file_exists( dirname( __FILE__ ). '/vendor/autoload.php' ) ) 
 {
	require_once dirname( __FILE__ ). '/vendor/autoload.php';
 }

 defined( 'VIVAHR_PLUGIN_BASENAME' ) || define( 'VIVAHR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/*
 | --------------------------------------------------------------------
 | VIVAHR Plugin URL
 | http://wp.vivahr.local/wp-content/plugins
 | --------------------------------------------------------------------
 */
 defined( 'VIVAHR_PLUGIN_URL' ) || define( 'VIVAHR_PLUGIN_URL',  plugin_dir_url(  __FILE__, ) );
 
/*
 | --------------------------------------------------------------------
 | VIVAHR Plugin Directory URL
 | D:\Ampps\www\wp.vivahr.local\wp-content\plugins
 | --------------------------------------------------------------------
 */
 defined( 'VIVAHR_PLUGIN_DIR_URL' ) || define( 'VIVAHR_PLUGIN_DIR_URL' , plugin_dir_path(  __FILE__ ) );
 
/*
 | --------------------------------------------------------------------
 | VIVAHR APP URL
 | --------------------------------------------------------------------
 */
 defined( 'VIVAHR_APP_URL' ) || define( 'VIVAHR_APP_URL', 'https://app.vivahr.com/' );
 
/*
 * VIVAHR Plugin Core Controller
 *
 * @since    1.0.0
 */
use VIVAHR\Controllers\CoreController;

/*
 * VIVAHR ACTIVATION/DEACTIVATION
 *
 * @since    1.0.0
 */
use VIVAHR\Libraries\VIVAHR_WP_Activation;
use VIVAHR\Libraries\VIVAHR_WP_Deactivation;

/*
 * VIVAHR CUSTOMERS
 *
 * @since    1.0.0
 */
use VIVAHR\Controllers\VIVAHR\VIVAHR_AdminController;
use VIVAHR\Controllers\VIVAHR\VIVAHR_PublicController;

/*
 * WP USERS
 *
 * @since    1.0.0
 */
use VIVAHR\Controllers\WP\VIVAHR_WP_AdminController;
use VIVAHR\Controllers\WP\VIVAHR_WP_PublicController;

use VIVAHR\Controllers\WP\VIVAHR_WP_Jobs;
use VIVAHR\Controllers\WP\VIVAHR_WP_Applications;

if ( ! class_exists( 'Vivahr' ) ) 
{
    class Vivahr extends CoreController
    {	    
		/**
         * Initialize Plugin.
         *
         * @since    1.0.0
         */
	    public function init()
		{   	
		    /*
             * Adds additional links in plugin overview
			 *
             * @since    1.0.0
			 */
			add_filter( 'plugin_action_links_' . VIVAHR_PLUGIN_BASENAME, array( $this, 'plugin_status_setup_link' ), 99 );
		
		    if( is_admin() )
            {
				if( $this->vivahr_plugin_type == 0 )
				{
					$admin = new VIVAHR_WP_AdminController();
			        $admin->init();  
				}
				else
				{
					$admin = new VIVAHR_AdminController();
			        $admin->init();  
				}   
			}
			else
            {
				if( $this->vivahr_plugin_type == 0 )
				{
					$public = new VIVAHR_WP_PublicController();
                    $public->init();
				}
				else
				{
					$public = new VIVAHR_PublicController();
                    $public->init();
				}   
            }

			if( $this->vivahr_plugin_type == 0 )
			{
				/*
                * Run JOBS CPT
			    *
			    * @link         
			    * @since        1.0.0
			    */
			    $jobs= new VIVAHR_WP_Jobs();
			    add_action( 'init', array( $jobs, 'init' ), 3  );
				
		    }
		} 
    }

    /**
     * Run Plugin
     * 
     * @since    1.0.0
     */
	$vivahr = new Vivahr();
    $vivahr->init();

    /**
     * Activate Plugin
     * 
     * @link  
     * @since    1.0.0
     */
	$vivahrWpActivation = new VIVAHR_WP_Activation();
    register_activation_hook( __FILE__, array( $vivahrWpActivation, 'init' ) );

    /**
     * Deactivate Plugin
     * 
     * @link  
     * @since    1.0.0
     */
	$vivahrWpDeactivation = new VIVAHR_WP_Deactivation();
    register_deactivation_hook( __FILE__, array( $vivahrWpDeactivation, 'init' ) );

}