<table class="table">
	<thead>
		<tr>
			<th>Key</th>
			<th>Name</th>
			<th>E-mail</th>
			<th>Status</th>
			<th>Created</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($beta_keys as $key): ?>
		<tr>
			<td><tt><?php echo $key->beta_key; ?></tt></td>
			<td><?php echo __($key->name); ?></td>
			<td><?php echo auto_link($key->email); ?></td>
			<td><?php echo ($key->status == 1) ? 'Not Claimed' : 'Claimed' ?></td>
			<td><?php echo date('F j, Y, g:i a', strtotime($key->insert_ts)); ?></td>
			<td class="action-cell"><a href="<?php echo site_url('admin/beta_key_revoke/'.$key->beta_key); ?>" class="btn confirm"><i class="icon-trash"></i></a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>