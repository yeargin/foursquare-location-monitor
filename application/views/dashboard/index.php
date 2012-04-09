<?php if ($has_foursquare): ?>

	<?php if (is_array($dashboard_modules) && count($dashboard_modules) > 0): ?>
	<?php foreach ($dashboard_modules as $module): ?>
	<div class="dashboard-module span4">
		<p class="pull-right">
			<a href="<?php echo site_url('foursquare/venue') .'/'. $module['venue_id']; ?>" class="btn btn-small" rel="tooltip" title="View Venue"><i class="icon-map-marker"></i></a>
			<a href="<?php echo site_url('checks/check_edit') .'/'. $module['check_id']; ?>" class="btn btn-small" rel="tooltip" title="Edit Check"><i class="icon-pencil"></i></a>
			<a href="<?php echo site_url('checks/check') .'/'. $module['check_id']; ?>" class="btn btn-small" rel="tooltip" title="View Check Log"><i class="icon-book"></i></a>
		</p>
		<h3>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $module['venue_id']; ?>"><?php echo (strlen($module['check_title']) > 20) ? __(substr($module['check_title'], 0, 22)).'&#133;' : __($module['check_title']); ?></a>
		</h3>
		<table class="table table-condensed">
			<tr>
				<th>Date</th>
				<th>Total Checkins</th>
				<th>Unique Visitors</th>
				<th>Tips Left</th>
				<th>Photos</th>
			</tr>
			<?php if (isset($module['total_checkins'])): ?>
			<?php foreach (array_keys($module['total_checkins']) as $date): ?>
			<tr>
				<td class="decimal"><?php echo date('n/j', strtotime($date)); ?></td>
				<td><?php echo number_format($module['total_checkins'][$date]); ?></td>
				<td><?php echo number_format($module['unique_visitors'][$date]); ?></td>
				<td><?php echo number_format($module['tips_left'][$date]); ?></td>
				<td><?php echo number_format($module['photo_count'][$date]); ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="5"><em>Not enough check history yet.</em></td>
			</tr>
			<?php endif; ?>
		</table>

	</div>
	<?php endforeach; ?>
	<?php else: ?>
		<div class="hero-unit">
			<h2>Excellent!</h2>
			<p>
				You are authenticated with Foursquare! Now, <a href="<?php echo site_url('foursquare/search'); ?>">search for venues</a> to monitor.
			</p>
		</div>
	<?php endif; ?>
<?php else: ?>

<div class="hero-unit">
	<h2>Welcome!</h2>
	<p>
		Your first step is to <a href="<?php echo site_url('foursquare/authenticate'); ?>">authenticate with Foursquare</a>.
	</p>
</div>

<?php endif; ?>