<h3>Change Password</h3>

<?php echo form_open('profile/change_password'); ?>
<?php echo form_fieldset('Edit'); ?>

<div class="clearfix">
	<?php echo form_label('Password', 'password'); ?>
	<div class="input">
		<?php echo form_password('password'); ?>
	</div>
</div>

<div class="clearfix">
	<?php echo form_label('Confirm', 'password_confirm'); ?>
	<div class="input">
		<?php echo form_password('password_confirm'); ?>
	</div>
</div>

<div class="actions">
	<?php echo form_submit('update', 'Change Password', 'class="btn primary"'); ?>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>