<div class="hero-unit">
	<p>You are currently using <strong><?php echo $usage['checks']->active_checks; ?></strong> of the <strong><?php echo $usage['package']->check_limit; ?></strong> monitored locations available in the <strong><?php echo $usage['package']->name; ?></strong> package.</p>
</div>

<hr />

<div class="row">
<?php foreach ($packages as $package): ?>
	<div class="span3">
		<div class="well">
			<h2><?php echo $package->name; ?></h2>
			<p><span class="badge badge-info"><?php echo number_format($package->check_limit); ?></span> Monitored Locations</p>
			<div><?php echo $package->description; ?></div>
			<hr />
			<p>
			<?php if ($profile->package_id == $package->id): ?>
				<a class="btn disabled"><i class="icon-check"></i> Current Package</a>
			<?php else: ?>
				<a class="btn btn-primary" href="<?php echo site_url('packages/change'); ?>?package_id=<?php echo $package->id; ?>">Switch to <?php echo $package->name; ?></a>
			<?php endif; ?>
			</p>
		</div>
	</div>
<?php endforeach; ?>
</div>