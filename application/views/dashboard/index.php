<?php if ($has_foursquare): ?>

	<?php if (is_array($dashboard_modules) && count($dashboard_modules) > 0): ?>
	<?php foreach ($dashboard_modules as $module): ?>
	<div class="span4">
		<h3>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $module['venue_id']; ?>"><?php echo (strlen($module['check_title']) > 25) ? __(substr($module['check_title'], 0, 25)).'...' : __($module['check_title']); ?></a>
			<small><a href="<?php echo site_url('checks/check') .'/'. $module['check_id']; ?>">Check Log</a>
			</small>
		</h3>
		<table class="table table-condensed">
			<tr>
				<th>Date</th>
				<th>Total Checkins</th>
				<th>Unique Visitors</th>
				<th>Tips Left</th>
				<th>Photos</th>
			</tr>
			<?php foreach (array_keys($module['total_checkins']) as $date): ?>
			<tr>
				<td class="decimal"><?php echo date('n/j', strtotime($date)); ?></td>
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