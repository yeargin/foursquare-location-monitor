
<?php echo form_open(); ?>
	<fieldset>
	<div class="clearfix">
		<label>Check Name</label>
		<div class="input">
			<?php echo form_input('check_title', $check->check_title); ?>
		</div>
		<?php if (isset($check->id)): ?>
		<div class="input">
			<button class="btn" data-toggle="button" id="toggle_status" value="<?php echo ($check->active == 1) ? '1' : '0'; ?>"><?php echo ($check->active == 1) ? 'Deactivate Check' : 'Activate Check'; ?></button>
		</div>
		<?php endif; ?>
	</div>
	<div class="form-actions">
		<?php if (isset($check->id)): ?>
		<?php echo form_submit('add', 'Update Check', 'class="btn btn-primary"'); ?>
		<a href="<?php echo site_url('checks/check_delete').'/'.$check->id; ?>" class="btn btn-danger">Delete Check</a>
		<a href="<?php echo site_url('checks/check' .'/'. $check->id); ?>">Cancel</a>
		<?php else: ?>
		<?php echo form_submit('add', 'Add Check', 'class="btn btn-primary"'); ?>
		<a href="" class="btn btn-cancel">Cancel</a>
		<?php endif; ?>
	</div>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#toggle_status').click(function(event) {
		event.stopPropagation();
		var status = $(this).val();
		if (status == 1) {
			$.get('<?php echo site_url('checks/ajax_check_status'); ?>/<?php echo $check->id; ?>?status=0');
			$(this).val('0');
			$(this).html('Activate Check');
		} else {
			$.get('<?php echo site_url('checks/ajax_check_status'); ?>/<?php echo $check->id; ?>?status=1');
			$(this).val('1');
			$(this).html('Deactivate Check');
		}
		return false;
	});
});
</script>