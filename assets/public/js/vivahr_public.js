(function( $ ) {
	'use strict';

    $(document).on('click', '#view-application-form-btn', function() {
		
		$('.job-menu').trigger('click');
		
	});
	
	$(document).on('click', '.job-menu', function() {
	  
	    if($(this).hasClass('active'))
		{
			return;
		}
		else
		{
			$('.job-menu').removeClass('active');
			$(this).toggleClass('active');
			
			var id = $(this).attr('id');
			
			$('.vb-box').fadeOut();
			$('.'+id+'-view').fadeIn();
		}
	
    });
	

	$(document).on("focusout", '#vj-search-jobs', function() {  
	    
	    let pageURL = window.location.href.split('?')[0]
        var search_value = $(this).val();
		var loc = '';
		var department = '';
		var position = '';
		
		if($('#location').val() != ''){
			loc = '&l='+$('#location').val();
		}		
		
		if($('#department').val() != ''){
			department = '&d='+$('#department').val();
		}		
		
		if($('#position').val() != ''){
			position = '&wt='+$('#position').val();
		}
		
		var query = '?j='+search_value+loc+department+position;
		
		$('#filter-query').val(pageURL+query);
		
    } );
	
	$(document).on("change", '#location', function() {  
	    $('#vj-search-jobs').trigger('focusout');
	} );
	
	$(document).on("change", '#department', function() {  
	    $('#vj-search-jobs').trigger('focusout');
	} );
	
	$(document).on("change", '#position', function() {  
	    $('#vj-search-jobs').trigger('focusout');
	} );
	
	$(document).on('click', '#vivhar-filter-jobs', function() {
        var url = $('#filter-query').val();
		console.log(url);
		window.location.href = url;
	
	} );
	
})( jQuery );
