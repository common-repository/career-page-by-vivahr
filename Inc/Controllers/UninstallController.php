<?php namespace VIVAHR\Controllers;

/**
 * Uninstall Controller
 * 
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Controllers
 */
 
defined('ABSPATH') OR exit('No direct script access allowed');

if( !class_exists( 'UninstallController' ) )
{
    class UninstallController
	{
		public function init()
		{
			$this->remove_career_page();
			$this->remove_company_logo();
			$this->remove_options();
			$this->remove_meta_data();
			$this->remove_posts();
			$this->remove_db_tables();
		}
		
		private function remove_career_page()
		{
			$page_id = get_option('vivahr_jobs_listing_page');
			
			if( isset( $page_id ) && !empty( $page_id ) )
			{
				if(is_numeric($page_id))
				{
					wp_delete_post($page_id);
				}
			}
			
		}
		
		private function remove_company_logo()
		{
			// Get Current Logo so we can delete the file
			$company_logo = get_option('vivahr_company_logo');
			
			// Just check but should not happen
			if( isset($company_logo) && empty($company_logo))
			{
				return;
			}
			
			if( file_exists( $company_logo['file'] ) )
			{
				unlink($company_logo['file']);
				
			}
			
			return;
		}
		
		private function remove_options()
		{
			$options = array(
			    'vivahr_application_form',
				'vivahr_job_details',
				'vivahr_setup',
				'vivahr_jobs_listing_page',
				'vivahr_jobs_listing_page_latest',
				'vivahr_api_token_data',
				'vivahr_business_name',
				'vivahr_business_address',
				'vivahr_business_phone',
				'vivahr_business_email',
				'vivahr_business_website',
				'vivahr_url_slug',
				'vivahr_company_logo',
				'vivahr_db_version',
				'vivahr_company_name',
				'vivahr_hr_email_address',
				'vivahr_applicant_notifications',
				'vivahr_hr_notifications',
				'vivahr_career_type',
				'vivahr_client_id',
				'vivahr_client_secret',
				'vivahr_redirect_uri'
			);
			
			foreach($options as $option)
			{
				delete_option($option);
			}
		}
		
		private function remove_posts()
		{
			global $wpdb;
		
		    $wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN( 'vivahr_jobs', 'vivahr_candidates' );" );

		}
		
		private function remove_meta_data()
		{
            global $wpdb;

            $meta_key = 'vivarh_applicant_additional';
            $values = $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s" , $meta_key) );
    
			$uploads = wp_upload_dir();
            $upload_path = $uploads['basedir'].$uploads['subdir'];
            $siteUrl = $uploads['url'];
			
            foreach($values as $value)
            {
	            $value = unserialize($value->meta_value);
	
	            $resume = str_replace($siteUrl, $upload_path, $value['post_resume']);
		        unlink($resume);
            }
			
			delete_post_meta_by_key( 'vivahr_job_details' );
			delete_post_meta_by_key( 'vivarh_applicant_additional' );
		}
		
		private function remove_db_tables()
		{
			global $wpdb;
			
			
            $tables = array(
			    $wpdb->prefix . 'vivahr_location',
				$wpdb->prefix . 'vivahr_department',
				$wpdb->prefix . 'vivahr_position_type',
				$wpdb->prefix . 'vivahr_skill_level',
				$wpdb->prefix . 'vivahr_salary_type',
				$wpdb->prefix . 'vivahr_states',
				$wpdb->prefix . 'vivahr_countries',
		    );	
				
			foreach($tables as $table)
			{
				$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %i", $table ) );
			}
		}	
	}
}