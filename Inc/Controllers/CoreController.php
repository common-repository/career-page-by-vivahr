<?php namespace VIVAHR\Controllers;

/**
 * Core Controller
 * 
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers
 */
 
defined('ABSPATH') OR exit('No direct script access allowed');

if( !class_exists( 'CoreController' ) )
{
    class CoreController
    {
		public $vivahr_plugin_type;
	    public $plugin_name;
	    public $plugin_version;
		public $vivahr_admin_views_path;
		public $vivahrAdminAssetsPath;
	
		
        public function __construct() 
        {
			$this->vivahr_plugin_type = $this->define_plugin_type();

		 	$this->plugin_name = $this->vivahr_config_item('plugin_name');
            
			$this->plugin_version = $this->vivahr_config_item('plugin_version');
			
			$this->vivahr_admin_views_path = VIVAHR_PLUGIN_DIR_URL.'views/admin/';
			
			$this->vivahrAdminAssetsPath = VIVAHR_PLUGIN_URL.'assets/admin';
        }
		
		/**
	     * Defining the Plugin Type
		 * api_token = 0 -> Not Vivahr Customer
		 * api_token = 1 -> Vivahr Customer
	     *
	     * @since    1.0.0
	     */
		public function define_plugin_type()
		{
			$api_token = get_option( 'vivahr_api_token_data' );
			
			if( empty( $api_token ) )
			{
				$plugin_type = 0;
			}
			else
			{
				$plugin_type = 1;
			}
			
			return $plugin_type;
		}
		
		/* public function generate_menu_tabs()
	    {
			$core = new CoreController();
			
			$allowed_pages = array('vivahr_overview', 'vivahr_settings');
			$current_page = sanitize_text_field($_GET['page']);
			
			if(in_array($current_page, $allowed_pages))
			{
				$admin_tabs = $core->admin_menu_tabs($current_page);
		        foreach($admin_tabs as $admin_tab)
                {
					$allowed_html = [
                        'li' => [
                            'class' => true,
                        ],
                        'a' => [
						    'class' => true,
                            'href'  => true,
							'target'=> true
						],
                    ]; 
                    $clear_admin_tabs = wp_kses( $admin_tab, $allowed_html );
					
					echo $clear_admin_tabs;
                }
			}
			else
			{
			    wp_die('');	
			}
		    
	    } */
        
		/**
	     * Generate VIVAHR WP Admin Submenu Tabs
		 * 
	     *
	     * @since    1.0.0
	     */
        public function getSubmenuTabs()
        {
            if( $this->vivahr_plugin_type == 0 )
            {
                $submenu_array = $this->vivahr_config_item('submenu_non_vivahr');
            }
            else
            {
                $submenu_array = $this->vivahr_config_item('submenu_vivahr');
            }
        
            return $submenu_array;
        }
        
/* 		public function admin_menu_tabs($current_page)
	    {
		    $vivahr_header_menu_tabs = $this->getSubmenuTabs();
		
		    $admin_tabs = array();
		    foreach( $vivahr_header_menu_tabs as $key => $value )
		    {
				if( $value['type'] == 'menu' )
				{
					$slug = 'admin.php?page='.$value['slug'].'';
				}
				else
				{
					$slug = 'edit.php?post_type='.$value['slug'].'';
				}
			    
				$link = '<li class="nav-item '.((''.$value['slug'] == $current_page) ? 'active' : '').'"><a class="nav-link" href="'.$slug.'">'.ucfirst($key).'</a></li>';
				
				$allowed_html_element = [
                    'li' => [
                        'class' => true
                    ],
					'a' => [
					    'class' => true,
						'href'  => true
					]
                ]; 
				
                $clean_link = wp_kses( $link, $allowed_html_element );
			
			    $admin_tabs[] = $clean_link;
		     
			}
			
			if( !empty( get_option('vivahr_jobs_listing_page') ) )
		    {
				$post_id = get_option('vivahr_jobs_listing_page');
				
				if ( !is_numeric($post_id) )
				{
					
				    $admin_tabs[] .= '';
				}
				else
				{
					$post = get_post( $post_id );
			    
                    if( !empty($post) )
				    {
				
						$link = '<li class="nav-item"><a class="nav-link vivahr-external-link-secondary" target="_blank" href="'.sanitize_text_field($post->post_name).'">'.esc_html('View Career Page', 'career-page-by-vivahr').'</a></li>';
					
				        $allowed_html_element = [
                            'li' => [
                                'class' => true
                            ],
					        'a' => [
					            'class' => true,
					        	'href'  => true,
					        	'target' => true
					        ]
                        ]; 
				
                        $clean_link = wp_kses( $link, $allowed_html_element );
			
			            $admin_tabs[] .= $clean_link;
					
				    }		
				}
		
		    }
			
			if( $this->vivahr_plugin_type == 1 )
		    {
				$link = '<li class="nav-item"><a class="nav-link vivahr-external-link-primary" target="_blank" href="'.esc_url(VIVAHR_APP_URL).'">'.esc_html('Take me to my VIVAHR Account', 'career-page-by-vivahr').'</a></li>';
				
					$allowed_html_element = [
                    'li' => [
                        'class' => true
                    ],
					'a' => [
					    'class' => true,
						'href'  => true,
						'target' => true
					]
                ];
				
                $clean_link = wp_kses( $link, $allowed_html_element );
			
			    $admin_tabs[] .= $clean_link;
		    } 
			
		    return $admin_tabs;
	    }  */
		
		/**
	     * Show initial setup list in plugins list
	     *
	     * @since    1.0.0
	     */
		public function plugin_status_setup_link ( $actions ) 
	    { 
			$links = array(
                '<a href="' . admin_url( 'admin.php?page=vivahr_setup' ) . '">'.esc_html('Initial Setup', 'career-page-by-vivahr').'</a>'
            );
            
			$actions = array_merge( $actions, $links );
            
			return $actions;
        }
		
		/**
	     * Get Config data
		 * /includes/Config.php
	     * @since    1.0.0
	     */
        public function &get_config(Array $replace = array())
        {
            static $vivahr_config;

            if (empty($vivahr_config))
            {
                $file_path = VIVAHR_PLUGIN_DIR_URL.'/config/Config.php';
                $found = FALSE;
            
                if (file_exists($file_path))
                {
                    $found = TRUE;
                    require($file_path);
                }

                // Does the $config array exist in the file?
                if ( ! isset($vivahr_config) OR ! is_array($vivahr_config))
                {
                    //set_status_header(503);
                    echo 'Your config file does not appear to be formatted correctly.';
                    exit(3); // EXIT_CONFIG
                }
            }

            // Are any values being dynamically added or replaced?
            foreach ($replace as $key => $val)
            {
                $vivahr_config[$key] = $val;
            }

           return $vivahr_config;
       }

		/**
	     * Get single config item from
		 * $this->vivahr_config_item($item_name)
		 * /includes/Config.php
	     * @since    1.0.0
	     */
        public function vivahr_config_item($item)
        {
            static $_vivahr_config;

            if (empty($_vivahr_config))
            {
                // references cannot be directly assigned to static variables, so we use an array
                $_vivahr_config[0] =& $this->get_config();
            }

            return isset($_vivahr_config[0][$item]) ? $_vivahr_config[0][$item] : NULL;
        }
		
    }
}