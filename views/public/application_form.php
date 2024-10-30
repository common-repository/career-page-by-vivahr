<?php defined('ABSPATH') OR exit('No direct script access allowed');

if($_POST){
	
	if ( ! isset( $_POST['cpt_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['cpt_nonce_field'] ) ) , 'cpt_nonce_action' ) )
	{
		wp_die('Nonce error');
	}
	
	do_action( 'save_candidate_application' );
}
 
if(isset($_SESSION['application_success']))
{
	?>
	<style>
	.application-submited-box{
		margin: 0 auto;
        text-align: center;
        background: aliceblue;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
	}
	</style>
	<div class="application-submited-box">
	    <?php echo esc_html(sanitize_text_field($_SESSION['application_success']));?>
	</div>
	<?php
    
	
	session_unset();
}
else
{
	?>
				<div id="vivahr-job-overview">
			
			    <div class="vjo-header">
				    <h1 class="vivhar-post-title">
					<?php 
						/* translators: %s: Post Title */
                        printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $post->post_title ) );
					?>
					</h1>
					
					<div class="vivahr-job-details" style="text-align: center;">
					    <?php
						if(isset($location_data->city) && !empty($location_data->city))
						{							
						    /* translators: %s: City */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $location_data->city ) );
						}
						?>
						
						<?php
						if(isset($location_data->state) && !empty($location_data->state))
						{	
							/* translators: %s: State */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $location_data->state ) );
						}
						?>	
						
						<?php
						if(isset($location_data->zip_code) && !empty($location_data->zip_code))
						{
							/* translators: %s: ZIP CODE */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $location_data->zip_code ) );							
						}
						?>						
						
						<?php
						if(isset($location_data->country) && !empty($location_data->country))
						{
							/* translators: %s: Country */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $location_data->country ) );
						}
						?>

						<?php
						if(isset($department_data->name) && !empty($department_data->name))
						{
							/* translators: %s: Department name */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( '&bull; '.$department_data->name ) );
						}
						?>
						
						<?php
						if(isset($position_name) && !empty($position_name))
						{
							/* translators: %s: Position name */
                            printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( '&bull; '.$position_name->name ) );								
						}
						?>	
					
					</div>
					
					<div class="vjo-job-menu">
					    <ul>
						    <li class="job-menu active" id="job-overview"><?php esc_html_e('Overview', 'career-page-by-vivahr');?></li>
							<li class="job-menu " id="job-application"><?php esc_html_e('Application', 'career-page-by-vivahr');?></li>
						</ul>
					</div>
				</div>			    
				
				<div class="vjo-body">
				    
					<div class="vb-box job-overview-view">
					    <?php echo wp_kses_post(get_post_field('post_content', $job_id));?>
					
					    <div class="vj-apply-box d-flex justify-content-center" style="margin-top: 55px;">
					        <a style="max-width:400px;" id="view-application-form-btn" class="vj-apply-btn" href="javascript:void(0);"><?php esc_html_e('Apply', 'career-page-by-vivahr');?></a>
					       
						</div>
					</div>
					
					<div class="vb-box job-application-view hidden">
					<div class="application-form-wrap">
                 <div class="application-form-header"></div>
                    <div class="application-form-body">
		               <form method="post" id="vivahr-application-form" enctype="multipart/form-data">
					      <input type="hidden" name="action" id="action" value="vivahr_candidates" />
					      <input type="hidden" name="job_post_id" id="job_post_id" value="<?php echo esc_attr(url_to_postid( get_permalink( get_the_ID() ) ));?>" />
                          <?php wp_nonce_field( 'cpt_nonce_action', 'cpt_nonce_field' ); ?>
					
						  <?php
						  foreach( $application_fields as $field )
						  {
						 	
						     if($field['disabled'] == 'no')
							 {
								if($field['type'] == 'text' || $field['type'] == 'phone' || $field['type'] == 'tel' || $field['type'] == 'email' || $field['type'] == 'url')
								{
								   echo '
							        <div class="vivahr-form-group">
						              <label for="'.esc_html($field['name']).'">'. esc_html($field['label']).'</label>
                                      <input '.(($field['required'] == 'required') ? 'required="required"' : '').' type="'.esc_html($field['type']).'" name="'.esc_html($field['name']).'" id="'.esc_html($field['name']).'" />
							        </div>
						           ';  	
								}
								elseif($field['type'] == 'file')
								{
								
									echo ' <div class="resume-box" id="ResumeUpload">
					           <strong>Upload a file </strong>&nbsp;<span>or drag and drop here</span>
					        </div>
			                <input style="display:none" type="file" name="resume" id="resume"/>';
						
								}
								elseif($field['type'] == 'textarea')
								{
									echo '
									<div class="vivahr-form-group">
						                <label for="'.esc_html($field['name']).'">'.esc_html($field['label']).'</label>
								    ';
								    
								    $content = "";
                                    $custom_editor_id = "coverletter";
                                    $custom_editor_name = "coverletter";
                                    $args = array(
		                                'media_buttons' => false, // This setting removes the media button.
		                                'textarea_name' => $custom_editor_name, // Set custom name.
		                                'textarea_rows' => get_option('default_post_edit_rows', 10), //Determine the number of rows.
		                                'quicktags' => false, // Remove view as HTML button.
	                                );
                                    wp_editor( $content, $custom_editor_id, $args );
									
									echo ' </div>';
								}
								
						 	    
							 }
							 
							
						  }
						  
						  ?>
						  
						  <div style="display: flex;justify-content: center;margin-top:55px;">
						   
                             <button style="max-width:400px;" id="vivahr-submit-application-btn" type="submit" class="vj-apply-btn"><?php esc_html_e('Submit Application') ?></button>
                          </div>
						  
						  <div style="text-align:center;margin-top: 75px;" class="footer center" bis_skin_checked="1">
		            <a href="https://vivahr.com"><img style="max-width: 100%;height: auto;" src="<?php echo VIVAHR_PLUGIN_URL;?>assets/public/images/powered_logo.svg" alt="VivaHR.com" width="250" height="88"> </a>
	            </div>
                       </form>
			        </div>   
                 <div class="application-form-footer">
				 
				 <?php
				 
				 // If the function it's not available, require it.


				
			?> 
				 
				 </div>
              </div>
					</div>
					
				</div>
				
				
			
			</div>
	<?php
}	
	
?>