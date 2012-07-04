<?php if ($this->session->flashdata('message')): ?>
	<p class="alert"><?php echo $this->session->flashdata('message'); ?></p>
<?php endif; ?>

<?php echo form_open('login/forgot_password', 'class="form form-horizontal"'); ?>

	<?php echo form_fieldset(); ?>
	
		<div class="control-group">
			<?php echo form_label('Username', 'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo form_input('username', null,'placeholder="username, e.g. johndoe"'); ?>
			</div>
		</div>
		
		<div class="form-actions">
			<?php echo form_submit('password_reset', 'Send Password Reset', 'class="btn btn-primary"'); ?>
		</div>

	<?php echo form_fieldset_close(); ?>

<?php echo form_close(); ?>