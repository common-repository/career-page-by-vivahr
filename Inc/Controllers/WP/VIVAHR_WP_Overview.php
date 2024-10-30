<?php namespace VIVAHR\Controllers\WP;

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
use VIVAHR\Controllers\WP\VIVAHR_WP_Jobs;

if( !class_exists( 'VIVAHR_WP_Overview') )
{
	class VIVAHR_WP_Overview
    {   
	    public function init()
		{			
			add_action( 'help_docs', array( $this, 'help_docs' ) );
			 
			$jobs = new VIVAHR_WP_Jobs();
			$core = new CoreController();
			
		    if( isset( $_GET['range'] ) && !empty( $_GET['range'] ) )
		    {
		    	$range = sanitize_text_field($_GET['range']);
				
				$allowed_ranges_array = array('week', 'month', 'year');
				
				if(!in_array($range, $allowed_ranges_array))
				{
					wp_die('Range is not allowed!');
				}
		    }
		    else
		    {
		    	$range = 'week';
		    }

			//$range_name = $core->generate_range_label($range);
			$range_name = 'This '.$range;
			
			$total_jobs = get_posts('post_type=vivahr_jobs'); 
			$total_candidates = get_posts('post_type=vivahr_candidates'); 
			
			if($range == 'week')
			{
				$args_jobs = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' ),
                            'week' => date( 'W' ),
                        ),
                    ),
                    'post_type' => 'vivahr_jobs',
                ); 
				
				$args_candidates = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' ),
                            'week' => date( 'W' ),
                        ),
                    ),
                    'post_type' => 'vivahr_candidates',
                );    
			}
			elseif($range == 'month')
			{
				$args_jobs = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' ),
                            'month' => date('m'),
                        ),
                    ),
                    'post_type' => 'vivahr_jobs',
                ); 

				$args_candidates = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' ),
                            'month' => date('m'),
                        ),
                    ),
                    'post_type' => 'vivahr_candidates',
                ); 				
			}
			elseif($range == 'year')
			{
				$args_jobs = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' )
                        ),
                    ),
                    'post_type' => 'vivahr_jobs',
                ); 

				$args_candidates = array(
                    'date_query' => array(
                        array(
                           'year' => date( 'Y' )
                        ),
                    ),
                    'post_type' => 'vivahr_candidates',
                ); 					
			}
            
            $count_jobs_diff = count(get_posts($args_jobs));
            $count_candidates_diff = count(get_posts($args_candidates));
			
			$count_jobs = count($total_jobs); 
			$count_candidates = count($total_candidates); 
             
			$results = array(
		        'jobs' => array(
			        'total'      => intval($count_jobs),
			        'diff'       => intval($count_jobs_diff),
			        'range_name' => $range_name
			    ),
			    'candidates' => array(
			        'total'      => intval($count_candidates),
				    'diff'       => intval($count_candidates_diff),
				    'range_name' => $range_name
			    ),
	            'active_jobs' => $this->get_active_cpt_vivahr_jobs()
		    );
			
			$overview = $results;
		    	
			//$add_job_link = '<a class="v-button-primary" href="post-new.php?post_type=vivahr_jobs" target="_blank">+ '.__('Add New Job', 'career-page-by-vivahr').'</a>';
            
			//add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
        
	        require_once $core->vivahr_admin_views_path.'wp/templates/vivahr_admin_header.php';
		    require_once $core->vivahr_admin_views_path.'wp/overview/vivahr_overview.php';
		
		}
		
	    public function get_active_cpt_vivahr_jobs()
		{
			$args = array('post_type' => 'vivahr_jobs');
            $posts = get_posts($args);
			
			return $posts;
		}
		
		public function help_docs()
		{
			$core = new CoreController();
			require_once $core->vivahr_admin_views_path.'wp/templates/help_docs.php';
			
		}
	
	}
}
