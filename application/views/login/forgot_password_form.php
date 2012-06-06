<?php if ($this->session->flashdata('message')): ?>
	<p class="alert"><?php echo $this->session->flashdata('message'); ?></p>
<?php endif; ?>

<?php echo form_open('login/forgot_password'); ?>

	<?php echo form_fieldset(); ?>

		<legend>Password Reset</legend>

		<div class="clearfix">
			<?php echo form_label('Username', 'username'); ?>
			<div class="input">
				<?php echo form_input('username', null,'placeholder="username, e.g. johndoe"'); ?>
			</div>
		</div>
		
		<div class="actions">
			<?php echo form_submit('password_reset', 'Send Password Reset', 'class="btn btn-primary"'); ?>
		</div>

	<?php echo form_fieldset_close(); ?>

<?php echo form_close(); ?>