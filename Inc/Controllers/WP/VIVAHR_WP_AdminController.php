<?php namespace VIVAHR\Controllers\WP;

/**
 * Admin functionality of the plugin
 * This section is intended for not registered VIVAHR users
 * 
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers/WP
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;

use VIVAHR\Libraries\VIVAHR_WP_Enqueue;
use VIVAHR\Libraries\VIVAHR_WP_Common;

use VIVAHR\Controllers\InitialSetupController;

use VIVAHR\Controllers\WP\VIVAHR_WP_Overview;

use VIVAHR\Controllers\WP\VIVAHR_WP_Settings;

use VIVAHR\Controllers\WP\VIVAHR_WP_Jobs;


if( !class_exists( 'VIVAHR_WP_AdminController') )
{
	class VIVAHR_WP_AdminController
    {   
	    public function __construct()
		{
			$this->settings = new VIVAHR_WP_Settings();
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
			add_action( 'admin_init', array( $initialSetupController, 'redirect_to_initial_setup' ), 3  );
			add_action( 'wp_ajax_vivahr_setup', array( $initialSetupController, 'initial_setup_submited' ) ); 
			
			
			/*************************************************************************************************
            ** Jobs/Applicants actions
            *************************************************************************************************/
			$this->jobs_actions();

            /*************************************************************************************************
            ** AJAX Settings Functions
            *************************************************************************************************/
		    
				    /*
                     * Add Location
			         *
			         * @link         
			         * @since        1.0.0
			         */
					add_action( 'wp_ajax_add_location_setup', array( $this->settings, 'add_location_setup' ),99 ); 
			
					/*
                     * Add Department
			         *
			         * @link         
			         * @since        1.0.0
			         */
					add_action( 'wp_ajax_add_department_setup', array( $this->settings, 'add_department_setup' ),99 );
				
					/*
                     * Edit Location
			         *
			         * @link         
			         */
			        add_action( 'wp_ajax_edit_location_setup', array( $this->settings, 'edit_location_setup' ),99 );
			
					/*
                     * Edit Department
			         *
			         * @link         
			         */					
					add_action( 'wp_ajax_edit_department_setup', array( $this->settings, 'edit_department_setup' ),99 );
				
					/*
                     * Delete Location
			         *
			         * @link         
			         * @since        1.0.0
			         */
					add_action( 'wp_ajax_job-details-delete-location', array( $this->settings, 'delete_location' ) );
				
					/*
                     * Delete Department
			         *
			         * @link         
			         * @since        1.0.0
			         */
					add_action( 'wp_ajax_job-details-delete-department', array( $this->settings, 'delete_department' ) );
				
			        /*
                     * Save/Update Job details form
			         *
			         * @link         http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_job_details_setup', array( $this->settings, 'job_details_setup' ),99 );
				
				    /*
                     * Loads Job details sections/forms
			         *
			         * @link         http://wp.vivahr.local/wp-admin/admin.php?page=vivahr_settings&section=job-details
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_job_details_section', array( $this->settings, 'load_job_details_section' ) );				
				
				    /*
                     * Save Company Information
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_company_information_setup', array( $this->settings, 'company_information_setup' ) );	
				
					/*
                     * Save API KEY
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_api_key_setup', array( $this->settings, 'api_key_setup' ) );	
				
					/*
                     * Delete Company Logo
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_delete-logo', array( $this->settings, 'delete_company_logo' ) );
				
				    /*
                     * Return states based on selected country
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_filter-states', array( $this->settings, 'getStatesFromCountry' ) );	
				
					/*
                     * Manage save application form
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_application-form-submit', array( $this->settings, 'application_form_save' ) );	
				
					/*
                     * Manage save application form
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_notifications_setup', array( $this->settings, 'notifications_setup_save' ) );	
				
					/*
                     * Manage save application form
			         *
			         * @link         
			         * @since        1.0.0
			         */
			        add_action( 'wp_ajax_hr_notifications_setup', array( $this->settings, 'hr_notifications_setup_save' ) );	
			
			
			/*************************************************************************************************
            ** Hide edit and quick edit button from Job Openings CPT
            *************************************************************************************************/
            add_action( 'admin_head', array( $this,'posttype_admin_css' ) ); 
		    
			add_action( 'vivahr_admin_nav_links', array( $this, 'admin_nav_links'), 10, 1 );
		}
		
		/**
	     * Generate admin menu and submenu tabs for Not VIVAHR Customers
	     * 
	     * @since    1.0.0
	     */
	    public function add_admin_pages() 
	    {		
	        $core = new CoreController();
			$vivahrCommon = new VIVAHR_WP_Common();
			$initialSetupController = new InitialSetupController();
			
            add_menu_page( 'Career Page by VIVAHR', 'Career Page by VIVAHR', 'manage_options', 'vivahr', '', $vivahrCommon->menu_icon(), 25 );

		    $submenu_tabs = $core->getSubmenuTabs();

            foreach( $submenu_tabs as $key => $value )
            {
				if($value['type'] == 'menu')
				{
				    add_submenu_page( 'vivahr', "$key | VIVAHR", $key, 'manage_options', $value['slug'], array( $this, $value['callback'] ), $value['position'] );
				}
            } 
			
			// Add VIVAHR_SETUP PAGE but not visible into the menu and accessible by url
			add_submenu_page( 'admin.php', 'Initial setup | VIVAHR', 'Initial setup | VIVAHR', 'manage_options', 'vivahr_setup', array( $initialSetupController, 'vivahr_setup' ) );
			add_submenu_page( 'admin.php', 'Initial setup | VIVAHR', 'Initial setup | VIVAHR', 'manage_options', 'vivahr_api_callback', array( $initialSetupController, 'vivahr_api_callback' ) );
	    }
		
		/**
	     * Display Overview page for VivaHR and non VivaHR customers
	     * 
	     * @since    1.0.0
	     */
	    public function vivahr_overview()
	    {
		    //	$jobs = new VIVAHR_WP_Jobs();
			$overview = new VIVAHR_WP_Overview();
		    $overview->init();
	    }
		
		/**
	     * Display Settings page for Non VivaHR Customers
	     * 
	     * @since    1.0.0
	     */
	    public function admin_settings() 
	    {
		    $this->settings->init();
		}	
		
		public function jobs_actions()
		{
			$jobs = new VIVAHR_WP_Jobs();
			
			/*
             * Show VIVAHR navigation menu for Job Openings and Applicants CPT
			 *
			 * @link         
			 * @since        1.0.0
			 */
            add_action( 'in_admin_header', array( $jobs, 'nav_header' ) );
			
			/*
             * Adds Meta Box in add/edit job opening
			 *
			 * @link         
			 * @since        1.0.0
			 */
			add_action( 'add_meta_boxes', array( $jobs, 'add_meta_box' ) );
			
            /*
             * Manage Save JOB Opening Post
			 *
			 * @link         
			 * @since        1.0.0
			 */	
            add_action( 'wp_insert_post_data', array( $jobs, 'validateMetaBoxesFields' ), 99, 2 );	
			
		}
		
		/* public function settings_actions()
		{
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) ) 
			{ 
				
			}
		} */
		
        public function posttype_admin_css() 
		{
            global $post_type;
			
            if( $post_type == 'vivahr_candidates' ) 
			{
                echo '<style type="text/css">.edit, .button-link.editinline{display: none;}</style>';
            }
        }	

       /*  public function admin_nav_links( $page )
		{
			$core = new CoreController();
			
			$admin_tabs = $core->admin_menu_tabs( $page );

			foreach( $admin_tabs as $admin_tab )
            {
	            echo esc_html($admin_tab);
            }
		}		 */
	}
}
