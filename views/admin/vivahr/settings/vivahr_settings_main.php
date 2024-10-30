<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<div class="wrap container-fluid vivahr-settings">
   
    <div class="row">
        
		<div class="col-xs-12 vivahr-settings-navigation">
	        
			<ul class="vivahr-settings-menu">
	            
				<?php do_action('settings_menu', $settings_section); ?>
		    
			</ul>
        
		</div>
    
	</div>
  
    <div class="row vivahr-settings-section"> 	
        
		<div class="col-xs-12 col-sm-12 col-md-10">
            
			<div class="vivahr-settings-content <?php echo esc_attr( $page_class ); ?>-settings">
            
                <div class="vsc-header">
                    
					<h3 class="vsc-h-title">
					<?php 
					/* translators: %s: Section Title */
					printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $section_title ) );?>
					</h3>  
                
				</div>

                <div class="vsc-body">
                    
					<?php do_action( 'settings_content', $settings_section );?>
                
				</div>

            </div>        
            
        </div>	   
 
	</div>
	
</div>