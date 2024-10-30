<?php namespace VIVAHR\Controllers\VIVAHR;

/**
 * The Admin functionality of the plugin.
 * 
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;

use VIVAHR\Libraries\VIVAHR_WP_Enqueue;
use VIVAHR\Libraries\VIVAHR_WP_Common;

use VIVAHR\Controllers\InitialSetupController;

use VIVAHR\Controllers\VIVAHR\VIVAHR_Overview;
use VIVAHR\Controllers\VIVAHR\VIVAHR_Settings;



if( !class_exists( 'VivahrAdminController') )
{
	class VIVAHR_AdminController extends CoreController
    {   
	    public function __construct()
		{
			$this->settings = new VIVAHR_Settings();
		}
		
	    public function init()
		{
			/*
             * Load Stylesheet and JS Files
			 *
			 * @link         
			 * @since        1.0.0
			 */	
			$enqueue = new VIVAHR_WP_Enqueue();
			add_action( 'admin_enqueue_scripts', array( $enqueue, 'admin_enqueue_styles'  ), 1 );
            add_action( 'admin_enqueue_scripts', array( $enqueue, 'admin_enqueue_scripts' ), 1 );
			
			/*
             * Generates Admin Menu
			 *
			 * @link         
			 * @since        1.0.0
			 */	
			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
			
            /*
             * INITIAL SETUP PAGE
			 *
			 * @link         
			 * @since        1.0.0
			 */
			$initialSetupController = new InitialSetupController();
			add_action( 'admin_init',           array( $initialSetupController, 'redirect_to_initial_setup' ), 3  );
			add_action( 'wp_ajax_vivahr_setup', array( $initialSetupController, 'initial_setup_submited'    ), 99 ); 
			
			/*
             * Save Company Information
			 *
			 * @link         wp-admin/admin.php?page=vivahr_settings  
			 * @since        1.0.0
			 */
			add_action( 'wp_ajax_company_information_setup', array( $this->settings, 'company_information_setup' ) );
			
			/*
             * Save API KEY
			 *
			 * @link         wp-admin/admin.php?page=vivahr_settings&section=api-key
			 * @since        1.0.0
			 */
			add_action( 'wp_ajax_api_key_setup', array( $this->settings, 'api_key_setup' ), 99 );
			
			$overview = new VIVAHR_Overview();
			add_action( 'wp_ajax_api_overview', array( $overview, 'load_overview' ), 99 ); 	
		}
		
		/**
	     * Generate admin menu and submenu tabs
	     * 
	     * @since    1.0.0
	     */
	    public function add_admin_pages() 
	    {
			$core = new CoreController();
			$vivahrCommon = new VIVAHR_WP_Common();
			$initialSetupController = new InitialSetupController();
			
            add_menu_page( 'Careers Page by VIVAHR', 'Careers Page by VIVAHR', '', 'vivahr', '', $vivahrCommon->menu_icon(), 25 );

            $submenu_tabs = $core->getSubmenuTabs();
			
            foreach( $submenu_tabs as $key => $value )
            {
				if($value['type'] == 'menu')
				{
				    add_submenu_page( 'vivahr', "$key | VIVAHR", $key, 'manage_options', $value['slug'], array( $this, $value['callback'] ), $value['position'] );
				}
            } 
			
			// Add VIVAHR_SETUP PAGE but not visible into the menu and accessible by url
			add_submenu_page( 'admin.php', 'Initial setup | VIVAHR', 'Initial setup | VIVAHR', 'manage_options', 'vivahr_setup', array( $initialSetupController, 'vivahr_setup' ) );// Add VIVAHR_SETUP PAGE but not visible into the menu and accessible by url
			add_submenu_page( 'admin.php', 'Initial setup | VIVAHR', 'Initial setup | VIVAHR', 'manage_options', 'vivahr_api_callback', array( $initialSetupController, 'vivahr_api_callback' ) );
	    }

	    /**
	     * Display Overview page for VIVAHR Customers
	     * 
	     * @since    1.0.0
	     */
	    public function vivahr_overview()
	    {
			$overview = new VIVAHR_Overview();
			$overview->init();
 		}

        /**
	     * Display Settings page for VivaHR Customers
	     * 
	     * @since    1.0.0
	     */
	    public function vivahr_settings()
	    {
			$this->settings->init();
	    }	
	}
}
