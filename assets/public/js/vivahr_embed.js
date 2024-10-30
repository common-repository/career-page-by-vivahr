(function() {
	$(document).ready(function() {

		var baseUrl = 'https://jobs.vivahr.com/';
		var id = document.getElementById("srcid").getAttribute("data-id");
		var type = $("#srcid").attr("data-type");
		if (type == 'culture') {
			var cultureProfileUrl = baseUrl+"embed/culture_profile/"+id;
		} else {
			var cultureProfileUrl = baseUrl+"embed/jobs/"+id;
		}
		var jobDetailsUrl = baseUrl+"embed/job_details/";
		var applyJobUrl = baseUrl+"embed/apply_job/";
		var applyProcessUrl = baseUrl+"embed/apply_process/";
		var questionnaireUrl = baseUrl+"embed/questionnaire/";
		var applyFinalizeUrl = baseUrl+"embed/apply_finalize/";
		var processQuestionnaireUrl = baseUrl+"embed/process_questionnaire/";
		var applicationFormValidation = baseUrl+"embed/application_form_validation"
		//$('head').append('<link rel="stylesheet" href="'+baseUrl+'assets/js/embed/embed_style.css?v=3">');
		//$('head').append('<link rel="stylesheet" href="'+baseUrl+'assets/js/colorbox/colorbox.css">');
		$('head').append('<link rel="stylesheet" href="'+baseUrl+'assets/css/font-awesome-4.7.0/css/font-awesome.min.css">');
		$('head').append('<script src="'+baseUrl+'assets/js/colorbox/jquery.colorbox.js"></script>');

		$('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>');
		$('head').append('<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>');

		
		function router(curr_hash) {
		    var hash = curr_hash.substr(2);
		    var pathArray = hash.split( '/' );
		    var page_to_load = pathArray[0];
		    var jobId = pathArray[1];
		    // console.log(page_to_load+":"+id);
		    if (page_to_load == 'job_details') {
		    	loadJobDetails(jobId);
		    } 
		    else if (page_to_load == 'apply_job') {
		    	loadApplyJob(jobId);
		    } 
		    else {
		    	loadProfile();
		    }
		}
		$(window).bind('hashchange', function(e) {
		    router(e.target.location.hash);
		}); 
		// dont know what the next line is for and dont think I need it
		// window.location.hash && router(window.location.hash);


		$(document).on('click','.load_job',function() {
			// console.log("job link clicked");
			var jobID = $(this).attr('data-id');
			loadJobDetails(jobID);
		});
		
		$(document).on('submit','#apply_job_form',function(e) { 
			e.preventDefault(); 
			apply_process();
		});


		$(document).on('submit','#questionnaire_form',function(e) { 
			e.preventDefault(); 
			process_questionnaire();
		});




		function loadProfile()
		{
			window.scrollTo(0,0);
			$("#profileEmbed").load(cultureProfileUrl);
			setTimeout(function() {
				$.getScript(baseUrl+'assets/js/colorbox/jquery.colorbox.js');
				$.getScript(baseUrl+'assets/js/colorbox_scripts.js');
				$.getScript(baseUrl+'assets/js/embed/youtube.js');
			}, 1000);

		}

		function loadJobDetails(jobID)
		{	
			$("#profileEmbed").load(jobDetailsUrl+jobID);
			window.scrollTo(0,0);
		}

		function loadApplyJob(jobID)
		{	
			$.getScript( baseUrl+'assets/js/tinymce/tinymce.min.js' )
		  		.done(function( script, textStatus ) {
					$("#profileEmbed").load(applyJobUrl+jobID);
					window.scrollTo(0,0);
			  });
		}

		function validate_app_fields(data)
		{
			/*
			var error = 0;
			var errorMsg = "Please correct the following\r\n";
			
			if ($("#resume").val() == ''){
				error = 1;
				errorMsg += "Resume\r\n";
			}
			if ($("#name").val() == ''){
				error = 1;
				errorMsg += "Full Name\r\n";
			}
			var email = $('#email').val();
			var pattern = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/;
			if (!pattern.test(email)) {
			    error = 1;
				errorMsg += "Valid Email Address\r\n";
			}
			if ($("#phone").val().length < 10){
				error = 1;
				errorMsg += "Valid Phone\r\n";
			}

			if (error != 0){
				alert(errorMsg);
				return false;
			} else {
				return true;
			}
            */
			
	
		}

		function apply_process(formData)
		{
			$('.error-message').html("");
			var formData = new FormData($("#apply_job_form")[0]);
		
			$.ajax({
				type:'POST',
				url: applyProcessUrl,
				dataType: 'json',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {

					if(data.success == false && data.action == 'validation'){
						
						if (data.errors_array && Object.keys(data.errors_array).length > 0) {

                            for (var fieldName in data.errors_array) {

                                if (data.errors_array.hasOwnProperty(fieldName)) {
          
                                    var errorMessage = data.errors_array[fieldName];
            
			                        $('.'+fieldName+'_error').css('color', 'red').html('<small>'+errorMessage+'</small>');
									$('#'+fieldName).css('border', '1px solid red');
                                }
                            }
                        }
					} else if(data.success == true) {
						load_questionnaire(data.code);
					} else {
						alert(data.message);
					}
				}
			});

		}

		function load_questionnaire(code)
		{
			$.ajax({
				type:'POST',
				url: questionnaireUrl,
				dataType: 'json',
				data: {
					id: $("#jobID").val(),
					code: code
				},
				success: function(data) {
					window.scrollTo(0,0);
					if (data.has_questionnaire == false) {
						applyFinalize(code);
					} else {
						$("#profileEmbed").html(data.html);
					}
				}
			});
		}
		

		function applyFinalize(code)
		{
			$.ajax({
				type:'POST',
				url: applyFinalizeUrl,
				dataType: 'json',
				data: {
					id: $("#jobID").val(),
					code: code
				},
				success: function(data) {
					// window.scrollTo(0,0);
					alert(data.message);
					if (data.success == true) {
						window.location = "#/job_details/"+data.jobID;
					} 
				}
			});
		}


		function process_questionnaire()
		{
			if (!validate_questionnaire()) {
				return false;
			}
			$.ajax({
				type:'POST',
				url: processQuestionnaireUrl,
				dataType: 'json',
				data: $("#questionnaire_form").serialize(),
				success: function(data) {
					if (data.success == true) {
						applyFinalize(data.code);
					} else {
						alert('Something went wrong.');
					}
				}
			});
		}


		function validate_questionnaire()
		{
			var error = 0;

			$('.question-block').each(function() {
				if($(this).find(':radio').length > 0){
				    var val = $(this).children('input:radio:checked').val();
				    if (val === undefined) {
				        error = 1;
				    }
				}
			});
			
			$('.question-block').each(function() {
				if($(this).find(':checkbox').length > 0){
				    var val = $(this).children('input:checkbox:checked').val();
				    if (val === undefined) {
				        error = 1;
				    }
				}
			});

			$(".question-block textarea").each(function(){
				if ($(this).val() == '') {
					error = 1;
				}
			});

			if (error > 0) {
				alert("Please ensure each question is answered");
				return false;
			} 

			return true;

		}



		// Initial Load
		router(window.location.hash);
	});
})();
