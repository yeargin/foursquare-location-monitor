<?php echo validation_errors(); ?>

<?php echo form_open('register', 'class="form form-horizontal"'); ?>
<?php echo form_fieldset(''); ?>

<div class="control-group">
	<?php echo form_label('Username', 'username', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('username', $profile->username); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('Password', 'password', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_password('password'); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('First Name', 'first_name', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('first_name', $profile->first_name); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('Last Name', 'last_name', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('last_name', $profile->last_name); ?>
	</div>
</div>

<div class="control-group">
	<?php echo form_label('E-mail', 'email', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo form_input('email', $profile->email); ?>
	</div>
</div>

<div class="form-actions">
	<?php echo form_hidden('beta_key', $beta_key); ?>
	<?php echo form_submit('register', 'Register', 'class="btn btn-primary"'); ?>
	<a href="<?php echo site_url('/'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>