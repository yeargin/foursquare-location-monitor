
<?php echo form_open(); ?>
	<fieldset>
	<div class="clearfix">
		<label>Check Name</label>
		<div class="input">
			<?php echo form_input('check_title', $venue->name); ?>
		</div>
	</div>
	<div class="actions">
		<?php echo form_submit('add', 'Add Check', 'class="btn primary"'); ?>
	</div>
	</fieldset>
<?php echo form_close(); ?>	