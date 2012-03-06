
<?php echo form_open(); ?>
	<fieldset>
	<div class="clearfix">
		<label>Check Name</label>
		<div class="input">
			<?php echo form_input('check_title', $check->check_title); ?>
		</div>
	</div>
	<?php if (isset($check->id)): ?>
	<div>
		<label>Status</label>
		<div class="input">
			<label class="checkbox">
				<?php echo form_checkbox('active', '1', (bool) $check->active); ?>
				Active
			</label>
		</div>
	</div>
	<?php endif; ?>
	<div class="actions">
		<?php if (isset($check->id)): ?>
		<?php echo form_submit('add', 'Update Check', 'class="btn primary"'); ?>
		<a href="<?php echo site_url('checks/check' .'/'. $check->id); ?>" class="btn cancel">Cancel</a>
		<?php else: ?>
		<?php echo form_submit('add', 'Add Check', 'class="btn primary"'); ?>
		<a href="" class="btn cancel">Cancel</a>
		<?php endif; ?>
	</div>
	</fieldset>
<?php echo form_close(); ?>	