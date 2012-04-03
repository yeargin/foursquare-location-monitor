<?php echo validation_errors(); ?>

<?php echo form_open('admin/beta_key_new', 'class="form form-vertical"'); ?>
<?php echo form_fieldset(''); ?>

<div class="clearfix">
	<?php echo form_label('Full Name', 'name'); ?>
	<div class="input">
		<?php echo form_input('name', $name); ?>
	</div>
</div>

<div class="clearfix">
	<?php echo form_label('E-mail', 'email'); ?>
	<div class="input">
		<?php echo form_input('email', $email); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('register', 'Create Beta Key', 'class="btn primary"'); ?>
	<a href="<?php echo site_url('/admin'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>