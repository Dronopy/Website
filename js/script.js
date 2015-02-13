var DEBUG = true;
$(function() {

	$(".login-btn").click(function(e) {
		window.location.href = "http://drone.sharetribe.com/en/login";
	});
	$(".signup-btn").click(function(e) {
		window.location.href = "http://drone.sharetribe.com/en/signup";
	});
	$("#alert-close").click(function(e) {
		hideAlert();
	});
	$(".do-wobble").mouseenter(function(e) {
		$(e.target).addClass('animated wobble');
	}).on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(e) {
		$(e.target).removeClass('animated wobble');
	});

	$(window).load(function() {
		$(".content").fadeTo(1000, 1, 'swing', function() {
			$("body, html").css('height', '100%');
			$("footer").fadeTo(1000, 1, 'swing');
		});
	});

	$('#contactform').ajaxForm({
		url: "contact.php",
		success: function(res) {
			log('Form submit response received.', res);
			// check if result indicates a success or error
			// TODO
			showAlert('success', 'Your message has been sent.');
		},
		error: function()  {
		}
	});

	$("#contactform").validate({
		debug: DEBUG,
		rules: {
			formName: {
				required: true,
				minlength: 2
			},
			formEmail: {
				required: true
			},
			formPhone: {
				minlength: 10
			},
			formLocation: {
				required: true,
				minlength: 2
			},
			formDescription: {
				required: true,
				minlength: 10
			}
		},
		messages: {
			formName: {
				required: 'Please enter a name.',
				minlength: $.validator.format("At least {0} characters required.")
			},
			formEmail: {
				required: 'Please enter an email address.',
				email: 'Please enter a valid email address format.'
			},
			formPhone: {
				required: 'Please enter a phone number.',
				minlength: $.validator.format("At least {0} characters required.")
			},
			formLocation: {
				required: 'Please enter a location.',
				minlength: $.validator.format("At least {0} characters required.")
			},
			formDescription: {
				required: 'Please enter a description.',
				minlength: $.validator.format("At least {0} characters required.")
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block error-help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		},
		invalidHandler: function(event, validator) {
			log('Invalid form.');
		},
		submitHandler: function(form) {
			log('Performing submit.');
			$(form).ajaxSubmit();
		}
	});
});

function showAlert(type, text)
{
	$("#alert-text").html(text);
	$("#alert").removeClass('alert-success alert-info alert-warning alert-danger').addClass('alert-'+type);
	$("#alert").show().addClass('in');
}
function hideAlert()
{
	$("#alert").removeClass('in').hide();
}

// usage: log('inside coolFunc',this,arguments);
// http://paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
// added support for debug flag
window.log = function(){
	log.history = log.history || [];   // store logs to an array for reference
	log.history.push(arguments);
	if(DEBUG && this.console){
		console.log( Array.prototype.slice.call(arguments) );
	}
};