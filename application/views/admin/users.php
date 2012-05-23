<?php if (isset($accounts) && is_array($accounts) && count($accounts) > 0): ?>
<?php $this->load->view('admin/_user_table', array('accounts'=>$accounts)); ?>
<?php else: ?>
<br />
<p class="alert alert-information">
	No accounts matched your critera.
</p>
<?php endif; ?>

<p>
	<a href="<?php echo site_url('admin'); ?>">&laquo; Back to Site Administration</a>
</p>