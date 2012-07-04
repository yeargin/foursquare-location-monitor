<form method="post" class="form form-horizontal">
	<fieldset>
	<legend>Are you sure you want to delete this package?</legend>

	<div class="control-group">
		<label class="control-label" for="migrate_to">Select Replacement Package</label>
		<div class="controls">
			<select name="migrate_to" id="migrate_to">
				<?php foreach($packages as $option): ?>
					<?php if ($package->id == $option->id): continue; endif; ?>
					<option value="<?php echo $option->id; ?>"><?php echo __($option->name); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="help-block">Users that have the deleted package selected will be moved over to the new selection.</p>
		</div>
	</div>

	<div class="form-actions">
			<input type="submit" name="confirm" class="btn btn-danger btn-large" value="Delete Package" />
	</div>
	</fieldset>
</form>