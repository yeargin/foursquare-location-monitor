<?php echo validation_errors(); ?>

<?php echo form_open('admin/beta_key_new', 'class="form form-horizontal"'); ?>
<?php echo form_fieldset(''); ?>

<div class="control-group">
	<?php echo form_label('Full Name', 'name', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('name', $name); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('E-mail', 'email', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('email', $email); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('register', 'Create Beta Key', 'class="btn btn-primary"'); ?>
	<a href="<?php echo site_url('/admin/beta_keys'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>

<p>
	<a href="<?php echo site_url('/admin/beta_keys'); ?>">&laquo; Back to Beta Keys</a>
</p>
