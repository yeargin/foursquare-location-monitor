<?php if ($has_foursquare): ?>

	<?php foreach ($dashboard_modules as $module): ?>
	<div class="well">
		<h3>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $module['venue_id']; ?>"><?php echo __($module['check_title']); ?></a>
			<small><a href="<?php echo site_url('checks/check') .'/'. $module['check_id']; ?>">Check Log</a>
			</small>
		</h3>
		<table class="table">
			<tr>
				<th>Date</th>
				<th>Total Checkins</th>
				<th>Unique Visitors</th>
				<th>Tips Left</th>
				<th>Photos</th>
			</tr>
			<?php foreach (array_keys($module['total_checkins']) as $date): ?>
			<tr>
				<td><?php echo date('F j, Y', strtotime($date)); ?></td>
				<td><?php echo number_format($module['total_checkins'][$date]); ?></td>
				<td><?php echo number_format($module['unique_visitors'][$date]); ?></td>
				<td><?php echo number_format($module['tips_left'][$date]); ?></td>
				<td><?php echo number_format($module['photo_count'][$date]); ?></td>
			</tr>
			<?php endforeach; ?>
		</table>

	</div>
	<?php endforeach; ?>

<?php else: ?>

Welcome! Your first step is to authenticate with foursquare.

<?php endif; ?>