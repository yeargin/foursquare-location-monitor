<form method="post" action="<?php echo site_url('admin/user_change_package'); ?>" class="form form-stacked" id="change_package">

<div class="row">

	<div class="span3">
		<label>Display Name</label>
		<h3><?php echo __($user->display_name); ?></h3>
	</div>
	
	<div class="span3">
		<label>Email</label>
		<h3><?php echo __($user->email); ?></h3>
	</div>
		
	<div class="span3">
		<label>Change Package</label>
		<?php echo form_dropdown('package_id', $packages, $user->package_id, 'onchange="$(\'form#change_package\').submit()"'); ?>
		<?php echo form_hidden('user_id', $user->id); ?>
		<?php echo form_submit('', 'Change Package', 'class="hide"'); ?>
	</div>

</div>

<br />

<div class="row">

	<div class="span3">
		<label>Joined</label>
		<h3><?php echo date('F j, Y, g:i a', strtotime($user->insert_ts)); ?></h3>
	</div>

	<div class="span3">
		<label>Status</label>
		<div><?php if ($user->status == 1): ?>
		<a href="<?php echo site_url('admin/deactivate_user').'/'.$user->id; ?>" class="btn btn-danger"><i class="icon-ban-circle icon-white"></i> Deactivate User</a>
		<?php else: ?>
		<a href="<?php echo site_url('admin/activate_user').'/'.$user->id; ?>" class="btn btn-primary"><i class="icon-ok-circle icon-white"></i> Activate User</a>	
		<?php endif; ?></div>
	</div>

	<div class="span3">
		<label>Level</label>
		<h3><?php echo ucwords($user->level); ?></h3>
	</div>

</div>

<br />

<div class="row">

	<div class="span3">
		<label>Account Management</label>
		<p>
			<a href="<?php echo site_url('admin/assume_user').'/'.$user->id; ?>" rel="tooltip" class="btn btn-information" title="Note: This will log you out of your own account and in as this user."><i class="icon-user"></i> Login As User</a>
		</p>
	</div>

	<div class="span3">

	</div>

</div>

</form>

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