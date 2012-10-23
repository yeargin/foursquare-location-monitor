<script type="text/javascript">

$(document).ready(function(){
	$('#modal .modal-body').load('/checks/ajax_modal');
})

function openCheckModal( element ) {

	console.log('open');
	console.log($(element).data());

	var check_id = $(element).data('check_id');
	var venue_id = $(element).data('venue_id');
	var check_title = $(element).data('check_title');

	Checks.show({
		check_id : check_id,
		venue_id : venue_id,
		check_title : check_title
	});
	
}

</script>