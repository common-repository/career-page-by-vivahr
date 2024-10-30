<?php namespace VIVAHR\Controllers\VIVAHR;

/**
 * The public-facing functionality of the plugin for registered customers
 * 
 * @link       
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers/VIVAHR
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;

use VIVAHR\Libraries\VIVAHR_WP_Enqueue;

if( !class_exists( 'VIVAHR_PublicController' ) )
{
	class VIVAHR_PublicController extends CoreController
	{
	    public function init()
		{
		    /*
             * Load Stylesheet and JS Files
			 *
			 * @link         
			 * @since        1.0.0
			 */		
			$enqueue = new VIVAHR_WP_Enqueue();
			add_action( 'wp_enqueue_scripts', array( $enqueue, 'public_enqueue_styles_vivahr'  ) );
            add_action( 'wp_enqueue_scripts', array( $enqueue, 'public_enqueue_scripts_vivahr' ) );
			
            add_filter('script_loader_tag', array( $enqueue, 'add_attributes_to_script' ), 10, 3); 
		
			/*
             * Load Career page code via API Call
			 *
			 * @link         
			 * @since        1.0.0
			 */		
            add_action( 'init', array( $this, 'shortcode' ) );
		}
	
        /**
         * Replace the shortcode in WP page with career page
         * 
         * @since    1.0.0
         */
        public function shortcode()
        {
            add_shortcode( $this->vivahr_config_item('career_page_shortcode'), array( $this, 'show_vivahr_career_page' ) );
        }

        /**
         * Replace the shortcode in WP page with vivahr career page scripts and show list of jobs
         * 
         * @since    1.0.0
         */
        public function show_vivahr_career_page()
        {
			return '<div id="profileEmbed"></div>';
        }
	
	}
}