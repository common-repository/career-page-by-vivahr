<?php 

/**
 * Enqueue Stylesheet and Script Files
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
use VIVAHR\Libraries\VIVAHR_WP_Common;
use VIVAHR\Libraries\VIVAHR_WP_API;

if( !class_exists( 'VIVAHR_WP_Enqueue' ) )
{
    class VIVAHR_WP_Enqueue
    {
		public function __construct()
		{
			$this->core = new CoreController();
			
			$this->admin_css = ['vivahr_admin', 'vivahr_admin_new'];
            $this->admin_js = ['vivahr_admin', 'handlebars'];	
		}
		/**
	     * Register and enqueue the stylesheets for the admin area.
	     *
	     * @since    1.0.0
	     */
        public function admin_enqueue_styles()
        {
			$vivahrCommon = new VIVAHR_WP_Common();
			
            if( ( isset(  $_GET['page'] ) && in_array( $_GET['page'], $vivahrCommon->admin_slugs() ) ) || ( isset($_GET['post_type']) && in_array( $_GET['post_type'], $vivahrCommon->admin_slugs() ) ) || (isset($_GET['action']) && $_GET['action'] == 'edit') )
		    {    
                $cssfiles = $this->admin_css;

                if ( isset( $cssfiles ) && !empty( $cssfiles ) )
                {
                    $cssPath = $this->core->vivahrAdminAssetsPath.'/css/';

                    foreach ($cssfiles as $cssFile) 
                    {
                	    wp_register_style( 
                	        $this->core->plugin_name.'-'.$cssFile, 
                	        $cssPath.$cssFile.'.css', 
                	        array(),
                	        $this->core->plugin_version,
                	        'all'
                        );

		                wp_enqueue_style( $this->core->plugin_name.'-'.$cssFile );
                    }
                }
            }
        }
		
		/**
	     * Register and enqueue the JavaScript for the admin area.
	     *
	     * @since    1.0.0
	     */
        public function admin_enqueue_scripts()
        {
			$vivahrCommon = new VIVAHR_WP_Common();
			
			if( ( isset(  $_GET['page'] ) && in_array( $_GET['page'], $vivahrCommon->admin_slugs() ) ) || ( isset($_GET['post_type']) && in_array( $_GET['post_type'], $vivahrCommon->admin_slugs() ) ) || (isset($_GET['action']) && $_GET['action'] == 'edit') )
		    {
                $jsfiles = $this->admin_js;
                
                if ( isset( $jsfiles ) && !empty( $jsfiles ) )
                {
                    $jsPath = $this->core->vivahrAdminAssetsPath.'/js/';

                    foreach ($jsfiles as $jsFile) 
                    {
                	    wp_register_script( 
                		    $this->core->plugin_name.'-'.$jsFile, 
                            $jsPath.$jsFile.'.js', 
                            array(),
               	            $this->core->plugin_version,
               	            false
                        );

                        $script_data_array = array(
                            'url' => admin_url( 'admin-ajax.php' ),
                            'nonce' => wp_create_nonce( 'vivahr_nonce' ),
                        );

                        wp_localize_script( $this->core->plugin_name.'-'.$jsFile, 'vivahr_nonce', $script_data_array );

		                wp_enqueue_script( $this->core->plugin_name.'-'.$jsFile );
                    }
                }
            }
	    }
		
		/**
	    * Register the stylesheets for the public-facing side of the site.
	    *
	    * @since    1.0.0
	    */
        public function public_enqueue_styles()
        {
            wp_enqueue_style( 
			    $this->core->plugin_name, 
			    plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/public/css/'.$this->core->plugin_name.'_public.css', 
			    array(), 
			    $this->core->plugin_version, 
			    'all' 
	        );
        }
		
		/**
	    * Register the stylesheets for the public-facing side of the site.
	    *
	    * @since    1.0.0
	    */
        public function public_enqueue_styles_vivahr()
        {
            wp_enqueue_style( 
			    $this->core->plugin_name.'embed-style', 
			    plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/public/css/'.$this->core->plugin_name.'_embed_style.css', 
			    array(), 
			    $this->core->plugin_version, 
			    'all' 
	        );
			
			wp_enqueue_style( 
			    $this->core->plugin_name.'colorbox-style', 
			    plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/public/css/'.$this->core->plugin_name.'_colorbox.css', 
			    array(), 
			    $this->core->plugin_version, 
			    'all' 
	        );
			
        }
        
        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function public_enqueue_scripts()
        {
            wp_enqueue_script( 
                $this->core->plugin_name, 
                plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/public/js/'.$this->core->plugin_name.'_public.js', 
                array( 'jquery' ), 
                $this->core->plugin_version, 
                false 
            );
		
			wp_enqueue_script( 'jquery' );
        }
		
		public function public_enqueue_scripts_vivahr()
        {
			wp_enqueue_script( 
                $this->core->plugin_name, 
                plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/public/js/'.$this->core->plugin_name.'_embed.js', 
                array( ), 
                $this->core->plugin_version, 
                array(
                    'in_footer'  => true,
                )
            );
        }
		
		public function add_attributes_to_script( $tag, $handle, $src ) {
			
            if ( 'vivahr' === $handle ) {	

                $vivahr_career_type = get_option('vivahr_career_type');
				
				$allowed_types = array('jobs', 'culture');
					
				if ( in_array( $vivahr_career_type, $allowed_types ) )
				{
					$vivahr_career_type = $vivahr_career_type;
				}
				else
				{
					$vivahr_career_type = 'culture';
				}
					
                $vivahrAPI = new VIVAHR_WP_API();
			    $vivahrAPI->refresh_api_access_token();
			
			    $api_url = $vivahrAPI->generate_api_url('wordpress/career');
        	    $api_token_data = get_option('vivahr_api_token_data');
			
                $args = array(
                    'headers' => array(
                       'Authorization' => sanitize_text_field($api_token_data['token_type']).' '.sanitize_text_field($api_token_data['access_token'])
                    )
                );
			
                $response = wp_remote_get( $api_url, $args );
			   
                if( $response['response']['code'] == 200 )
			    {
                    $array_response = json_decode($response['body'], true);
					
                    $guid = $array_response['data']['guid'];
                    
					$tag = '<script src="https://jobs.vivahr.com/assets/js/jquery-3.7.1.min.js"></script><script id="srcid" data-id="' .$guid. '" type="text/javascript" src="' . esc_url( $src ) . '" data-type="' .esc_html($vivahr_career_type). '" ></script>';
                }
            	
            } 
			
            return $tag;
        }	

    }
}