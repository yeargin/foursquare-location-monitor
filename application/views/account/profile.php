<?php echo validation_errors(); ?>

<?php echo form_open('profile', 'class="form form-vertical"'); ?>
<?php echo form_fieldset(''); ?>

<div class="clearfix">
	<?php echo form_label('First Name', 'first_name'); ?>
	<div class="input">
		<?php echo form_input('first_name', $profile->first_name); ?>
	</div>
</div>

<div class="clearfix">
	<?php echo form_label('Last Name', 'last_name'); ?>
	<div class="input">
		<?php echo form_input('last_name', $profile->last_name); ?>
	</div>
</div>

<div class="clearfix">
	<?php echo form_label('E-mail', 'email'); ?>
	<div class="input">
		<?php echo form_input('email', $profile->email); ?>
	</div>
</div>

<div class="clearfix">
	<label>Password</label>
	<div class="input">
		<h3>&bull; &bull; &bull; &bull; &bull; &bull; <small><a href="<?php echo site_url('profile/change_password'); ?>">(Change)</a></small></h3>
	</div>
</div>

<div class="form-actions">
	<?php echo form_submit('update', 'Update Record', 'class="btn primary"'); ?>
	<a href="<?php echo site_url('dashboard'); ?>">Cancel</a>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>