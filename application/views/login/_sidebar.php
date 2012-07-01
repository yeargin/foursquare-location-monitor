<?php if ($beta_only): ?>
<h3>Have a beta key?</h3>
<p>
	<?php echo $this->config->item('application_name'); ?> is currently in beta. Only visitors with a beta key will be able to create accounts.
</p>
<p>
	<a href="<?php echo site_url('register/beta'); ?>" class="btn">Register</a>
</p>
<?php else: ?>
<h3>New user?</h3>
<p>
	Register to use <?php echo $this->config->item('application_name'); ?> on the next page.
</p>
<p>
	<a href="<?php echo site_url('register'); ?>" class="btn">Register</a>
</p>
<?php endif; ?>