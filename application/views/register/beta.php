<?php echo validation_errors(); ?>

<?php echo form_open('register', array('class' => 'form form-horizontal', 'method' => 'get')); ?>
<?php echo form_fieldset(''); ?>

<div class="control-group">
	<?php echo form_label('Beta Key', 'beta_key', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('beta_key', $beta_key); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('', 'Validate Beta Key', 'class="btn btn-primary"'); ?>
	<a href="<?php echo site_url('/'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>