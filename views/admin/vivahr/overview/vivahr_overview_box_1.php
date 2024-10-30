<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<script id="handlebars-demo" type="text/x-handlebars-template">
   
    <div class="vo-b-section col-xs-12 col-sm-4">

   	    <div class="vo-b-s-content-1-1">

            <p> {{ new_candidates }} </p>

            <div class="d-flex" style="flex-direction: column;">
   		
                <span style="font-size:16px;">
   		    
   			        {{#ifGreater new_candidates_diff '0'}}
   			        
   					    {{abs new_candidates_diff}}	<img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_up_1.png');?>"/>

   				    {{else}}

                        {{abs new_candidates_diff}} <img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_down_1.png');?>"/>
        
                    {{/ifGreater}}
   
                </span>
   			
                <span style="font-size:13px;">{{range_name}}</span>
  
            </div>

        </div>
		 
		<div class="vo-b-s-content-1-2">
		
            <img loading="lazy" style="max-width:135px;" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/new_candidates.png');?>">

        </div>
   	
        <div class="vo-b-s-content-1-3">
   	
            <span><?php esc_html_e( 'New Candidates', 'career-page-by-vivahr' );?></span>
   	   
        </div>
   	
    </div>
   
    <div class="vo-b-section col-xs-12 col-sm-4">
   
        <div class="vo-b-s-content-2-1">
   	
            <p>{{total_hired}}</p>
   		
            <div class="d-flex" style="flex-direction: column;">
   		
                <span style="font-size:16px;">
   
   			        {{#ifGreater total_hired_diff '0'}}
   			        
   					    {{abs total_hired_diff}}	
						<img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_up_2.png');?>"/>

   				    {{else}}
            
                        {{abs total_hired_diff}} 
						<img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_down_2.png');?>"/>

                    {{/ifGreater}}

                </span>
                
				<span style="font-size:13px;">{{range_name}}</span>
   			
            </div>
   		
        </div>

        <div class="vo-b-s-content-2-2">
   	
            <img loading="lazy" style="max-width:135px;" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/total_hired.png');?>">
   	   
        </div>
   	
        <div class="vo-b-s-content-2-3">

            <span><?php esc_html_e( 'Total Hired', 'career-page-by-vivahr' );?></span>
   	   
        </div>
   	
    </div>
   
    <div class="vo-b-section col-xs-12 col-sm-4">
   
        <div class="vo-b-s-content-3-1">
   	
            <p>{{total_disqualified}}</p>
   		
            <div class="d-flex" style="flex-direction: column;">

   		        <span style="font-size:16px;">

   				    {{#ifGreater total_disqualified_diff '0'}}
   			        
   					    {{abs total_disqualified_diff}}	
						<img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_up_3.png');?>"/>

   				    {{else}}
            
                        {{abs total_disqualified_diff}} 
						<img loading="lazy" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/arrow_down_3.png');?>"/>

                    {{/ifGreater}}

   		        </span>
                   
                <span style="font-size:13px;">{{range_name}}</span>
   		   
            </div>
   		
        </div>

        <div class="vo-b-s-content-3-2">
   	
            <img loading="lazy" style="max-width:135px;" src="<?php echo esc_url($core->vivahrAdminAssetsPath.'/images/total_disqualified.png');?>">
   	   
        </div>
   	
        <div class="vo-b-s-content-3-3">
   	
            <span><?php esc_html_e( 'Total Disqualified', 'career-page-by-vivahr' );?></span>
   	   
        </div>
   	
    </div>
   
</script>
							
<script id="hb-active-jobs" type="text/x-handlebars-template">
    
	{{#if active_jobs.length}}
    
	    {{#each active_jobs}}
            
			<tr>
			
                <td>
				    <div class="d-flex align-items-end" style="">
				        <h4 style="margin-bottom: 0px;font-style: normal;font-weight: 500;font-size: 14px;line-height: 18px;color: #000000;">{{job_title}}</h4>
						<a style="text-indent: 0;text-decoration:none;" target="_blank" href="{{job_link}}"><span style="color: #0868F6;width: 16px;height: 16px;font-size: 16px;" class="dashicons dashicons-external"></span></a>
					</div>
					<p>{{name}}</p>
				</td>
				
                <td>
                    {{#check remote_option null}}
               
			            {{city}}, {{state}}
			   
			        {{else}}
			   
			            <div style="color:#0868f6">{{remote_option}}</div>
			   
                    {{/check}}
				</td>
				
                <td>{{convertDate last_published_at}}</td>
				
                <td>
				    {{candidate_count}} 
					
					{{!#check candidate_count '0'}}
						
					{{!else}}
					        
						<div class="pill pill-hollow pill-small">{{new_candidate_count}} new</div>
					
					{{!/check}}
				</td>
				
                <td>{{status_name}}</td>
				
            </tr>
							 
        {{/each}}
    
	{{else}}
	
	    <tr>
		    <td>
			    <span style="text-indent:initial;" class="dashicons dashicons-open-folder"></span> 
				<?php esc_html_e( 'No Job Openings!', 'career-page-by-vivahr' );?>	
			</td>
		</tr>

	{{/if}} 
	
</script>