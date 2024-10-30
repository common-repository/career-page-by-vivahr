<?php namespace VIVAHR\Controllers\WP;

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;

if( !class_exists( 'VIVAHR_WP_JobsList' ) )
{
	class VIVAHR_WP_JobsList extends CoreController
	{ 
		public function init()
        {
			$output = '';
			
			$output .= $this->jobs_list_start();
			
			$output .= $this->jobs_list_company_image();
			
			$output .= $this->jobs_list_company_overview();
			
			$output .= $this->jobs_list_search_jobs();
			
			$output .= $this->jobs_list_filtering_dropdowns();
			
			$output .= $this->jobs_list();
				
			$output .= $this->jobs_list_end();
			
			return $output;
			
		}
		
		public function jobs_list_start()
		{
			return '<div id="vivahr-jobs">';
		}		
		
		public function jobs_list_end()
		{  //https://jobs.vivahr.com/
			return '</div><div style="text-align:center;margin-top: 75px;" class="footer center" bis_skin_checked="1">
		            <a href="https://vivahr.com"><img style="max-width: 100%;height: auto;" src="'.VIVAHR_PLUGIN_URL.'assets/public/images/powered_logo.svg" alt="VivaHR.com" width="250" height="88"> </a>
	            </div>';
		}
		
		public function jobs_list_company_image()
		{
			$companyLogo = get_option('vivahr_company_logo');
		    $response = '';
			
			if(!empty($companyLogo))
			{
				$response = '<div class="vj-company-logo">
				    <img style="max-width:173px; max-height:125px;" src="'.esc_url( $companyLogo['url'] ).'">
				</div>';
			}
			
			return $response;
		}
		
		public function jobs_list_company_overview()
		{
			return '<div class="vj-company-overview">
			    <h3>'.esc_html('Search Job Openings', 'career-page-by-vivahr').'</h3>
			</div>
			';
		}
		
		public function jobs_list_search_jobs()
		{
			$search = (isset($_GET['j']) ? sanitize_text_field($_GET['j']) : '');
			
			
			return '<div class="vj-search-box">
			    <input value="'.$search.'" type="text" id="vj-search-jobs" placeholder="'.esc_html__('Search Job Openings', 'career-page-by-vivahr').'" />
			</div>';
		}
		
		public function jobs_list_filtering_dropdowns()
		{
			global $wpdb;
			
			$location_id = ((isset($_GET['l']) && is_numeric($_GET['l'])) ? intval($_GET['l']) : '');
			$department_id = ((isset($_GET['d']) && is_numeric($_GET['d'])) ? intval($_GET['d']) : '');
			$position_id = ((isset($_GET['wt']) && is_numeric($_GET['wt'])) ? intval($_GET['wt']) : '');
			
		 	$locations_table = $wpdb->prefix . 'vivahr_location';
			$locations = $wpdb->get_results(
	            $wpdb->prepare("SELECT id, name, address, city, country, state, zip_code FROM %i WHERE deleted_at IS NULL", $locations_table)
            );
			
			$departments_table = $wpdb->prefix . 'vivahr_department';
			$departments  = $wpdb->get_results(
	            $wpdb->prepare("SELECT id, name FROM %i WHERE deleted_at IS NULL", $departments_table)
            );
			
			$positions_table = $wpdb->prefix . 'vivahr_position_type';
			$positions  = $wpdb->get_results(
	            $wpdb->prepare("SELECT id, name FROM %i WHERE deleted_at IS NULL", $positions_table)
            );
			
			$locations_array = array();
			foreach($locations as $location)
			{
				$locations_array[] = '<option '.(($location_id == $location->id) ? 'SELECTED' : '').' value="'.intval($location->id).'">'.esc_html($location->name).'</option>';
			}
			
			$departments_array = array();
			foreach($departments as $department)
			{
				$departments_array[] = '<option '.(($department_id == $department->id) ? 'SELECTED' : '').' value="'.intval($department->id).'">'.esc_html($department->name).'</option>';
			}
			
			$positions_array = array();
			foreach($positions as $position)
			{
				$positions_array[] = '<option '.(($position_id == $position->id) ? 'SELECTED' : '').' value="'.intval($position->id).'">'.esc_html($position->name).'</option>';
			}
			
			return '<div class="vj-filtering">
			    
				<select id="location">
				    <option value="">'.esc_html__('Select Location', 'career-page-by-vivahr').'</option>
                    '.implode( '', $locations_array ).'
				</select>				
				
				<select id="department">
				    <option value="">'.esc_html__('Select Department', 'career-page-by-vivahr').'</option>
					'.implode( '', $departments_array ).'   
				</select>				
				
				<select id="position">
				    <option value="">'.esc_html__('Work Type', 'career-page-by-vivahr').'</option>
					'.implode( '', $positions_array).'
				</select>
				
				<button style="height:37px;" class="vj-apply-btn" id="vivhar-filter-jobs">'.esc_html__('Search', 'career-page-by-vivahr').'</button>
				<input type="hidden" id="filter-query"/>
			</div>';
		}
		
		function extend_wp_query_where( $where, $wp_query ) 
		{
            if ( $extend_where = $wp_query->get( 'extend_where' ) ) 
			{
                $where .= " AND " . $extend_where;
            }
            
			return $where;
        }
		
		public function jobs_list()
		{
			global $wpdb;
			
			$locations_table = $wpdb->prefix . 'vivahr_location';
			$departments_table = $wpdb->prefix . 'vivahr_department';
			$positions_table = $wpdb->prefix . 'vivahr_position_type';
			
        
	        $search_by_job_title = ((isset($_GET['j'])) ? sanitize_text_field($_GET['j']) : '');
	        $search_by_location = ((isset($_GET['l'])) ? intval($_GET['l']) : '');
	        $search_by_department = ((isset($_GET['d'])) ? intval($_GET['d']) : '');
	        $search_by_position = ((isset($_GET['wt'])) ? intval($_GET['wt']) : '');
			
			// GET POST ID's by Location
			$postmetas = $wpdb->get_results("SELECT * 
			    FROM wp_postmeta 
			    WHERE 
				meta_key = 'vivahr_job_details'"
			);
			
			$meta_value = array();
			$search_by_post_id = array();
			foreach($postmetas as $meta)
			{
				$vivahr_job_details = unserialize($meta->meta_value);

                $position = $vivahr_job_details['vivahr_position_type'];
                $location = $vivahr_job_details['vivahr_location'];
                $department = $vivahr_job_details['vivahr_department'];
                
				if($search_by_location == $location)
				{
					$search_by_post_id[] .= (is_numeric($meta->post_id) ? $meta->post_id : '');
				}
				
				if($search_by_department == $department)
				{
					$search_by_post_id[] .= (is_numeric($meta->post_id) ? $meta->post_id : '');
				}				
				
				if($search_by_position == $position)
				{
					$search_by_post_id[] .= (is_numeric($meta->post_id) ? $meta->post_id : '');
				}
			}
			
	        $query = "SELECT * 
			    FROM wp_posts 
			    WHERE 
				post_type = 'vivahr_jobs' 
				AND 
				post_status = 'publish'";
	  

            if($search_by_job_title)
			{
				$cond = $wpdb->prepare(" AND post_title LIKE %s;", '%' . $wpdb->esc_like($search_by_job_title) . '%');
                $query .= $cond;
			}
			
			if(!empty($search_by_post_id))
			{
				$cond = $wpdb->prepare(" AND ID IN (%s)", implode(',', array_unique($search_by_post_id))); 
                $query .= $cond;
			}
			elseif( empty( $search_by_job_title ) && empty( $search_by_location ) && empty( $search_by_department ) && empty( $search_by_position ) )
			{
			
			}
			else
			{
				
				$null = 'null';
				$cond = $wpdb->prepare(" AND ID = %s", $null); 
                $query .= $cond;
			}
	    
	        $vivahr_jobs = $wpdb->get_results($query);
			
			//update_option('vivahr_log', $query);
			
			if( ! empty( $vivahr_jobs ) )
			{
	            
				$jobs_array = array();
	            foreach ( $vivahr_jobs as $job )
				{
	                $meta = get_post_meta( $job->ID, 'vivahr_job_details' );
					
					if(isset($meta) && !empty($meta))
					{
						if(!empty($meta[0]['vivahr_location']))
					    {
						    $location_id = (int)$meta[0]['vivahr_location'];
							$location_data = $wpdb->get_row( $wpdb->prepare( "SELECT id, name, address, city, country, state, zip_code FROM %i WHERE id = %d", $locations_table, $location_id ) );
					    }
						else
						{
							$location_data = '';
						}
					
					    if(!empty($meta[0]['vivahr_department']))
					    {
						    $department_id = (int)$meta[0]['vivahr_department'];
							$department_data = $wpdb->get_row( $wpdb->prepare( "SELECT id, name FROM %i WHERE id = %d", $departments_table, $department_id ) );
					    }
						else
						{
							$department_data = '';
						}
						
						if(isset($meta[0]['vivahr_position_type']))
					    {
						    $position_type = (int)$meta[0]['vivahr_position_type'];
					        $position_type = $wpdb->get_row( $wpdb->prepare( "SELECT id, name FROM %i WHERE id = %d", $positions_table, $position_type ) );
					    }
					    else
					    {
					    	$position_type = '';
					    }
					}
					
					
					
					
					$city = (isset($location_data->city) && !empty($location_data->city)) ? $location_data->city : '';
					$state = (isset($location_data->state) && !empty($location_data->state)) ? $location_data->state : '';
					$zip = (isset($location_data->zip_code) && !empty($location_data->zip_code)) ? $location_data->zip_code : '';
					$country = (isset($location_data->country) && !empty($location_data->country)) ? $location_data->country : '';
					
					$department_name = (isset($department_data->name) && !empty($department_data->name)) ? $department_data->name : '';
			
                    $jobs_array[] = '<tr>
                            <th scope="row"><p class="vj-job-title"><a href=' . esc_url(get_permalink( $job->ID )) . '>' . esc_html($job->post_title) . '</a></p> <p class="vj-jobs-posted-data">Posted ' . date('M d, Y', strtotime($job->post_date)) . '</p></th>
                            <td>'.esc_html($city).', '.esc_html($state).' '.esc_html($zip).'</br> '.esc_html($country).'</td>
                            <td>'.esc_html($department_name) .'</td>
                            <td>'.esc_html($position_type->name).'</td>
                            <td width="150px;"><a class="vj-apply-btn" href=' . esc_url(get_permalink( $job->ID )) . '>Apply</a></td>
                        </tr>';
			 }
				
	            
            }
			
			if(!isset($jobs_array) || empty($jobs_array))
			{

			}
			else
			{
				return '
			        <div class="vj-jobs-table">
			            <table class="table">
                            <tbody>
                                '.implode('', $jobs_array).'
                            </tbody>
                        </table>
			        </div>';				
			}
			
		}
	}
}