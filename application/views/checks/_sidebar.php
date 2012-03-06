<h3>Monitored Venues</h3>
<?php if (is_array($checks) && count($checks) > 0): ?>
<ul>
<?php foreach ($checks as $check): ?>
	<li><a href="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></a></li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<p>
	<em>No venues monitored. <a href="<?php echo site_url('foursquare/search'); ?>">Add via Search</a>.
</p>
<?php endif; ?>