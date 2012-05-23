<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Login</th>
			<th>Name</th>
			<th>Email</th>
			<th>Level</th>
			<th>Package</th>
			<th>Joined</th>
			<th>Checks</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($accounts as $user): ?>
		<tr>
			<td><?php echo $user->id; ?></td>
			<td><a href="<?php echo site_url('admin/user') .'/'. $user->id; ?>"><?php echo __($user->username); ?></a></td>
			<td><?php echo __($user->display_name); ?></td>
			<td><?php echo __($user->email); ?><t/d>
			<td><?php echo ucwords($user->level); ?></td>
			<td><?php echo ($user->package); ?></td>
			<td><?php echo date('F j, Y, g:i a', strtotime($user->insert_ts)); ?></td>
			<td><?php echo (int) $user->check_count; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>