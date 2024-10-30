<?php defined('ABSPATH') OR exit('No direct script access allowed'); ?>

<div class="vivahr-content-header">

    <div class="vivahr-item-content">

        <h3 class="vivahr-header-title"><?php esc_html_e('Application Form', 'career-page-by-vivahr');?></h3>

    </div>

</div>

<div class="vivahr-content-bodyy">

    <form id="application-form-form">

        <?php
	    $form = json_decode(get_option('vivahr_application_form'), true);
	    foreach($form as $form)
	    {
	        ?>
		    <div class="job-form-application-row">

		        <div class="job-form-application-row-col1">

			        <h4 class="job-form-input-header">
					<?php 
					
					/* translators: %s: Input Label */
                    printf( esc_html__( '%s', 'career-page-by-vivahr' ), esc_html( $form['label'] ) );
					?>
					</h4>
				
			        <div class="application-btns">

			            <div class="o-switch btn-group" data-toggle="buttons" role="group">

				            <?php								
				            if($form['name'] == 'name')
				            {
				                ?>
				                <label for="<?php echo esc_attr($form['name']);?>-required" class="btn btn-application-label active">
                                    
									<input checked="checked" class="hidden" type="radio" id="<?php echo esc_attr($form['name']);?>-required" name="<?php echo esc_attr($form['name']);?>[required]" value="1">
                                    <?php esc_html_e('Required', 'career-page-by-vivahr');?>
									
                                </label>
							    <?php
				            }	
				            elseif($form['name'] == 'email')
				            {
					            ?>
					            <label for="<?php echo esc_attr($form['name']);?>-required" class="btn btn-application-label active">
                       
					                <input checked="checked" class="hidden" type="radio" id="<?php echo esc_attr($form['name']);?>-required" name="<?php echo esc_attr($form['name']);?>[required]" value="1">
                                     <?php esc_html_e('Required', 'career-page-by-vivahr');?>
                     
					            </label>
					 
					            <?php
				            }	
				            else
				            {
				                ?>

					            <input class="hidden" type="radio" id="<?php echo esc_attr($form['name']);?>-required" name="<?php echo esc_attr($form['name']);?>[required]" value="<?php echo (($form['required'] == 'required') ? '1' : '0' );?>" <?php echo (($form['required'] == 'required') ? 'checked' : '' );?>>
					  
					            <label for="<?php echo esc_attr( $form['name'] );?>-required" class="btn btn-application-label <?php echo (($form['required'] == 'required' && $form['disabled'] == 'no') ? 'active' : '' );?>" > <?php esc_html_e('Required', 'career-page-by-vivahr');?></label>
					            <label for="<?php echo esc_attr( $form['name'] );?>-optional" class="btn btn-application-label <?php echo (($form['required'] == '' && $form['disabled'] == 'no') ? 'active' : '' );?>" > <?php esc_html_e('Optional', 'career-page-by-vivahr');?></label>
					            <label for="<?php echo esc_attr( $form['name'] );?>-on-off-label" class="btn btn-application-input <?php echo (($form['disabled'] == 'no') ? '' : 'active' );?>"> 

						        <input checked="checked" class="hidden" type="checkbox" id="<?php echo esc_attr($form['name']);?>-on-off" name="<?php echo esc_attr($form['name']);?>[disabled]" value="<?php echo (($form['disabled'] == 'yes') ? '1' : '0' );?>">
					            <?php esc_html_e('Off', 'career-page-by-vivahr');?>
						
					            </label>	
					            <?php
				            }					
				            ?>
			            </div>

			        </div>

			    </div>

		    </div>
		    <?php
        }
	    
		?>
	 
	  <input type="hidden" name="action" id="action" value="application-form-submit" />

      <?php wp_nonce_field( 'application_form_nonce_action', 'application_form_field' ); ?>

        <div class="row" style="margin-top:35px;">
	       <div class="col-xs-12">
		       <div class="fr">
		          <button class="vivahr-btn-primary" id="save-application-form" ><?php esc_html_e('Save Changes', 'career-page-by-vivahr'); ?></button>
               </div>
		   </div>
	    </div>

   </form>

</div>