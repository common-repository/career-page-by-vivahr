<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<div class="wrap container-fluid vivahr-dashboard">

    <div class="row">
        
	    <div class="col-xs-12 col-sm-12 col-md-8 d-flex align-items-center">
			 
			<h3 class="page-title"><?php esc_html_e( 'Dashboard', 'career-page-by-vivahr' );?></h3>

		</div>	  
	 
   	    <div class="col-xs-12 col-sm-12 col-md-4 d-flex justify-content-end align-items-center">
		<?php 
		
           /*  $allowed_html = [
                'a' => [
                    'class'  => true,
                    'href'   => true,
					'target' => true
                ]
            ]; 
						
            $add_job_link_filtered = wp_kses( $add_job_link, $allowed_html );
			
			echo $add_job_link_filtered; */
		?>
		<a class="v-button-primary" href="post-new.php?post_type=vivahr_jobs" target="_blank">+ <?php esc_html_e('Add New Job', 'career-page-by-vivahr');?></a>
		
		</div>
	   
    </div>

    <div class="row" style="margin: 20px -25px 45px -25px;">
	  
        <div class="col-xs-12 col-md-8 col-lg-8">   
		
		    <div class="row">
	
                <div class="col-xs-12 col-md-12 col-lg-12">
	            
				    <div class="vivahr-panel">
                    
					    <div class="vh-header">
						
                            <h3 class="vhh-title"><?php esc_html_e( 'Overview', 'career-page-by-vivahr' );?></h3>
							
		                    <select class="overview-range">
							
			                    <option <?php echo esc_html(( isset( $range ) && $range == 'week') ? 'selected' : '');?> value="week"><?php esc_html_e( 'This Week', 'career-page-by-vivahr' );?></option>
			                    <option <?php echo esc_html(( isset( $range ) && $range == 'month') ? 'selected' : '');?> value="month"><?php esc_html_e( 'This Month', 'career-page-by-vivahr' );?></option>
			                    <option <?php echo esc_html(( isset( $range ) && $range == 'year') ? 'selected' : '');?> value="year"><?php esc_html_e( 'This Year', 'career-page-by-vivahr' );?></option>
			               
						   </select>
                        
						</div>
                       
					    <div class="vh-body">
                            
							<div class="vo-b-section col-xs-12 col-sm-6">
                                
								<div class="vo-b-s-content-1-1">
                           
						            <p><?php echo esc_html( intval( $overview['jobs']['total'] ) );?></p>
                            
							        <div class="d-flex" style="flex-direction: column;">
									
                                        <span style="font-size:16px;">
										
						                <?php 
						                if ($overview['jobs']['diff'] > 0) 
						                { 
									        $img_url_1_1 = $core->vivahrAdminAssetsPath.'/images/arrow_up_1.png';
							                echo esc_html( intval( $overview['jobs']['diff'] ) ).' <img src="'.esc_url( $img_url_1_1 ).'">';
						                } 
						                else 
						                {
											$img_url_1_1 = $core->vivahrAdminAssetsPath.'/images/arrow_down_1.png';
						                	echo esc_html( intval( $overview['jobs']['diff'] ) ).' <img src="'.esc_url( $img_url_1_1 ).'">';
						                } 
						                ?>
										
						                </span>
										
                                        <span style="font-size:13px;">
										
									        <?php 
										
									        /* translators: %s: Range name */
                                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( strtoupper( $overview['jobs']['range_name'] ) ) );

									        ?>
										
									   </span>
									   
                                    </div>
									
                                </div>
                        
						        <div class="vo-b-s-content-1-2">
								
                                   <img style="max-width:135px;" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/new_candidates.png');?>">
								   
                                </div>
								
                                <div class="vo-b-s-content-1-3">
								
                                   <span><?php esc_html_e( 'Total Active Jobs', 'career-page-by-vivahr' );?></span>
								   
                                </div>
								
                            </div>
							
                            <div class="vo-b-section col-xs-12 col-sm-6">
							
                                <div class="vo-b-s-content-2-1">
								
                                    <p><?php echo esc_html(intval($overview['candidates']['total']));?></p>
									
                                    <div class="d-flex" style="flex-direction: column;">
									
					                    <span style="font-size:16px;">
										
						                    <?php 
						    
						                    if ($overview['candidates']['total'] > 0) 
						                    { 
										        $img_url_2_1 = $core->vivahrAdminAssetsPath.'/images/arrow_up_2.png';
						                	    echo esc_html(intval($overview['candidates']['diff'])).' <img src="'.esc_url($img_url_2_1).'">';
						                    } 
						                    else 
						                    {
												$img_url_2_1 = $core->vivahrAdminAssetsPath.'/images/arrow_down_2.png';
						                    	echo esc_html(intval($overview['candidates']['diff'])).' <img src="'.esc_url($img_url_2_1).'">';
						                    } 
											
						                    ?>
											
						                </span>
										
                                        <span style="font-size:13px;">
										
										    <?php
                                            /* translators: %s: Range name */
                                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( strtoupper( $overview['candidates']['range_name'] ) ) );										
										    ?>
										</span>
										
                                    </div>
									
                                </div>
                  
				                <div class="vo-b-s-content-2-2">
								
                                   <img style="max-width:135px;" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/total_hired.png');?>">
								   
                                </div>
								
                                <div class="vo-b-s-content-2-3">
								
                                   <span><?php esc_html_e( 'Total Candidates', 'career-page-by-vivahr' );?></span>
								   
                                </div>
								
                            </div>

                        </div>
						
                    </div>
					
			    </div>
				
			</div>
			
	        <div class="row" style="margin-top:35px;">
			
                <div class="col-xs-12">
                    
					<div style="overflow-x:auto;">
                        
						<table class="table vivahr-table">
                            
							<thead>
                                
								<tr class="vivahr-table-description">
                         
						            <th><?php esc_html_e( 'Active Jobs', 'career-page-by-vivahr' );?></th>
									
                                </tr>
                                
								<tr class="vivahr-table-header">
								
                                    <th><?php esc_html_e( 'Job Title', 'career-page-by-vivahr' );?></th>
                                    <th><?php esc_html_e( 'Location', 'career-page-by-vivahr' );?></th>
                                    <th><?php esc_html_e( 'Date Posted', 'career-page-by-vivahr' );?></th>
                                    <th><?php esc_html_e( 'Candidates', 'career-page-by-vivahr' );?></th>
                                    <th><?php esc_html_e( 'Status', 'career-page-by-vivahr' );?></th>
                                
								</tr>
                            
							</thead>
                   
				        <tbody>
			                
							<?php
			                if(empty($overview['active_jobs']))
			                {
			                    echo '<tr><td><span style="text-indent:initial;" class="dashicons dashicons-open-folder"></span> '.esc_html('No Job Openings!', 'career-page-by-vivahr').'</td></tr>'; 
			                }
			                else
			                {
			                    $args = array(
				                   'numberposts' => 10,
				                   'post_type' => 'vivahr_jobs'
				                );
				  
                                $posts = get_posts($args);
				   
				                foreach($posts as $job_key => $job_value)
				                {
				           
           						    $post_meta = get_post_meta( intval($job_value->ID), 'vivahr_job_details', TRUE);
							
				                    $candidates_count = count( $jobs::get_candidates( intval($job_value->ID), 'ids' ) );
				   
					                echo '<tr>
                                        <td>'.esc_html($job_value->post_title).'</td>
                                        <td>'.esc_html($jobs->get_location_name($post_meta['vivahr_location'])).'</td>
                                        <td>'.esc_html(date('M d, Y', strtotime($job_value->post_date))).'</td>
                                        <td>'.(($candidates_count == 0) ? '-' : intval($candidates_count)).'</td>
                                        <td>'.(($job_value->post_status == 'publish') ? 'Active' : esc_html($job_value->post_status)).'</td>
                                        </tr>';
				                }
			                }
                            ?>
							
                        </tbody>
                    
					    </table>
               
 			        </div>
			
			    </div>
         
		    </div>
	 
	    </div>
	
	    <div class="col-xs-12 col-md-4 col-lg-4">
            
		    <?php do_action('help_docs');?>
    
	    </div>
   
    </div>

</div>

<script>
	jQuery('.overview-range').on('change', function() {	   
	   window.location.replace("admin.php?page=vivahr_overview&range="+this.value);
    });
</script>