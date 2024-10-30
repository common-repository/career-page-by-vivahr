<?php namespace VIVAHR\Controllers\WP;

/**
 * The Job openings functionality
 * 
 * @since      1.0.0
 *
 * @package    VIVAHR
 * @subpackage vivahr/includes/controllers
 */

defined('ABSPATH') OR exit('No direct script access allowed');

use VIVAHR\Controllers\CoreController;
 
if( !class_exists( 'VIVAHR_WP_Jobs') )
{
    class VIVAHR_WP_Jobs
    {	
		public function init() 
		{
			
			if ( post_type_exists( 'vivahr_jobs' ) ) 
			{
			    return;
		    }
			
		    $has_archive = false; //= get_option( 'vivahr_jobs_disable_archive_page' ) !== 'disable' ? true : false;
		    $front  = true; //= get_option( 'vivahr_jobs_remove_permalink_front_base' ) !== 'remove' ? true : false;
		    $supports    = array( 'title', 'editor', 'excerpt', 'author', 'custom-fields', 'publicize', 'custom-fields' );
			$show_in_menu = 'vivahr';

			$args = array(
		       'has_archive'     => $has_archive,
		       'labels'          => $this->jobs_cpt_labels(),
		       'hierarchical'    => false,
		       'map_meta_cap'    => true,
		       'taxonomies'      => array(),
		       'public'          => true,
		       'show_ui'         => true,
		       'show_in_rest'    => true,
		       'show_in_menu'    => $show_in_menu,
		       'rewrite'         => array(
			       'slug'       => sanitize_text_field(get_option( 'vivahr_url_slug', 'jobs' )),
			       'with_front' => $front,
			   ),
			   'capability_type' => 'post',
			   'menu_icon'       => '',
			   'supports'        => $supports
		    );
     
            register_post_type( 'vivahr_jobs', $args );
			
			if ( post_type_exists( 'vivahr_candidates' ) ) 
			{
			    return;
		    }
			
			$args = array(
                'labels'          => $this->all_candidates_cpt_labels(),
			    'public'          => false,
				'show_ui'         => true,
				'map_meta_cap'    => true,
				'show_in_menu'    => 'vivahr',
			    'capability_type' => 'page',
				'capabilities' => array(
                     'create_posts' => false,
                    
                 ),
                // 'map_meta_cap' => true,
				'supports'        => false,
				'rewrite'         => false,
				'has_archive'     => false,
				
            );
     
            register_post_type( 'vivahr_candidates', $args );
			
			add_filter( 'manage_vivahr_jobs_posts_columns',       array( $this, 'vivahr_jobs_custom_column_member' ) );
			add_filter( 'manage_vivahr_jobs_posts_custom_column', array( $this, 'vivahr_jobs_custom_column_member_data' ), 10, 2 );
			
			add_filter( 'manage_vivahr_candidates_posts_columns',       array( $this, 'vivahr_candidates_custom_column_member' ) );
			add_filter( 'manage_vivahr_candidates_posts_custom_column', array( $this, 'vivahr_candidates_custom_column_member_data' ), 10, 2 );
       
	       add_filter('screen_options_show_screen', '__return_false');

	   }
	   
		private function jobs_cpt_labels()
		{
			
            $labels = array(
                'name'                  => _x( 'Jobs', 'Post type general name', 'career-page-by-vivahr' ),
                'singular_name'         => _x( 'Job', 'Post type singular name', 'career-page-by-vivahr' ),
                'menu_name'             => _x( 'Jobs', 'Admin Menu text', 'career-page-by-vivahr' ),
                'name_admin_bar'        => _x( 'Job', 'Add New on Toolbar', 'career-page-by-vivahr' ),
                'add_new'               => esc_html( 'Add New', 'career-page-by-vivahr' ),
                'add_new_item'          => __( 'Add New job', 'career-page-by-vivahr' ),
                'new_item'              => __( 'New job', 'career-page-by-vivahr' ),
                'edit_item'             => __( 'Edit job', 'career-page-by-vivahr' ),
                'view_item'             => __( 'View job', 'career-page-by-vivahr' ),
                'all_items'             => __( 'Job Openings', 'career-page-by-vivahr' ),
                'search_items'          => __( 'Search jobs', 'career-page-by-vivahr' ),
                'parent_item_colon'     => __( 'Parent jobs:', 'career-page-by-vivahr' ),
                'not_found'             => __( 'No jobs found.', 'career-page-by-vivahr' ),
                'not_found_in_trash'    => __( 'No jobs found in Trash.', 'career-page-by-vivahr' ),
                'featured_image'        => _x( 'Job Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'career-page-by-vivahr' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'career-page-by-vivahr' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'career-page-by-vivahr' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'career-page-by-vivahr' ),
                'archives'              => _x( 'Job archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'career-page-by-vivahr' ),
                'insert_into_item'      => _x( 'Insert into job', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'career-page-by-vivahr' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this job', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'career-page-by-vivahr' ),
                'filter_items_list'     => _x( 'Filter jobs list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'career-page-by-vivahr' ),
                'items_list_navigation' => _x( 'Jobs list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'career-page-by-vivahr' ),
                'items_list'            => _x( 'Jobs list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'career-page-by-vivahr' ),
            );
			
			return $labels;
		}		
		
		private function all_candidates_cpt_labels()
		{
            $labels = array(
			    'name'               => __( 'Applications',                   'career-page-by-vivahr' ),
			    'singular_name'      => __( 'Application',                    'career-page-by-vivahr' ),
			    'menu_name'          => __( 'Applications',                   'career-page-by-vivahr' ),
			    'edit_item'          => __( 'Applications',                   'career-page-by-vivahr' ),
			    'search_items'       => __( 'Search',                         'career-page-by-vivahr' ),
			    'not_found'          => __( 'No Applications found',          'career-page-by-vivahr' ),
			    'not_found_in_trash' => __( 'No Applications found in Trash', 'career-page-by-vivahr' ),
			);
			
			return $labels;
		}
		
        public function vivahr_jobs_custom_column_member( $columns ) 
		{
		    $columns = array(
		    	'cb'              => '<input type="checkbox" />',
		    	'title'           => esc_attr__( 'Job Title',   'career-page-by-vivahr' ),
		    	'job_posting'     => esc_attr__( 'Job Posting', 'career-page-by-vivahr' ),
		    	'job_status'      => esc_attr__( 'Status',      'career-page-by-vivahr' ),
		    	'job_date_posted' => esc_attr__( 'Date Posted', 'career-page-by-vivahr' ),
		    	'job_candidates'  => esc_attr__( 'Candidates',  'career-page-by-vivahr' ),
		    	'job_location'    => esc_attr__( 'Location',    'career-page-by-vivahr' ),
		    );
			
		    return $columns;
	    }
		
		public function vivahr_candidates_custom_column_member( $columns ) 
		{
		    $columns = array(
		    	'cb'                    => '<input type="checkbox" />',
		    	'title'                 => esc_attr__( 'Candidate Name', 'career-page-by-vivahr' ),
		    	'post_date'             => esc_attr__( 'Date Applied',   'career-page-by-vivahr' ),
		    	'job_title'             => esc_attr__( 'Job',            'career-page-by-vivahr' ),
		    	'candidate_phone'       => esc_attr__( 'Phone',          'career-page-by-vivahr' ),
		    	'candidate_email'       => esc_attr__( 'Email',          'career-page-by-vivahr' ),
		    	'candidate_resume'      => esc_attr__( 'Resume',         'career-page-by-vivahr' ),
		    );
		    return $columns;
	    }
		
		public function vivahr_candidates_custom_column_member_data( $column, $post_id )
		{
			if ( !is_numeric( $post_id ) ) 
			{
               wp_die( 'Incorrect Post ID!' );
            }
            else 
			{
                $post_id = sanitize_text_field( $post_id );
            }
			
			$candidate_meta = get_post_meta( $post_id, 'vivarh_applicant_additional', true );
			
			$candidate_post = get_post( $post_id );

			$job_post = get_post($candidate_post->post_parent);
            
		    switch ( $column ) 
			{
				case 'post_date':
				    
					echo sprintf(
						    '<p>%1$s</p>',
			                date( 'M d, Y', strtotime( esc_html( $candidate_post->post_date ) ) )
		                );
				    //echo apply_filters( 'the_content', wp_kses_post(  ) ) );
				
				break;
				
				case 'job_title':
				    
					echo sprintf(
						    '<p>%1$s</p>',
			                esc_html( $job_post->post_title )
		                );
				
				break;
				
				case 'candidate_phone':
				
				    echo (isset($candidate_meta['post_phone'])) ? esc_html($candidate_meta['post_phone']) : '';
				
				break;
				
				case 'candidate_email':
				
				    echo (isset($candidate_meta['post_email'])) ? esc_html($candidate_meta['post_email']) : '';
				
				break;
				
				case 'candidate_resume':
				
				    $post_meta = get_post_meta( $post_id, 'vivarh_applicant_additional', true );
				    if( isset( $post_meta['post_resume'] ) && !empty( $post_meta['post_resume'] ) )
					{
						/* $resume_link = '<a target="_blank" href="'.esc_url($post_meta['post_resume']).'"><img style="width:20px;height:20px;" src="'.VIVAHR_PLUGIN_URL.'/assets/admin/images/download.png"/></a>';
                        $allowed_html = [
                            'a' => [
                                'target' => true,
                                'href'  => true,
                            ],
                            'img' => [
							    'style' => true,
								'src' => true
							],
                        ]; 
                        $clear_resume_link = wp_kses( $resume_link, $allowed_html );
                        echo $clear_resume_link; */
						
						echo sprintf(
						    '<a target="_blank" href="%1$s"><img style="width:20px;height:20px;" src="%2$s/assets/admin/images/download.png"/></a>',
			                esc_url( $post_meta['post_resume'] ),
			                esc_url( VIVAHR_PLUGIN_URL )
		                );
					}
			
				break;
		    }
		}
		
		public function vivahr_jobs_custom_column_member_data( $column, $post_id )
		{
			if (!is_numeric($post_id)) 
			{
               wp_die('Incorrect Post ID!');
            }
            else 
			{
                $post_id = sanitize_text_field( $post_id );
            }
			
		    $candidates_count = count( self::get_candidates( $post_id, 'ids' ) );
		    $default_display   = '<span aria-hidden="true">-</span>';
            $job_post = get_post( $post_id );
			$post_meta = get_post_meta( $post_id, 'vivahr_job_details', TRUE);
			
			$permalink   = get_permalink( $post_id );
		    $preview_url = function_exists( 'get_preview_post_link' ) ? get_preview_post_link( $job_post ) : add_query_arg( 'preview', 'true', $permalink );

		
		    // View job link.
		    $view_post_link_html = sprintf(
			    ' <a href="%1$s">%2$s</a>',
			    esc_url( $permalink ),
			    esc_html( 'View', 'career-page-by-vivahr' )
		    );
			
		    switch ( $column ) 
			{
			    case 'job_posting':
					
                    $allowed_html = [
                        'a' => [
                            'href'  => true,
                        ],  
                    ]; 
                    
					echo wp_kses( $view_post_link_html, $allowed_html );
                        
				break;
				
				case 'job_status':
					
				   echo esc_html( ucfirst( $job_post->post_status ) );
				   
				break;

			    case 'job_candidates':
					
					$output = $default_display;
				    if ( is_numeric($candidates_count) && $candidates_count > 0 ) 
					{
					    $output = sprintf( '<a href="%1$s">%2$s</a>', esc_url( admin_url( 'edit.php?post_type=vivahr_candidates&vivahr_filter_posts=' . $post_id ) ), $candidates_count );
				    }
					
					$allowed_html = [
                        'a' => [
                            'href'  => true,
                        ],
                        'span' => [
						    'aria-hidden' => true
						]						
                    ]; 
                    
					echo wp_kses( $output, $allowed_html );
					
				break;

			    case 'job_date_posted':
			    	
			        echo wp_kses_post( date('M d, Y', strtotime( $job_post->post_date )) );
			    break;
				
				case 'job_location':
			    	
					if(isset($post_meta['vivahr_location']))
					{
						echo esc_html($this->get_location_name($post_meta['vivahr_location']));
					}
			       
			    break;
		    }
	    }
		
		public function get_location_name($location_id)
		{
			global $wpdb;
			
			if(isset($location_id) && !empty($location_id) && !is_numeric($location_id))
			{
				wp_die('Incorrect Location ID');
			}
			
			$location_id = (int)$location_id;
			
			$location = $wpdb->get_row(
	            $wpdb->prepare("SELECT name FROM ".$wpdb->prefix."vivahr_location WHERE id = %d", $location_id)
            );
			
			return isset($location->name) ? $location->name : '-';
		}
		
		public function unregister_vivahr_jobs_post_type() 
		{
		    global $wp_post_types;
		    if ( isset( $wp_post_types['vivahr_jobs'] ) ) {
		    	unset( $wp_post_types['vivahr_jobs'] );
		    	return true;
		    }
		    return false;
	    }
		
		public static function get_candidates( $job_id, $fields = 'all' ) 
		{
			if(!is_numeric($job_id))
			{
				wp_die('An error occurred');
			}
			
		    $candidates = get_children(
			    array(
				    'post_parent' => $job_id,
				    'post_type'   => 'vivahr_candidates',
				    'numberposts' => -1,
				    'orderby'     => 'date',
				    'order'       => 'DESC',
				    'fields'      => $fields,
			    )
		    );
			
		    return $candidates;
	    }
        
    	/**
	     * Adds Job Details Meta Box inside the Job Openings add/edit form
	     * 
	     * @since    1.0.0
	     */		
		public function add_meta_box( $post_type ) 
		{
		    $post_types = array( 'vivahr_jobs' );

		    if ( in_array( $post_type, $post_types ) ) 
			{
			    add_meta_box(
				    'vivahr_jobs-meta',
				    __( 'Job Details', 'career-page-by-vivahr' ),
				    array( $this, 'render_meta_box_content' ),
				    $post_type,
				    'advanced',
				    'high'
			    );
		    }
	    }
		
		/**
	     * Render Meta Box content.
	     *
	     * @param WP_Post $post The post object.
	     */
	    public function render_meta_box_content( $post ) 
		{ 
		    global $wpdb;
			
			$allowed_wp_tables = array(
			    $wpdb->prefix.'vivahr_position_type',
			    $wpdb->prefix.'vivahr_skill_level',
			    $wpdb->prefix.'vivahr_salary_type',
			    $wpdb->prefix.'vivahr_department',
			    $wpdb->prefix.'vivahr_location',
			);
			
			if(!is_numeric($post->ID))
			{
				wp_die('An error occurred');
			}
			
		    wp_nonce_field( 'vivahr_job_details_box', 'vivahr_job_details_box_nonce' );
			 
			$jobDetails = get_option( 'vivahr_job_details', 'objects' );
            $post_meta = get_post_meta( $post->ID, 'vivahr_job_details', TRUE);
			
			echo '<div class="vivahr-job-details-section" id="job-details" style="display: flex;flex-direction: column;">';
			
            foreach($jobDetails as $jobDetail => $jobDetailValue)
            {

	            if($jobDetail != 'vivahr_salary_range')
		        {
			        
		            $table = $wpdb->prefix . $jobDetail;
					
					if(in_array($table, $allowed_wp_tables))
					{   $status_id = 1;
						
						$result = $wpdb->get_results( $wpdb->prepare("SELECT id, name FROM %i WHERE deleted_at IS NULL AND status = %d", $table, $status_id) );
          
		                $option = array();
		  
		                $i = 0;
	        
			            foreach($result as $row)
		                {  
		                    $option[$i]['id'] = intval($row->id);
		                    $option[$i]['name'] = esc_html(sanitize_text_field($row->name));
			  
			                $i++;
		                }
					}
                    
		        }
		  
		        $options = $option;
		  
	            if($jobDetail == 'vivahr_salary_range')
	            {
		            if(isset($post_meta['vivahr_salary_range_from']) && !empty($post_meta['vivahr_salary_range_from']))
		            {
			            $vivahr_salary_range_from = sanitize_text_field($post_meta['vivahr_salary_range_from']);
		            }
		            else
		            {
			            $vivahr_salary_range_from = '';
		            }
		  
		            if(isset($post_meta['vivahr_salary_range_to']) && !empty($post_meta['vivahr_salary_range_to']))
		            {
			            $vivahr_salary_range_to = sanitize_text_field($post_meta['vivahr_salary_range_to']);
		            }
		            else
		            {
			            $vivahr_salary_range_to = '';
		            }
		  
		            ?>
		            <div class="job-details-field">
					
		                <label>
						    <?php 
                                /* translators: %s: Field Label */
                                printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $jobDetailValue['field_title'] ) );
							?>
						</label>
			            
						<div style="gap:25px;display: flex;align-items: center;">
						
		                    <input value="<?php echo esc_attr($vivahr_salary_range_from);?>" placeholder="0.00" type="number" name="vivahr_job_details[<?php echo esc_attr( $jobDetail ); ?>_from]" id="job_details_<?php echo esc_attr( $jobDetail ); ?>_from"/>
		                    to
			                <input value="<?php echo esc_attr($vivahr_salary_range_to);?>" placeholder="0.00" type="number" name="vivahr_job_details[<?php echo esc_attr( $jobDetail ); ?>_to]" id="job_details_<?php echo esc_attr( $jobDetail ); ?>_to"/>
		                
						</div>
		            
					</div>
		            <?php
	            }
	            else
	            {
		            $required = ( ( $jobDetailValue['required'] == 1 ) ? 'required=required' : '' );
	                ?>
		
		            <div class="job-details-field">
		  
           		        <label for="job_details_<?php echo esc_attr( $jobDetail ); ?>">
						    <?php
							    /* translators: %s: Field Label */
                                printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $jobDetailValue['field_title'] ) );
							?> 
							
							<?php echo (($jobDetailValue['required'] == 1) ? "*" : '');?>
						</label>
						
		                <select class="vivahr-job-details-field" name="vivahr_job_details[<?php echo esc_attr( $jobDetail ); ?>]" id="job_details_<?php echo esc_attr( $jobDetail ); ?>" <?php echo esc_attr( $required ); ?>>
		                    
							<option value="">
							<?php
							    /* translators: %s: Option Label */
                                printf( esc_html__( 'Select %s', 'career-page-by-vivahr' ), esc_html( $jobDetailValue['field_title'] ) );
							?>
							</option>
			                
							<?php
		   	                foreach($options as $option)
		   		            {
					            if(isset($post_meta[$jobDetail]))
					            {
						            $selected = (($option['id'] == $post_meta[$jobDetail]) ? 'selected' : '');
					            }
					            else
					            {
						            $selected = '';
					            }
					            
							    echo '<option '.esc_attr($selected).' value="'.esc_attr($option['id']).'">'.esc_html($option['name']).'</option>';
		   		            }
		   	                ?>
							
		                </select>
		            
					</div>
		            
					<?php
		        }
	        
			} 
 
	    }
		
        public function validateMetaBoxesFields($postData, $postArray) 
		{ 

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    	return $postData;
		    }
			
			if ( ! isset( $postArray['vivahr_job_details_box_nonce'] ) ) {
		    	return $postData;
		    }
         
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $postArray['vivahr_job_details_box_nonce'] ) ), 'vivahr_job_details_box' ) ) {
		        return $postData;
            }

		    if ( ! current_user_can( 'edit_post', $postArray['ID'] ) ) {
		    	return;
		    }
			
			if ( array_key_exists( 'post_type', $postData ) && $postData['post_type'] === 'vivahr_jobs' ) {
				
                if ( array_key_exists('post_status', $postData ) && $postData['post_status'] === 'publish' ) {
                    
					$valid = true;
                
				    // Check meta box fields (in $postArray) if something not valid, set $valid to false;
                    if ( !$valid) {
						
                        $postData['post_status'] = 'draft';
							
                    }
					
					if ( isset( $_POST['vivahr_job_details'] ) ) {
						
				        foreach( $_POST['vivahr_job_details'] as $key => $val ) {
						    $job_details[$key] = sanitize_text_field( $val );
					    }
					
				        $this->my_update_post_meta( $postArray['ID'], 'vivahr_job_details', $job_details );	
			        }	
                }
            }
          
			return $postData; 
        }

		public function my_update_post_meta($post_id, $meta_key, $new_meta_value) 
		{
            if(!is_numeric($post_id))
			{
				wp_die('An error occurred');
			}
			
			$meta_value = get_post_meta($post_id, $meta_key, true);
            if ($new_meta_value && '”' === $meta_value)
			{
				add_post_meta($post_id, $meta_key, $new_meta_value, true); // unique
			}
            else if ($new_meta_value && $new_meta_value !== $meta_value)
			{
				 update_post_meta($post_id, $meta_key, $new_meta_value, $meta_value);
			}   
            // same prev_value
            else if ('”' === $new_meta_value && $meta_value)
			{
				delete_post_meta($post_id, $meta_key, $meta_value); 
			}
                
		}
		
		public function nav_header() 
		{
			$core = new CoreController();
		    $nav_page = self::get_admin_nav_page();
		
	        if ( ! empty( $nav_page ) ) 
			{
			    $nav_items = array(
				    array(
				    	'visible' => current_user_can( 'manage__jobs' ),
				    	'id'      => '',
				    	'label'   => __( 'Overview', 'career-page-by-vivahr' ),
				    	'url'     => admin_url( 'admin.php?page=vivahr_overview' ),
				    ), 
					array(
				    	'visible' => current_user_can( 'manage__jobs' ),
				    	'id'      => '',
				    	'label'   => __( 'Job Openings', 'career-page-by-vivahr' ),
				    	'url'     => admin_url( 'edit.php?post_type=vivahr_jobs' ),
				    ), 
					array(
				    	'visible' => current_user_can( 'manage__jobs' ),
				    	'id'      => '',
				    	'label'   => __( 'Applications', 'career-page-by-vivahr' ),
				    	'url'     => admin_url( 'edit.php?post_type=vivahr_candidates' ),
				    ), 
					array(
				    	'visible' => current_user_can( 'manage__jobs' ),
				    	'id'      => '',
				    	'label'   => __( 'Settings', 'career-page-by-vivahr' ),
				    	'url'     => admin_url( 'admin.php?page=vivahr_settings' ),
				    ),
					array(
				    	'visible' => current_user_can( 'manage__jobs' ),
				    	'id'      => '',
				    	'label'   => __( 'View Career Page', 'career-page-by-vivahr' ),
				    	'url'     => admin_url( 'admin.php?page=vivahr_settings' ),
				    )
			    );
			?>
			
			<div class="container-fluid vivahr-navbar" style="height: 69px;">
    
	<div class="row">
	    
		<div class="col-xs-12 col-sm-12 col-md-12">
		    
			<div class="d-flex align-items-center justify-content-between" style="padding: 5px 0;"> 
			
			    <a class="vivahr-navbar-brand d-flex align-items-center" href="admin.php?page=vivahr_overview">  
	
	                <img src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/VIVAHR_Blue_Icon.png');?>" border="0"/> 
		            <h4><?php esc_html_e( 'Career Page by VIVAHR', 'career-page-by-vivahr' );?></h4>
	
	            </a>

			    <ul class="hidden-sm vivahr-navbar-nav d-flex align-items-center">
		
	            <?php
				 /*   if( isset( $_GET['page'] ) && !empty( $_GET['page'] ) )
				   {
				      $page = sanitize_text_field($_GET['page']); 
				   }
				   elseif( isset( $_GET['post_type'] ) && !empty( $_GET['post_type'] ) )
				   {
					   $page = sanitize_text_field($_GET['post_type']); 
				   }
				
				   
                   $admin_tabs = $core->admin_menu_tabs( $page );
		           foreach( $admin_tabs as $admin_tab )
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
	                 
                   } */
		        ?>
                    <li class="nav-item "><a class="nav-link" href="<?php echo esc_url( get_admin_url().'admin.php?page=vivahr_overview' );?>"><?php esc_html_e('Overview', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item <?php if( isset($_GET['post_type']) && sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) == 'vivahr_jobs'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'edit.php?post_type=vivahr_jobs' );?>"><?php esc_html_e('Job Openings', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item <?php if( isset($_GET['post_type']) && sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) == 'vivahr_candidates'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'edit.php?post_type=vivahr_candidates' );?>"><?php esc_html_e('Applications', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item "><a class="nav-link" href="<?php echo esc_url( get_admin_url().'admin.php?page=vivahr_settings' );?>"><?php esc_html_e('Settings', 'career-page-by-vivahr');?></a></li>
					
					<?php 
					if( !empty( get_option('vivahr_jobs_listing_page') ) )
		            {
				        $post_id = sanitize_text_field( get_option('vivahr_jobs_listing_page') );
				
				        if ( !is_numeric($post_id) )
				        {
					
				            
				        }
				        else
				        {
					        $post = get_post( $post_id );
			    
                            if( !empty($post) )
				            {
				                ?>
								<li class="nav-item"><a class="nav-link vivahr-external-link-secondary" target="_blank" href="<?php echo esc_url(get_admin_url().sanitize_text_field($post->post_name));?>"><?php esc_html_e('View Career Page', 'career-page-by-vivahr');?></a></li>
								<?php
				            }		
				        }
		            }
					?>
				
				</ul>

		    </div>  
	
		</div>
		
	</div>
    
</div>

			<?php
			}
	}
	
		public static function get_admin_nav_page() 
		{
		    $page = false;
		    $current_screen  = get_current_screen();
			
		    if ( ! empty( $current_screen ) ) 
			{
			    $post_type = $current_screen->post_type;
				
			    if ( ( $post_type === 'vivahr_jobs' ) || ( $post_type === 'vivahr_candidates' ) )
				{
				    $is_page = $current_screen->id;
				   
				    if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) 
					{
				    	$is_page = false;
				    }
				 
				    if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) 
					{
				    	$is_page = false;
				    }
			    }
		    }
			
		    return ( isset( $is_page ) ? $is_page : '');
	    }
	
   }

}
