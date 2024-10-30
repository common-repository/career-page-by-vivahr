<?php defined('ABSPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid vivahr-navbar">
	
	<div class="row">
	
    	<div class="col-xs-12 col-sm-12 col-md-12">
			
			<div class="d-flex align-items-center justify-content-between" style="padding: 5px 0;"> 
			    
				<a class="vivahr-navbar-brand d-flex align-items-center" href="admin.php?page=vivahr_overview">  
	                
					<img src="<?php echo esc_url($core->vivahrAdminAssetsPath).'/images/VIVAHR_Blue_Icon.png';?>" border="0"/> 
		            <h4><?php esc_html_e( 'Career Page by VIVAHR', 'career-page-by-vivahr' );?></h4>
	            
				</a>
			    
				<ul class="hidden-sm vivahr-navbar-nav d-flex align-items-center">
	                
					<?php //do_action( 'vivahr_admin_nav_links', sanitize_text_field($_GET['page']) );?>
                    <li class="nav-item <?php if( isset($_GET['page']) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) == 'vivahr_overview'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'admin.php?page=vivahr_overview' );?>"><?php esc_html_e('Overview', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item <?php if( isset($_GET['post_type']) && sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) == 'vivahr_jobs'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'edit.php?post_type=vivahr_jobs' );?>"><?php esc_html_e('Job Openings', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item <?php if( isset($_GET['post_type']) && sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) == 'vivahr_candidates'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'edit.php?post_type=vivahr_candidates' );?>"><?php esc_html_e('Applications', 'career-page-by-vivahr');?></a></li>
					<li class="nav-item <?php if( isset($_GET['page']) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) == 'vivahr_settings'){echo 'active';}?>"><a class="nav-link" href="<?php echo esc_url( get_admin_url().'admin.php?page=vivahr_settings' );?>"><?php esc_html_e('Settings', 'career-page-by-vivahr');?></a></li>
					
					<?php
					
					$link = '';
					
					if( !empty( get_option('vivahr_jobs_listing_page') ) )
		            {
				        $post_id = sanitize_text_field(get_option('vivahr_jobs_listing_page'));
				
				        if ( !is_numeric($post_id) )
				        {
					
				            $link = '';
				        }
				        else
				        {
					        $post = get_post( $post_id );
			    
                            if( !empty($post) )
				            {
				
						        $link = get_site_url().'/'.$post->post_name;

				            }		
				        }
		
		            }
					?>
					
					<li class="nav-item"><a class="nav-link vivahr-external-link-secondary" target="_blank" href="<?php echo esc_url($link);?>">View Career Page</a></li>
					
			    </ul>
			   
		    </div>  
			
		</div>	
		
	</div> 
	
</div>

<div>

	<input type="checkbox" id="one" class="hidden" checked>  
	
	<label for="one" class="alert-message-box d-flex align-items-center justify-content-between">
	
        <span class="alert-message"></span>
		
    </label>
	
</div> 