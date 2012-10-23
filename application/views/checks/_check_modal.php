<?php echo form_open(null, array('id'=>'checkModal')); ?>
	<fieldset>
	<div class="clearfix">
		<label>Location Name</label>
		<div class="input">
			<?php echo form_input('check_title', $check->check_title); ?>
		</div>
		<?php if (isset($check->id)): ?>
		<div class="input">
			<button class="btn" data-toggle="button" id="toggle_status" value="<?php echo ($check->active == 1) ? '1' : '0'; ?>"><?php echo ($check->active == 1) ? 'Deactivate Check' : 'Activate Check'; ?></button>
		</div>
		<?php endif; ?>
	</div>
	</fieldset>
	<?php echo form_hidden('check_id', $check->id); ?>
	<?php echo form_hidden('venue_id', $check->venue_id); ?>
<?php echo form_close(); ?>