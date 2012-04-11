<ul>
	<li>Display Name: <?php echo __($user->display_name);?></li>
	<li>First Name: <?php echo __($user->first_name);?></li>
	<li>Last Name: <?php echo __($user->last_name);?></li>
	<li>E-mail: <?php echo auto_link($user->email);?></li>
	<li>Package: <?php echo __($user->package_id);?></li>
	<li>Joined: <?php echo date('F j, Y, g:i a', strtotime($user->insert_ts)); ?></li>
	<li>Status: <?php echo ($user->status == 1) ? 'Active' : 'Inactive';?></li>
	<li>Level: <?php echo __($user->level);?></li>
</ul>

<form method="post" action="<?php echo site_url('admin/user_change_package'); ?>" class="form form-inline">
	<?php echo form_dropdown('package_id', $packages, $user->package_id); ?>
	<?php echo form_hidden('user_id', $user->id); ?>
	<?php echo form_submit('', 'Change Package', 'class="btn btn-primary"'); ?>
</form>

<p>
	<?php if ($user->status == 1): ?>
	<a href="<?php echo site_url('admin/deactivate_user').'/'.$user->id; ?>" class="btn btn-danger">Deactivate User</a>
	<?php else: ?>
	<a href="<?php echo site_url('admin/activate_user').'/'.$user->id; ?>" class="btn btn-primary">Activate User</a>	
	<?php endif; ?>
	<a href="<?php echo site_url('admin/assume_user').'/'.$user->id; ?>" rel="tooltip" class="btn btn-information" title="Note: This will log you out of your own account and in as this user.">Login As User</a>
</p>

<hr />

<h3>Venue Checks</h3>

<table class="table table-bordered table-rounded table-striped">
<thead>
	<tr>
		<th>Title</th>
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
		<td><?php echo ($check->active == 1) ? 'Active' : 'Inactive'; ?></td>
		<td><?php echo (!empty_date($check->last_live_check_ts)) ? date('F j, Y, g:i a', strtotime($check->last_live_check_ts)) : 'Not yet.'; ?></td>
		<td><?php echo (!empty_date($check->last_daily_check_ts)) ? date('F j, Y, g:i a', strtotime($check->last_daily_check_ts)) : 'Not yet.'; ?></td>
	</tr>
	<?php endforeach; ?>
	<?php endif; ?>
</tbody>
</table>

<hr />

<p>
	<a href="<?php echo site_url('admin'); ?>">&laquo; Back to Site Administration</a>
</p>