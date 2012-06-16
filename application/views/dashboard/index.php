<?php if ($has_foursquare): ?>

	<?php if (count($checks) > 0): ?>
	<div class="hidden-desktop">
		<form class="well form-inline">
		<fieldset>
		<select name="check_list" onchange="window.location=$(this).val()">
			<option value="">(Select a Venue)</option>
			<?php foreach ($checks as $check): ?>
			<?php if ($check->active != '1') continue; ?>
			<option value="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></option>
			<?php endforeach; ?>
		</select>
		</fieldset>
		</form>
	</div>
	<?php endif; ?>

	<?php if (is_array($dashboard_modules) && count($dashboard_modules) > 0): ?>
	<?php foreach ($dashboard_modules as $module): ?>
	<div class="dashboard-module span4">
		<div class="pull-right">
			<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i><span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('foursquare/venue') .'/'. $module['venue_id']; ?>"><i class="icon-map-marker"></i> View Venue</a></li>
					<li><a href="<?php echo site_url('checks/check_edit') .'/'. $module['check_id']; ?>"><i class="icon-pencil"></i> Edit Check</a></li>
					<li><a href="<?php echo site_url('checks/check') .'/'. $module['check_id']; ?>"><i class="icon-book"></i> View Check Log</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo site_url('checks/export') .'/'. $module['check_id'] . '?type=daily'; ?>"><i class="icon-download-alt"></i> Daily Export</a></li>
					<li><a href="<?php echo site_url('checks/export') .'/'. $module['check_id'] . '?type=live'; ?>"><i class="icon-download-alt"></i> Live Export</a></li>
				</ul>
			</div>
		</div>
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
			<?php foreach (array_keys($module['total_checkins']) as $k => $date): ?>
			<?php if ($k+1 > 5): continue; endif; ?>
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
		Your first step is to <a href="<?php echo site_url('foursquare/authenticate'); ?>"><img src="https://playfoursquare.s3.amazonaws.com/press/logo/connect-white.png" alt="connect to foursquare"></a>
	</p>
</div>

<?php endif; ?>