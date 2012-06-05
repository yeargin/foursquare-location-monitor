$(document).ready(function() {

	// Meow
	$('#onready div').each( function(index) {
		var is_sticky = $(this).hasClass('is-sticky');
		$.meow({
			message: $(this).html(),
			sticky: is_sticky
		});
	});

	$(".alert-message").alert();

	$('.spinner').spin({lines: 10, length: 3, radius: 3, trail: 60, speed: 1.0, width: 2});

	$('a[rel="tooltip"]').tooltip();

	$('a.confirm').click(function(event) {
		event.stopPropagation();
		return confirm('Are you sure you want to do that?');
	});
	
	$('input[type="date"]').datepicker();

});