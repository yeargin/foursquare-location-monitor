<?php echo validation_errors(); ?>

<?php echo form_open('register', array('class' => 'form form-vertical', 'method' => 'get')); ?>
<?php echo form_fieldset(''); ?>

<div class="clearfix">
	<?php echo form_label('Beta Key', 'beta_key'); ?>
	<div class="input">
		<?php echo form_input('beta_key', $beta_key); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('', 'Validate Beta Key', 'class="btn primary"'); ?>
	<a href="<?php echo site_url('/'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>