<?php echo validation_errors(); ?>

<?php echo form_open('', 'class="form form-horizontal"'); ?>
<?php echo form_fieldset(''); ?>

<div class="control-group">
	<?php echo form_label('Package Name', 'name', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('name', $package->name); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('Description', 'description', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_textarea('description', $package->description); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('Monitored Locations', 'check_limit', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('check_limit', $package->check_limit); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('register', ($package->id >0) ? 'Update Package' : 'Create Package', 'class="btn btn-primary"'); ?>
	<?php if ($package->id > 0): ?>
	<a href="<?php echo site_url('admin/package_delete/'.$package->id); ?>" class="btn btn-danger"><i class="icon-trash icon-white"></i> Delete Package</a>
	<?php endif; ?>
	<a href="<?php echo site_url('/admin/packages'); ?>">Cancel</a>
</div>

<?php echo form_hidden('id', $package->id); ?>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>

<p>
	<a href="<?php echo site_url('/admin/packages'); ?>">&laquo; Back to Packages</a>
</p>
