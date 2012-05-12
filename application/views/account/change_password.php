<?php echo validation_errors(); ?>

<?php echo form_open('profile/change_password', 'class="form form-vertical"'); ?>
<?php echo form_fieldset(''); ?>

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

<div class="form-actions">
	<?php echo form_submit('update', 'Change Password', 'class="btn primary"'); ?>
	<a href="<?php echo site_url('profile'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>