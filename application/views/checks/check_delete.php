<form method="post">
<fieldset>
<legend>Are you sure you want to delete this venue check?</legend>
<div class="control-group">
	<ul class="unstyled">
		<li><strong>Title:</strong> <?php echo __($check->check_title); ?></li>
		<li><strong>Added:</strong> <?php echo date('F j, Y', strtotime($check->insert_ts)); ?></li>
	</ul>
</div>
<div class="form-actions">
<p>
	<input type="submit" name="confirm" class="btn btn-danger btn-large" value="Delete Check" />
	<a href="<?php echo site_url('checks/check_edit').'/'.$check->id; ?>">Cancel</a>
	(This removes all associated log data forever. Consider <a href="<?php echo site_url('checks/check_edit').'/'.$check->id; ?>">deactivating</a> instead.)
</p>
</div>
</fieldset>
</form>