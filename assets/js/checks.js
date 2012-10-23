var Checks = {
	'show' : function( form_data ) {
				
		$('#modal').modal({
			show: true,
			keyboard: true,
			background: true
		});
		
		// Double check that a form is loaded into the modal body
		if ($('input[name="check_id"]').length == 0) {
			$('#modal .modal-body').load('/checks/ajax_modal');
		}
		
		// Load check data or submitted data
		if (typeof form_data.check_id == 'number') {
			$.post('/checks/ajax_get_check/'+form_data.check_id, function(data) {
				if (typeof data == 'object') {
					$('#modal_title').html('Update Monitored Location');
				} else {
					$.meow({ message : 'Could not load selected monitored location.'});
				}
			});
		} else {
			$('#modal_title').html('Add Monitored Location');
		}

		$('#checkModal input[name="check_id"]').val(form_data.check_id);
		$('#checkModal input[name="check_title"]').val(form_data.check_title);
		$('#checkModal input[name="venue_id"]').val(form_data.venue_id);

		// Activate submit button
		$('a#modal_save').bind('click', function() {
			// Determine if we're updating a check or adding one
			if (typeof check_id == 'number') {
				Checks.edit(check_id, {
					check_id : $('#checkModal input[name="check_id"]').val(form_data.check_id),
					check_title : $('#checkModal input[name="check_title"]').val(form_data.check_title)
				});
			} else {
				Checks.add({
					venue_id : $('#checkModal input[name="venue_id"]').val(form_data.venue_id),
					check_title : $('#checkModal input[name="check_title"]').val(form_data.check_title)
				});
			}
		});
		
	},
	'add' : function ( form_data ) {
		
		$.post('/checks/ajax_add_check', {
			venue_id : form_data.venue_id,
			check_title : form_data.check_title
		}, function( response ) {
			if (response == true) {
				$.meow({ message : 'Monitored location added!' });
			} else {
				$.meow({ message : 'Could not add monitored location.' });
			}
			
		});
		
		$('#modal').modal('hide');
		
	},
	'edit' : function ( check_id, form_data ) {
		$.post('/checks/ajax_edit_check/'+check_id, {
			check_id : form_data.check_id,
			check_title : form_data.check_title
		}, function( response ) {
			if (!response.error) {
				$.meow({ message : 'Monitored location updated!' });
			} else {
				$.meow({ message : response.error });
			}
		});
		
		$('#modal').modal('hide');
		
	},
	'deactivate' : function ( check_id ) {
		$.post('/checks/ajax_deactivate_check/'+check_id);
	},
	'activate' : function ( check_id ) {
		$.post('/checks/ajax_activate_check/'+check_id);
	}
};