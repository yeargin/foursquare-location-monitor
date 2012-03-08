<table class="table table-bordered table-rounded table-striped">
<thead>
	<tr>
		<th>Title</th>
		<th></th>
		<th>Status</th>
		<th>Last "Live" Check</th>
		<th>Last Daily Check</th>
	</tr>
</thead>
<tbody>
	<?php if (is_array($checks) && count($checks) > 0): ?>
	<?php foreach ($checks as $check): ?>
	<tr>
		<td><a href="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></a></td>
		<td><a href="<?php echo site_url('checks/check') .'/'. ($check->id); ?>">Log</a> | <a href="<?php echo site_url('checks/check_edit') .'/'. ($check->id); ?>">Edit</a></td>
		<td><?php echo ($check->active == 1) ? 'Active' : 'Inactive'; ?></td>
		<td><?php echo date('F j, Y, g:i a', strtotime($check->last_live_check_ts)); ?></td>
		<td><?php echo date('F j, Y, g:i a', strtotime($check->last_daily_check_ts)); ?></td>
	</tr>
	<?php endforeach; ?>
	<?php endif; ?>
</tbody>
</table>

<hr />

<p>
	<a href="<?php echo site_url('checks'); ?>">&laquo; Back to Venue Checks</a>
</p>