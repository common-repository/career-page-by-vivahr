<?php defined('ABSPATH') OR exit('No direct script access allowed');?>

<div class="wrap container-fluid vivahr-dashboard">

    <div class="row">
        
	    <div class="col-xs-12 col-sm-12 col-md-8 d-flex align-items-center">
			 
			<h3 class="page-title"><?php esc_html_e( 'Dashboard', 'career-page-by-vivahr');?></h3>

		</div>	  
	 
   	    <div class="col-xs-12 col-sm-12 col-md-4 d-flex justify-content-end align-items-center">
		
		<a class="v-button-primary" href="<?php echo esc_html( VIVAHR_APP_URL ); ?>jobs/create_job" target="_blank">+ <?php esc_html_e('Add New Job', 'career-page-by-vivahr');?></a>
		
		</div>

    </div>

    <div class="row" style="margin: 20px -25px 45px -25px;">
	
        <div class="col-xs-12 col-md-10 col-lg-10">   
		
		    <div class="row">
	
                <div class="col-xs-12 col-md-12 col-lg-12">
	            
				    <div class="vivahr-panel">
                    
					    <div class="vh-header">
						
                            <h3 class="vhh-title"><?php esc_html_e( 'Overview', 'career-page-by-vivahr' );?></h3>
							
		                    <select class="overview-range">
							
			                    <option  value="week"><?php esc_html_e( 'This Week', 'career-page-by-vivahr' );?></option>
								
			                    <option  value="month"><?php esc_html_e( 'This Month', 'career-page-by-vivahr' );?></option>
								
			                    <option  value="year"><?php esc_html_e( 'This Year', 'career-page-by-vivahr' );?></option>
								
			                </select>
							
							<?php wp_nonce_field( 'overview_details', 'overview_details_nonce' ); ?>
                        
						</div>
                        
						<?php do_action('overview_box_1');?>
					    <div class="vh-body"></div>
						
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
                  
				            <tbody id="hb-active-jobs-table"></tbody>
                        
						</table>
                    
					</div>
			
			    </div>
         
		    </div>
	    
		</div>
		
   </div>

</div>

<script>
	
	
jQuery ( function($) {
    
	$(window).load(function() {
        
        load_overview_1_box();
		
    });
	
	
	$('.overview-range').on('change', function() {	
	    
		$('.vh-body').html("");
		$('#hb-active-jobs-table').html("");
	    load_overview_1_box();
		
		
    });
    
	function load_overview_1_box() {
		
		var overview_range = $('.overview-range').val();
		
		var nonce = $('#overview_details_nonce').val();
		
        $.post( "admin-ajax.php", { action: "api_overview", range: overview_range, nonce:nonce }, function( data ) {
            
			Handlebars.registerHelper('abs', function (arg) {
				let value = Math.abs(arg);
				return value;
			});
			
			Handlebars.registerHelper('convertDate', function (arg) {
				
				let date = new Date(arg);
				
				return date.toDateString().split(' ').slice(1).join(' ');
			});
			
			Handlebars.registerHelper('ifGreater', function(arg1, arg2, options) {
				return (arg1 > arg2) ? options.fn(this) : options.inverse(this);
            });
			
			Handlebars.registerHelper('check', function(value, comparator, options) {
                return (value === comparator) ? options.fn(this) : options.inverse(this);
            });
			
			
	
			
			// Retrieve the template data from the HTML (jQuery is used here).
            var template = $('#handlebars-demo').html();
			
			// Compile the template data into a function
            var templateScript = Handlebars.compile(template);

            var context = data;

            var html = templateScript(context);

            // Insert the HTML code into the page
            $('.vh-body').append(html);
			
			// Retrieve the template data from the HTML (jQuery is used here).
            var template1 = $('#hb-active-jobs').html();
			
			// Compile the template data into a function
            var templateScript1 = Handlebars.compile(template1);

            var context1 = data;

            var html1 = templateScript1(context1);

            // Insert the HTML code into the page
            $('#hb-active-jobs-table').append(html1);
        }, "json");
		
    }
	
} );

</script>