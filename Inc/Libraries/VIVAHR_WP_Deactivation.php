<?php 

/**
 * Plugin Deactivation
 *
 * @link      
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Libraries
 */
namespace VIVAHR\Libraries;

defined('ABSPATH') or die();

use VIVAHR\Controllers\WP\VIVAHR_WP_Jobs;
if( !class_exists( 'VIVAHR_WP_Deactivation' ) )
{
    class VIVAHR_WP_Deactivation
    {
		public function init()
		{
			$this->reset_application_form();
			
			$this->reset_job_details_form();
			$this->reset_initial_setup_status();
			
			$jobs = new VIVAHR_WP_Jobs();
			$jobs->unregister_vivahr_jobs_post_type();
				
		    update_option( 'vivahr_application_form', '' );
			
			
			flush_rewrite_rules();
		}
		
		private function reset_application_form()
		{
			update_option( 'vivahr_application_form', '' );
		}

		private function reset_job_details_form()
		{
			update_option( 'vivahr_job_details', '' );
		}
		
		private function reset_initial_setup_status()
		{
			update_option('vivahr_setup', 0);
		}
		
    }
}