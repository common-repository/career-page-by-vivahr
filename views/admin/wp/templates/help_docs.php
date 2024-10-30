<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<style>
.vhdbs-desc a{
	text-decoration:none;
}
</style>

<div class="vivahr-help-docs">
   <div class="vhd-header">
      <h3 class="vhdh-title"><?php esc_html_e('Help Docs', 'career-page-by-vivahr');?></h3>
   </div>
   <div class="vhd-body">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 vhdb-section d-flex align-items-center">
         <div style="width: 100%; max-width: 15%;" class=""> 
            <img src="<?php echo esc_url($core->vivahrAdminAssetsPath).'/images/help_docs_1.png';?>">
         </div>
         <div class="vhdbs-desc">
		    <a target="_blank" style="text" href="<?php echo esc_url('https://knowledge.vivahr.com/wordpress-plugin-overview');?>">
            <h3 class="vhdbs-d-header"><?php esc_html_e('Docs and How to', 'career-page-by-vivahr');?></h3>
            <p class="vhdbs-d-title"> <?php esc_html_e('Learn how to use the plugin', 'career-page-by-vivahr');?></p>
			</a>
         </div>
      </div>
      <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 vhdb-section d-flex align-items-center">
         <div style="width: 100%; max-width: 15%;" class=""> 
            <img src="<?php echo esc_url($core->vivahrAdminAssetsPath).'/images/help_docs_2.png';?>">
         </div>
         <div class="vhdbs-desc">
		 <a target="_blank" style="text" href="<?php echo esc_url('https://vivahr.com/demo/');?>">
            <h3 class="vhdbs-d-header"><?php esc_html_e('Interactive Tour', 'career-page-by-vivahr');?></h3>
            <p class="vhdbs-d-title"> <?php esc_html_e('A quick guided turtorial', 'career-page-by-vivahr');?></p>
		 </a>
         </div>
      </div>
      <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12 vhdb-section d-flex align-items-center">
         <div style="width: 100%; max-width: 15%;" class=""> 
            <img src="<?php echo esc_url($core->vivahrAdminAssetsPath).'/images/help_docs_3.png';?>">
         </div>
         <div class="vhdbs-desc">
		 <a target="_blank" style="text" href="<?php echo esc_url('https://vivahr.com/job-description-templates/');?>">
            <h3 class="vhdbs-d-header"><?php esc_html_e('Job Description Templates', 'career-page-by-vivahr');?></h3>
            <p class="vhdbs-d-title"><?php esc_html_e('Better job descriptions attract better candidates.', 'career-page-by-vivahr');?></p>
		 </a>
         </div>
      </div>
      <div class="col-xs-12 col-sm-12" style="padding: 25px;">
         <div class="want-more-candidates">
            <h3><?php esc_html_e('Want More Candidates?', 'career-page-by-vivahr');?></h3>
            <p> <?php esc_html_e('Get full access to VIVAHR Applicant Tracking Software', 'career-page-by-vivahr');?></p>
            <a target="_blank" href="<?php echo esc_url(VIVAHR_APP_URL.'get-started');?>"> <?php esc_html_e('Upgrade Now', 'career-page-by-vivahr');?></a>
         </div>
      </div>
   </div>
</div>