<h3>Your profile</h3>

<?php echo form_open('profile/post'); ?>
<?php echo form_fieldset('Edit'); ?>

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

<div class="actions">
	<?php echo form_submit('update', 'Update Record', 'class="btn primary"'); ?>
</div>

<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>