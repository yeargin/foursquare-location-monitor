<?php $api_key = $this->config->item('google_developer_key'); ?>

<?php if ( isset($venue->location->lat)  && isset($venue->location->lng) ): ?>
<script type="text/javascript"
	src="http://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&sensor=true">
</script>
<script type="text/javascript">

	// Set map options
	function initialize() {
	
		// Used for placemarker and centering
		var venueLocation = new google.maps.LatLng(<?php echo $venue->location->lat; ?>, <?php echo $venue->location->lng; ?>);
	
		var myOptions = {
			center: venueLocation,
			zoom: 16,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map"),
				myOptions);

		<?php if (isset($nearby) && is_array($nearby)): ?>
		<?php foreach($nearby as $k => $nearby_item): ?>
		// Nearby #<?php echo $k; ?>
		var nearby_<?php echo $k; ?> = <?php echo json_encode($nearby_item); ?>;
		var nearby_loc_<?php echo $k; ?> = new google.maps.LatLng(<?php echo $nearby_item->location->lat; ?>, <?php echo $nearby_item->location->lng; ?>);
		var nearby_marker_<?php echo $k; ?> = new google.maps.Marker({
				position: nearby_loc_<?php echo $k; ?>,
				map: map,
				title:"<?php echo addslashes($nearby_item->name); ?>",
				<?php if (isset($nearby_item->categories[0])): ?>
				icon: '<?php echo $nearby_item->categories[0]->icon->prefix . '32.png'; ?>',
				<?php endif; ?>
				zIndex: <?php echo $k; ?>
		});
		google.maps.event.addListener(nearby_marker_<?php echo $k; ?>, 'click', function() {
			window.location = '<?php echo site_url('foursquare/venue') .'/'. $nearby_item->id; ?>';
		});
	
		<?php if (isset($nearby_item->categories[0])): ?>
		nearby_marker_<?php echo $k; ?>.setIcon('<?php echo $nearby_item->categories[0]->icon->prefix . '32.png'; ?>');
		<?php endif; ?>
		
		<?php endforeach; ?>
		<?php endif; ?>
		
		// Venue Marker
		var marker = new google.maps.Marker({
				position: venueLocation,
				map: map,
				title:"<?php echo addslashes($venue->name); ?>",
				zIndex: 100
		});
		
		<?php if (isset($venue->categories[0])): ?>
		// Set marker icon
		marker.setIcon('<?php echo $venue->categories[0]->icon->prefix . '32.png'; ?>');
		<?php endif; ?>

		$('#map').removeClass('spinner');

	}

	// Bind init method
	$('#map').ready(function() {
		initialize();
	});

</script>
<?php else: ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#map').html('<p class="alert alert-error">Could not load map. :-(</p.').removeClass('spinner');
	});
</script>
<?php endif; ?>