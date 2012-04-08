<script type="text/javascript">
$(document).ready(function() {

	var availableTags = <?php echo json_encode(array_keys($tag_list)); ?>;

	$('form#tag-form input').keypress(function(e){
		if(e.which == 13){
			e.preventDefault();
			saveTags();
		}
	});	

});

// Open Tag Modal
function openTagModal(element) {
	var check_title = $(element).attr('data-check_title');
	var check_id = $(element).attr('data-check_id');
	var tags = $(element).attr('data-tags').split(', ');
	var tagField = $('#tags');
	
	// Clear current values
	tagField.val('');

	// Set modal name
	$('.modal-header h3').html(check_title);

	// Set hidden field
	$('form#tag-form input#check_id').val(check_id);

	// Get list of existing tags
	$('#existing_tags').html('');
	for (item in tags) {
		if (tags[item] == '') { $('existing_tags').html('(None)'); continue; }
		$('#existing_tags').append('<span class="label label-warning" onclick="removeTag(this);" style="cursor:pointer;" data-check_id="'+check_id+'" data-tag="'+tags[item]+'">'+tags[item]+' &times;</span> ')
	}
	
	return;
}

// Select Tag
function selectTag(tag) {
	var tag = $(tag).text();
	var tagField = $('#tags');
	if (tagField.val() == '') {
		tagField.val(tag);
	} else {
		tagField.val(tagField.val()+', '+tag);
	}
}

// Save Tag Form
function saveTags() {

	// Form variables
	var tagField = $('#tags');
	var checkIdField = $('#check_id');

	// Process
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('checks/ajax_tags'); ?>',
		data: {check_id : checkIdField.val(), tags : tagField.val(), action : 'add_tags' },
		success: function() { $('#tagModal').modal('hide'); },
		dataType: 'json'
	});
	
	// Update tag list in table
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('checks/ajax_tags'); ?>',
		data: {check_id : checkIdField.val(), action : 'list_tags' },
		success: function(data) { 
			$('tr#check_'+checkIdField.val()+' .taglist').html(function() {
				celltags = '';
				for (x in data) {
					celltags += '<span class="label label-warning" onclick="removeTag(this);" style="cursor:pointer;" data-check_id="'+checkIdField.val()+'" data-tag="'+data[x].tag+'">'+data[x].tag+' &times;</span> '
				}
				return celltags;
			})
		},
		dataType: 'json'
	});

	return;
}

function addTag(check_id, tag, div) {

	tag = tag.replace(/[^a-z 0-9]+/gi,'');

	// Process
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('checks/ajax_tags'); ?>',
		data: {check_id : check_id, tags : tag, action : 'add_tags' },
		success: function() { $(div).append('<span class="label">'+tag+'</span>'); },
		dataType: 'json'
	});

}

function removeTag(el) {
	
	var tag = $(el).attr('data-tag');
	var check_id = $(el).attr('data-check_id');
	
	tag = tag.replace(/[^a-z 0-9]+/gi,'');

	// Process
	$.ajax({
		type: 'GET',
		url: '<?php echo site_url('checks/ajax_tags'); ?>',
		data: {check_id : check_id, tags : tag, action : 'remove_tag' },
		success: function() { $(el).fadeOut().remove(); },
		dataType: 'json'
	});

}

</script>