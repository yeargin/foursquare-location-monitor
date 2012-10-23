<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawCharts);
	
	function drawCharts() {
		drawChartLive();
		drawChartDaily();
	}

	// Daily Chart
	function drawChartDaily() {
		<?php if (isset($daily_data_delta) && count($daily_data_delta) > 2): ?>
		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Total Checkins');
		data.addColumn('number', 'Unique Visitors');
		data.addColumn('number', 'Tips');
		data.addColumn('number', 'Photos');
		data.addRows([
			<?php foreach ($daily_data_delta as $k => $row): ?>
			[new Date('<?php echo date('n/j/Y', strtotime($k)); ?>'), <?php echo ($row->total_checkins); ?>, <?php echo ($row->unique_visitors); ?>, <?php echo ($row->tips_left); ?>, <?php echo ($row->photo_count); ?>],
			<?php endforeach; ?>
		]);

		var options = {
			width: '100%',
			height: 275,
			title: '<?php echo addslashes($check->check_title); ?>',
			hAxis: {format : 'M/d/yy'}
		};

		var chartDaily = new google.visualization.LineChart(document.getElementById('chart_daily'));
		chartDaily.draw(data, options);
		<?php endif; ?>
	}
	
	// Live Chart
	function drawChartLive(json_live_data) {
		<?php if (isset($live_data) && count($live_data) > 2): ?>
		var data = new google.visualization.DataTable();
		data.addColumn('datetime', 'Date');
		data.addColumn('number', 'Friends');
		data.addColumn('number', 'Other');
		data.addColumn('number', 'Total Here Now');
		data.addRows([
			<?php foreach ($live_data as $k => $row): ?>
			[new Date('<?php echo date('n/j/Y H:i', strtotime($row->insert_ts)); ?>'), <?php echo ($row->herenow_friends); ?>, <?php echo ($row->herenow_other); ?>, <?php echo ($row->herenow_total); ?>],
			<?php endforeach; ?>
		]);

		var options = {
			width: '100%',
			height: 275,
			title: '<?php echo addslashes($check->check_title); ?>',
			hAxis: {format : 'M/d/yy h:mm a'}
		};

		var chartLive = new google.visualization.LineChart(document.getElementById('chart_live'));
		chartLive.draw(data, options);
		<?php endif; ?>
	}
	
	// Handle resizers, spinners
	$(document).ready(function() {
		
		// Resize chart if window is resized
		$(window).resize(function() {
				if(this.resizeTO) clearTimeout(this.resizeTO);
				this.resizeTO = setTimeout(function() {
						drawCharts();
				}, 500);
		});
		
		// Remove spinner class when chart loaded
		$('#chart_live').removeClass('spinner');
		$('#chart_daily').removeClass('spinner');
		
	});

</script>