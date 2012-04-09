<h3>Active Accounts</h3>

<?php if (isset($active_accounts) && is_array($active_accounts) && count($active_accounts) > 0): ?>
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
	<?php foreach ($active_accounts as $user): ?>
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
<?php else: ?>
<p class="alert alert-information">
	No accounts are currently active. This usually indicates a database error.
</p>
<?php endif; ?>

<h3>Inactive Accounts</h3>

<?php if (isset($inactive_accounts) && is_array($inactive_accounts) && count($inactive_accounts) > 0): ?>
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
	<?php foreach ($inactive_accounts as $user): ?>
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
<?php else: ?>
<p class="alert alert-information">
	No accounts are currently inactive.
</p>
<?php endif; ?>
<h3>Most Recent Checks</h3>

<?php if (isset($last_checks) && is_array($last_checks) && count($last_checks) > 0): ?>
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
	<?php foreach ($last_checks as $check): ?>
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
<?php else: ?>
<p class="alert alert-information">
	No checks have been run. This usually indicates a database error.
</p>
<?php endif; ?>


<p class="pull-right">
	<a href="<?php echo site_url('admin/beta_key_new'); ?>" class="btn btn-small"><i class="icon-plus"></i> New Beta Key</a>
</p>

<h3>Beta Keys</h3>

<?php if (isset($beta_keys) && is_array($beta_keys) && count($beta_keys) > 0): ?>
<table class="table">
	<thead>
		<tr>
			<th>Key</th>
			<th>Name</th>
			<th>E-mail</th>
			<th>Status</th>
			<th>Created</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($beta_keys as $key): ?>
		<tr>
			<td><tt><?php echo $key->beta_key; ?></tt></td>
			<td><?php echo __($key->name); ?></td>
			<td><?php echo auto_link($key->email); ?></td>
			<td><?php echo ($key->status == 1) ? 'Not Claimed' : 'Claimed' ?></td>
			<td><?php echo date('F j, Y, g:i a', strtotime($key->insert_ts)); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p class="alert alert-information">
	No beta keys found. It probably means that none have been created yet.
</p>
<?php endif; ?>