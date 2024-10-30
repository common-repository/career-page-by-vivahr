<?php 

/**
 * Common values/settings
 *
 * @link      
 * @since      1.0.0
 *
 * @package    vivahr
 * @subpackage vivahr/Inc/Libraries
 */
namespace VIVAHR\Libraries;

defined('ABSPATH') or die();

if( !class_exists( 'VIVAHR_WP_Common' ) )
{
    class VIVAHR_WP_Common
    {
		public function application_form()
		{
			$applicationForm = '[{"label":"Name","name":"name","type":"text","required":"required","disabled":"no"},{"label":"Email","name":"email","type":"email","required":"required","disabled":"no"},{"label":"Resume","name":"resume","type":"file","required":"","disabled":"no"},{"label":"Cover Letter","name":"coverletter","type":"textarea","required":"","disabled":"no"},{"label":"Phone number","name":"phone","type":"tel","required":"","disabled":"no"},{"label":"Address","name":"applicant_address","type":"text","required":"","disabled":"no"},{"label":"LinkedIn URL","name":"linkedin","type":"url","required":"","disabled":"no"},{"label":"Portfolio URL","name":"portfolio","type":"url","required":"","disabled":"no"},{"label":"Website URL","name":"website","type":"url","required":"","disabled":"no"}]';
			
			return $applicationForm;
		}

        public function job_details()
		{
			$jobDetailsForm = array(
			    'vivahr_position_type' => array(
				    'field_title' => __('Position Type', 'career-page-by-vivahr'),
				    'required'    => 1
				),			    
				'vivahr_skill_level' => array(
				    'field_title' => __('Skill Level', 'career-page-by-vivahr'),
				    'required'    => 0
				),				
				'vivahr_salary_type' => array(
				    'field_title' => __('Salary Type', 'career-page-by-vivahr'),
				    'required'    => 0
				),				
				'vivahr_salary_range' => array(
				    'field_title' => __('Salary Range', 'career-page-by-vivahr'),
				    'required'    => 0
				),				
				'vivahr_department' => array(
				    'field_title' => __('Department', 'career-page-by-vivahr'),
				    'required'    => 0
				),				
				'vivahr_location' => array(
				    'field_title' => __('Location', 'career-page-by-vivahr'),
				    'required'    => 0
				),
			);
			
			return $jobDetailsForm;
		}

        public function admin_slugs()
		{
			$adminSlugs = array(
			    'vivahr_overview', 
				'vivahr_settings', 
				'vivahr_setup', 
				'vivahr_jobs', 
				'vivahr_candidates',
				'vivahr_api_callback' 
			);
			
			return $adminSlugs;
		}	

        public function menu_icon()
		{
            //The icon in Base64 format
            $icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iMTM1LjAwMDAwMHB0IiBoZWlnaHQ9IjEzNS4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDEzNS4wMDAwMDAgMTM1LjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgoKPGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMC4wMDAwMDAsMTM1LjAwMDAwMCkgc2NhbGUoMC4xMDAwMDAsLTAuMTAwMDAwKSIKZmlsbD0iIzAwMDAwMCIgc3Ryb2tlPSJub25lIj4KPHBhdGggZD0iTTUwMiAxMzAxIGMtMTE0IC0zMyAtMTkwIC03OSAtMjgyIC0xNzEgLTEzOCAtMTM4IC0xOTIgLTI2NiAtMTkzCi00NTUgMCAtOTIgNSAtMTIzIDI2IC0xOTAgMzUgLTEwNiA4OCAtMTk0IDE2MyAtMjY5IDI0OSAtMjQ4IDYzOSAtMjU1IDg5NgotMTggMTM5IDEyOSAyMDggMjg3IDIwOCA0NzcgMCAxMTEgLTE4IDE4NSAtNzIgMjk1IC03NyAxNTggLTI0OCAyOTQgLTQxOSAzMzUKLTg4IDIwIC0yNDcgMTggLTMyNyAtNHogbTMwMyAtMjYgYzExNSAtMjQgMjA4IC03NiAzMDEgLTE2OSA5MiAtOTIgMTQ0IC0xODYKMTY5IC0zMDMgNTcgLTI2OCAtNzUgLTU1MSAtMzE1IC02NzMgLTEyOCAtNjUgLTI5MiAtODcgLTQyMyAtNTYgLTI4MiA2NiAtNDc3CjMxMiAtNDc3IDYwMSAwIDEwNiAyMSAxOTAgNzIgMjg4IDEyNiAyNDEgNDAyIDM2OSA2NzMgMzEyeiIvPgo8cGF0aCBkPSJNNDAzIDk2MyBjLTEzIC0yIC0yMyAtNiAtMjMgLTcgMCAtMiA1MSAtMTI0IDExNCAtMjcyIGwxMTQgLTI2OCAyNgo1OSBjMTUgMzIgMjkgNjggMzIgNzkgMyAxMyAtMjggOTcgLTc5IDIxNiBsLTg0IDE5NSAtMzkgMSBjLTIxIDEgLTQ5IDAgLTYxCi0zeiIvPgo8cGF0aCBkPSJNOTE3IDk2MSBsLTM5IC02IC0xMjUgLTI5MyBjLTEyMCAtMjgxIC0xMjQgLTI5NCAtMTA5IC0zMTcgMjEgLTMxCjUyIC00MCA3OSAtMjIgMTQgOSA0NCA2NSA3OSAxNDggMzEgNzQgOTMgMjE2IDEzNiAzMTcgbDgwIDE4MiAtMzEgLTEgYy0xOCAtMQotNDkgLTUgLTcwIC04eiIvPgo8L2c+Cjwvc3ZnPgo=';

            return "data:image/svg+xml;base64,$icon_base64";

		}	

        public function initial_setup_fields()
		{
			$fields = array(
                'vivahr_company_name' => array(
                    'title'       => __( 'Company Name', 'career-page-by-vivahr' ),
                    'type'        => 'input',
                    'subtype'     => 'text',
                    'placeholder' => __( 'Company Name', 'career-page-by-vivahr' ),
				    'sanitize'    => 'sanitize_text_field',
				    'required'    => true
                ),   
                'vivahr_hr_email_address' => array(
                    'title'       => __( 'HR Email Address', 'career-page-by-vivahr' ),
                    'type'        => 'input',
                    'subtype'     => 'email',
                    'placeholder' => __( 'HR Email Address', 'career-page-by-vivahr' ),
				    'sanitize'    => 'sanitize_email',
				    'required'    => true
                ),    
                'page_id' => array(
                    'title'       => __( 'Jobs Listing Page', 'career-page-by-vivahr' ),
                    'type'        => 'select',
                    'subtype'     => 'select',
                    'placeholder' => __( 'Jobs Listing Page', 'career-page-by-vivahr' ),
				    'sanitize'    => 'intval',
				    'required'    => false
                ),
			    'vivahr_client_id'  => array(
                    'title'       => __( 'Client ID', 'career-page-by-vivahr' ),
                    'type'        => 'input',
                    'subtype'     => 'text',
                    'placeholder' => __( 'Client ID', 'career-page-by-vivahr' ),
				    'sanitize'    => '',
				    'required'    => false
                ),
			    'vivahr_client_secret'  => array(
                    'title'       => __( 'Client Secret', 'career-page-by-vivahr' ),
                    'type'        => 'input',
                    'subtype'     => 'password',
                    'placeholder' => __( 'Client Secret', 'career-page-by-vivahr' ),
				    'sanitize'    => '',
				    'required'    => false
                ),  
				'vivahr_redirect_uri'  => array(
                    'title'       => __( 'Callback URL', 'career-page-by-vivahr' ),
                    'type'        => 'input',
                    'subtype'     => 'text',
                    'placeholder' => __( 'Callback URL', 'career-page-by-vivahr' ),
				    'sanitize'    => '',
				    'required'    => false
                )  		 
            );	
			
			return $fields;
		}	
    }
}