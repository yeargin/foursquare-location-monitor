<?php /*
<h3>Your Plan</h3>
<p>
	You are currently using <strong><?php echo $usage['checks']->active_checks; ?></strong> of the <strong><?php echo $usage['package']->check_limit; ?></strong> monitored locations available in the <strong><?php echo $usage['package']->name; ?></strong> package.
</p>
	
<p>
	<a href="<?php echo site_url('packages'); ?>" class="btn">Change Package</a>
</p>
*/ ?>

<h3>Troubleshooting</h3>
<p>
	If you your location monitoring checks have stopped working, you may need to re-authenticate with foursquare.
<p>
	<a href="<?php echo site_url('foursquare/reset'); ?>" class="btn btn-danger">Reset Authentication</a>
</p>