<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Title / Venue</th>
			<th>User</th>
			<th>Last Live Check</th>
			<th>Last Daily Check</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($checks as $check): ?>
		<tr>
			<td><?php echo $check->check_id; ?></td>
			<td><a href="<?php echo site_url('foursquare/venue') .'/'. $check->venue_id; ?>"><?php echo $check->check_title; ?></a></td>
			<td><a href="<?php echo site_url('admin/user') .'/'. $check->user_id; ?>"><?php echo $check->username; ?></a></td>
			<td><?php echo date('F j, Y, g:i a', strtotime($check->last_live_check_ts)); ?></td>
			<td><?php echo date('F j, Y, g:i a', strtotime($check->last_daily_check_ts)); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>