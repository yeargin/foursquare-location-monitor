<?php if ($this->input->get('login_failed')): ?>
<p class="alert alert-error">Your login attempt failed.</p>
<?php endif; ?>

<?php echo form_open('login/post', 'class="form form-horizontal"'); ?>

	<?php echo form_fieldset(); ?>

		<div class="control-group">
			<?php echo form_label('Username', 'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo form_input('username'); ?>
			</div>
		</div>

		<div class="control-group">
			<?php echo form_label('Password', 'password', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo form_password('password'); ?>
			</div>
		</div>

		<?php echo form_hidden('redirect', $redirect); ?>

		<div class="form-actions">
			<?php echo form_submit('login', 'Login', 'class="btn btn-primary"'); ?>
			<a href="<?php echo site_url('login/forgot_password'); ?>">Forgot your password?</a>
		</div>

	<?php echo form_fieldset_close(); ?>

<?php echo form_close(); ?>