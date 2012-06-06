<?php if ($prompt_update): ?>
<p class="alert alert-danger">
	<strong><i class="icon-exclamation-sign"></i> Your database is out of date!</strong> Run the <a href="<?php echo site_url('admin/system_upgrade'); ?>">upgrade script</a>.
</p>
<?php endif; ?>

<h3>Active Accounts <small><a href="<?php echo site_url('admin/users/active'); ?>">View All</a></small></h3>

<?php if (isset($active_accounts) && is_array($active_accounts) && count($active_accounts) > 0): ?>
<?php $this->load->view('admin/_user_table', array('accounts'=>$active_accounts)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No accounts are currently active. This usually indicates a database error.
</p>
<?php endif; ?>

<h3>Inactive Accounts <small><a href="<?php echo site_url('admin/users/inactive'); ?>">View All</a></small></h3>

<?php if (isset($inactive_accounts) && is_array($inactive_accounts) && count($inactive_accounts) > 0): ?>
<?php $this->load->view('admin/_user_table', array('accounts'=>$inactive_accounts)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No accounts are currently inactive.
</p>
<?php endif; ?>

<div class="pull-right">
		<a href="<?php echo site_url('admin/beta_key_new'); ?>" class="btn btn-small"><i class="icon-plus"></i> New Beta Key</a>
</div>

<h3>Beta Keys <small><a href="<?php echo site_url('admin/beta_keys'); ?>">View All</a></small></h3>

<?php if (isset($beta_keys) && is_array($beta_keys) && count($beta_keys) > 0): ?>
<?php $this->load->view('admin/_beta_keys_table', array('beta_keys'=>$beta_keys)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No beta keys found. It probably means that none have been created yet.
</p>
<?php endif; ?>

<h3>Most Recent Checks</h3>

<?php if (isset($last_checks) && is_array($last_checks) && count($last_checks) > 0): ?>
<?php $this->load->view('admin/_checks_table', array('checks'=>$last_checks)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No checks have been run. This usually indicates a database error.
</p>
<?php endif; ?>