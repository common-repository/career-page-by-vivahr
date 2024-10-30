<?php 

/**
 * Plugin Activation
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

if( !class_exists( 'VIVAHR_WP_Activation' ) )
{
    class VIVAHR_WP_Activation extends CoreController
    {
		public function init()
		{   
		    $this->add_application_form();
			$this->add_job_details_form();
			$this->add_db_tables();
			
			flush_rewrite_rules();
		}
		
		private function add_application_form()
		{
			$vivahrCommon = new VIVAHR_WP_Common();
			
			update_option( 'vivahr_application_form', $vivahrCommon->application_form(), 'no' );
		}
	
		private function add_job_details_form()
		{
			$vivahrCommon = new VIVAHR_WP_Common();
			
			update_option( 'vivahr_job_details', $vivahrCommon->job_details() );
		}
		
		private function add_db_tables()
		{
			global $wpdb;
	       			
			$vivahr_installed_db_version = get_option('vivahr_db_version');
            
			if ( $vivahr_installed_db_version != $this->plugin_version ) 
			{
				$charset_collate = $wpdb->get_charset_collate();
	
				$table_name = $wpdb->prefix . 'vivahr_location';
				$table_name_1 = $wpdb->prefix . 'vivahr_department';
				$table_name_2 = $wpdb->prefix . 'vivahr_position_type';
				$table_name_3 = $wpdb->prefix . 'vivahr_skill_level';
				$table_name_4 = $wpdb->prefix . 'vivahr_salary_type';
				$table_name_5 = $wpdb->prefix . 'vivahr_states';
				$table_name_6 = $wpdb->prefix . 'vivahr_countries';

				$sql = "CREATE TABLE IF NOT EXISTS $table_name (
	            	id mediumint(9) NOT NULL AUTO_INCREMENT,
			    	name varchar(100) DEFAULT '' NOT NULL,
			    	address varchar(100) DEFAULT '' NOT NULL,
			    	city varchar(100) DEFAULT '' NOT NULL,
			    	country varchar(100) DEFAULT '' NOT NULL,
			    	state varchar(100) DEFAULT '' NOT NULL,
			    	zip_code varchar(100) DEFAULT '' NOT NULL,
					created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL,
					deleted_at datetime DEFAULT NULL,
					status tinyint(4) DEFAULT 1,
	            	PRIMARY KEY  (id)
	            ) $charset_collate;";

	            $sql_1 = "CREATE TABLE IF NOT EXISTS $table_name_1 (
	            	id mediumint(9) NOT NULL AUTO_INCREMENT,
			    	name varchar(100) DEFAULT '' NOT NULL,
					created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL,
					deleted_at datetime DEFAULT NULL,
					status tinyint(4) DEFAULT 1,
	            	PRIMARY KEY  (id)
	            ) $charset_collate;";
				
				$sql_2 = "CREATE TABLE IF NOT EXISTS $table_name_2 (
	            	id mediumint(9) NOT NULL AUTO_INCREMENT,
			    	name varchar(100) DEFAULT '' NOT NULL,
					created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL,
					deleted_at datetime DEFAULT NULL,
					status tinyint(4) DEFAULT 1,
	            	PRIMARY KEY  (id)
	            ) $charset_collate;";
				
				$sql_3 = "CREATE TABLE IF NOT EXISTS $table_name_3 (
	            	id mediumint(9) NOT NULL AUTO_INCREMENT,
			    	name varchar(100) DEFAULT '' NOT NULL,
					created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL,
					deleted_at datetime DEFAULT NULL,
					status tinyint(4) DEFAULT 1,
	            	PRIMARY KEY  (id)
	            ) $charset_collate;";
				
				$sql_4 = "CREATE TABLE IF NOT EXISTS $table_name_4 (
	            	id mediumint(9) AUTO_INCREMENT NOT NULL ,
			    	name varchar(100) DEFAULT '' NOT NULL,
					created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL,
					deleted_at datetime DEFAULT NULL,
					status tinyint(4) DEFAULT 1,
	            	PRIMARY KEY  (id)
	            ) $charset_collate;";
				
				$sql_5 = "CREATE TABLE IF NOT EXISTS $table_name_5 (
                    state_id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    state varchar(75) NOT NULL,
                    state_short char(2) NOT NULL,
                    country char(2) NOT NULL,
                    created_at datetime DEFAULT NULL,
                    updated_at datetime DEFAULT NULL
                ) $charset_collate;";				
				
				$sql_6 = "CREATE TABLE IF NOT EXISTS $table_name_6 (
                    id bigint PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    country char(2) NOT NULL,
                    full_name varchar(100) NOT NULL DEFAULT '',
                    phone_code varchar(5) DEFAULT NULL,
                    created_on datetime DEFAULT NULL,
                    updated_on datetime DEFAULT NULL
                ) $charset_collate;";

	            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	            dbDelta( $sql );
	            dbDelta( $sql_1 );
	            dbDelta( $sql_2 );
	            dbDelta( $sql_3 );
	            dbDelta( $sql_4 );
	            dbDelta( $sql_5 );
	            dbDelta( $sql_6 );
			    
				$wpdb->insert( $table_name_2, array( 'id' => '1', 'name' => 'Full Time' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '2', 'name' => 'Part Time' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '3', 'name' => 'Contract/Temporary' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '4', 'name' => 'Internship' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '5', 'name' => 'On Call' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '6', 'name' => 'Seasonal' ) );
				$wpdb->insert( $table_name_2, array( 'id' => '7', 'name' => 'Volunteer' ) );
				
				$wpdb->insert( $table_name_3, array( 'id' => '1', 'name' => 'Student' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '2', 'name' => 'Internship' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '3', 'name' => 'Entry Level' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '4', 'name' => 'Associate' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '5', 'name' => 'Mid-Senior Level' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '6', 'name' => 'Director' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '7', 'name' => 'Executives' ) );
				$wpdb->insert( $table_name_3, array( 'id' => '8', 'name' => 'Not Applicable' ) );
				
				$wpdb->insert( $table_name_4, array( 'id' => '1', 'name' => 'Per Hour' ) );		
				$wpdb->insert( $table_name_4, array( 'id' => '2', 'name' => 'Per Week' ) );		
				$wpdb->insert( $table_name_4, array( 'id' => '3', 'name' => 'Per Month' ) );		
				$wpdb->insert( $table_name_4, array( 'id' => '4', 'name' => 'Per Year' ) );		
				$wpdb->insert( $table_name_4, array( 'id' => '5', 'name' => 'DOE' ) );		
				
				$wpdb->insert( $table_name_5, array( 'state_id' => '1', 'state' => 'Alabama', 'state_short' => 'AL', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL) );
				$wpdb->insert( $table_name_5, array( 'state_id' => '1', 'state' => 'Alabama', 'state_short' => 'AL', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL) );
	
                        $wpdb->insert( $table_name_5, array( 'state_id' => '1', 'state' => 'Alabama', 'state_short' => 'AL', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '2', 'state' => 'Alaska', 'state_short' => 'AK', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '3', 'state' => 'Arizona', 'state_short' => 'AZ', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '4', 'state' => 'Arkansas', 'state_short' => 'AR', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '5', 'state' => 'California', 'state_short' => 'CA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '6', 'state' => 'Colorado', 'state_short' => 'CO', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '7', 'state' => 'Connecticut', 'state_short' => 'CT', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '8', 'state' => 'Delaware', 'state_short' => 'DE', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '9', 'state' => 'District of Columbia', 'state_short' => 'DC', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '10', 'state' => 'Florida', 'state_short' => 'FL', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '11', 'state' => 'Georgia', 'state_short' => 'GA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '12', 'state' => 'Hawaii', 'state_short' => 'HI', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '13', 'state' => 'Idaho', 'state_short' => 'ID', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '14', 'state' => 'Illinois', 'state_short' => 'IL', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '15', 'state' => 'Indiana', 'state_short' => 'IN', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '16', 'state' => 'Iowa', 'state_short' => 'IA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '17', 'state' => 'Kansas', 'state_short' => 'KS', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '18', 'state' => 'Kentucky', 'state_short' => 'KY', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '19', 'state' => 'Louisiana', 'state_short' => 'LA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '20', 'state' => 'Maine', 'state_short' => 'ME', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '21', 'state' => 'Maryland', 'state_short' => 'MD', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '22', 'state' => 'Massachusetts', 'state_short' => 'MA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '23', 'state' => 'Michigan', 'state_short' => 'MI', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '24', 'state' => 'Minnesota', 'state_short' => 'MN', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '25', 'state' => 'Mississippi', 'state_short' => 'MS', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '26', 'state' => 'Missouri', 'state_short' => 'MO', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '27', 'state' => 'Montana', 'state_short' => 'MT', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '28', 'state' => 'Nebraska', 'state_short' => 'NE', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '29', 'state' => 'Nevada', 'state_short' => 'NV', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '30', 'state' => 'New Hampshire', 'state_short' => 'NH', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '31', 'state' => 'New Jersey', 'state_short' => 'NJ', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '32', 'state' => 'New Mexico', 'state_short' => 'NM', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '33', 'state' => 'New York', 'state_short' => 'NY', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '34', 'state' => 'North Carolina', 'state_short' => 'NC', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '35', 'state' => 'North Dakota', 'state_short' => 'ND', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '36', 'state' => 'Ohio', 'state_short' => 'OH', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '37', 'state' => 'Oklahoma', 'state_short' => 'OK', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '38', 'state' => 'Oregon', 'state_short' => 'OR', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '39', 'state' => 'Pennsylvania', 'state_short' => 'PA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '40', 'state' => 'Rhode Island', 'state_short' => 'RI', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '41', 'state' => 'South Carolina', 'state_short' => 'SC', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '42', 'state' => 'South Dakota', 'state_short' => 'SD', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '43', 'state' => 'Tennessee', 'state_short' => 'TN', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '44', 'state' => 'Texas', 'state_short' => 'TX', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '45', 'state' => 'Utah', 'state_short' => 'UT', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '46', 'state' => 'Vermont', 'state_short' => 'VT', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '47', 'state' => 'Virginia', 'state_short' => 'VA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '48', 'state' => 'Washington', 'state_short' => 'WA', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '49', 'state' => 'West Virginia', 'state_short' => 'WV', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '50', 'state' => 'Wisconsin', 'state_short' => 'WI', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '51', 'state' => 'Wyoming', 'state_short' => 'WY', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '57', 'state' => 'Virgin Islands', 'state_short' => 'VI', 'country' => 'US', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '58', 'state' => 'Alberta', 'state_short' => 'AB', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '59', 'state' => 'British Columbia', 'state_short' => 'BC', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '60', 'state' => 'Manitoba', 'MB', 'state_short' => 'CA', 'country' => NULL, 'created_at' => NULL, 'updated_at' => NULL ));                  
						$wpdb->insert( $table_name_5, array( 'state_id' => '61', 'state' => 'Newfoundland', 'state_short' => 'NL', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '62', 'state' => 'New Brunswick', 'state_short' => 'NB', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '63', 'state' => 'Nova Scotia', 'state_short' => 'NS', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '64', 'state' => 'Ontario', 'state_short' => 'ON', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '65', 'state' => 'Prince Edward Island', 'state_short' => 'PE', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '66', 'state' => 'Quebec', 'state_short' => 'QC', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '67', 'state' => 'Seskatchewan', 'state_short' => 'SK', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '68', 'state' => 'Aguascalientes', 'state_short' => 'AG', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '69', 'state' => 'Baja California', 'state_short' => 'BN', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '70', 'state' => 'Baja California Sur', 'state_short' => 'BS', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '71', 'state' => 'Campeche', 'state_short' => 'CM', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '72', 'state' => 'Chiapas', 'state_short' => 'CP', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '73', 'state' => 'Chihuahua', 'state_short' => 'CH', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '74', 'state' => 'Ciudad de México', 'state_short' => 'DF', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '75', 'state' => 'Coahuila', 'state_short' => 'CA', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '76', 'state' => 'Colima', 'state_short' => 'CL', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '77', 'state' => 'Durango', 'state_short' => 'DU', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '78', 'state' => 'Guanajuato', 'state_short' => 'GJ', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '79', 'state' => 'Guerrero', 'state_short' => 'GR', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '80', 'state' => 'Hidalgo', 'state_short' => 'HI', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '81', 'state' => 'Jalisco', 'state_short' => 'JA', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '82', 'state' => 'México', 'state_short' => 'MX', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '83', 'state' => 'Michoacán', 'state_short' => 'MC', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '84', 'state' => 'Morelos', 'state_short' => 'MR', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '85', 'state' => 'Nayarit', 'state_short' => 'NA', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '86', 'state' => 'Nuevo León', 'state_short' => 'NL', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '87', 'state' => 'Oaxaca', 'state_short' => 'OA', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '88', 'state' => 'Puebla', 'state_short' => 'PU', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '89', 'state' => 'Querétaro', 'state_short' => 'QE', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '90', 'state' => 'Quintana Roo', 'state_short' => 'QR', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '91', 'state' => 'San Luis Potosí', 'state_short' => 'SL', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '92', 'state' => 'Sinaloa', 'state_short' => 'SI', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '93', 'state' => 'Sonora', 'state_short' => 'SO', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '94', 'state' => 'Tabasco', 'state_short' => 'TB', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '95', 'state' => 'Tamaulipas', 'state_short' => 'TM', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '96', 'state' => 'Tlaxcala', 'state_short' => 'TL', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '97', 'state' => 'Veracruz', 'state_short' => 'VE', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '98', 'state' => 'Yucatán', 'state_short' => 'YU', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '99', 'state' => 'Zacatecas', 'state_short' => 'ZA', 'country' => 'MX', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '100', 'state' => 'Northwest Territories', 'state_short' => 'NT', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '101', 'state' => 'Nunavut', 'state_short' => 'NU', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
                        $wpdb->insert( $table_name_5, array( 'state_id' => '102', 'state' => 'Yukon', 'state_short' => 'YT', 'country' => 'CA', 'created_at' => NULL, 'updated_at' => NULL ));
			    
				$wpdb->insert( $table_name_6, array( 'id' => '1', 'country' => 'US', 'full_name' => 'United States', 'phone_code' => NULL, 'created_on' => '2019-01-14 10:47:48', 'updated_on' => NULL ) );	
				$wpdb->insert( $table_name_6, array( 'id' => '2', 'country' => 'CA', 'full_name' => 'Canada', 'phone_code' => NULL, 'created_on' => '2019-01-14 10:47:48', 'updated_on' => NULL ) );	
				$wpdb->insert( $table_name_6, array( 'id' => '3', 'country' => 'MX', 'full_name' => 'Mexico', 'phone_code' => NULL, 'created_on' => '2019-01-14 10:47:48', 'updated_on' => NULL ) );	
				
				update_option( "vivahr_db_version", $this->plugin_version );
			}
		}
    }
}