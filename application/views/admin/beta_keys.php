<?php if (isset($beta_keys) && is_array($beta_keys) && count($beta_keys) > 0): ?>
<?php $this->load->view('admin/_beta_keys_table', array('beta_keys'=>$beta_keys)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No beta keys found. It probably means that none have been created yet.
</p>
<?php endif; ?>

<p>
	<a href="<?php echo site_url('admin'); ?>">&laquo; Back to Site Administration</a>
</p>