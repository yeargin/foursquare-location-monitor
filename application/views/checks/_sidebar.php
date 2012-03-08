<h3>Monitored Venues</h3>
<?php if (is_array($checks) && count($checks) > 0): ?>

<?php if (count($checks) > 10): ?>
<form>
<fieldset>
<select name="check_list" onchange="window.location=$(this).val()">
	<option value="">(Select a Venue)</option>
	<?php foreach ($checks as $check): ?>
	<option value="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></option>
	<?php endforeach; ?>
</select>
</fieldset>
</form>

<?php else: ?>

<ul>
<?php foreach ($checks as $check): ?>
	<li><a href="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></a></li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php else: ?>
<p>
	<em>No venues monitored. <a href="<?php echo site_url('foursquare/search'); ?>">Add via Search</a>.
</p>
<?php endif; ?>