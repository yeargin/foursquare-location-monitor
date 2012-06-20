<?php if ($overage): ?>
<div class="hero-unit">

	<p class="alert alert-error">
		Your account would be over its allowed quota by <strong><?php echo $overage; ?> monitored location(s)</strong>. In order to downgrade your package, visit the <a href="<?php echo site_url('checks'); ?>">Monitored Locations</a> list and disable monitoring as necessary.</p>
	<hr />
	<p>
		<a href="<?php echo site_url('checks'); ?>" class="btn btn-primary btn-large">Manage Monitored Locations</a>
	</p>
	
</div>
<?php else: ?>
<form method="post" class="form form-horizontal" id="change_form">
	<div class="hero-unit">

		<p>You are changing from the <strong><?php echo $usage['package']->name; ?></strong> package with <strong><?php echo $usage['package']->check_limit; ?> monitored locations</strong> to the <strong><?php echo $package->name; ?></strong> package with <strong><?php echo $package->check_limit; ?> monitored locations</strong>.</p>
		<hr />
		<p>
			<input type="hidden" name="confirm_change" value="true" />
			<input type="submit" value="Confirm Change" class="btn btn-primary btn-large">
		</p>
	
	</div>
</form>
<?php endif; ?>