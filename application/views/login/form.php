<?php if ($this->input->get('login_failed')): ?>
<p class="alert alert-error">Your login attempt failed.</p>
<?php endif; ?>

<?php echo form_open('login/post'); ?>

	<?php echo form_fieldset(); ?>

		<div class="clearfix">
			<?php echo form_label('Username', 'username'); ?>
			<div class="input">
				<?php echo form_input('username'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo form_label('Password', 'password'); ?>
			<div class="input">
				<?php echo form_password('password'); ?>
			</div>
		</div>

		<?php echo form_hidden('redirect', $redirect); ?>

		<div class="form-actions">
			<?php echo form_submit('login', 'Login', 'class="btn primary"'); ?>
			&nbsp; &nbsp; <a href="<?php echo site_url('login/forgot_password'); ?>">Forgot your password?</a>
		</div>

	<?php echo form_fieldset_close(); ?>

<?php echo form_close(); ?>