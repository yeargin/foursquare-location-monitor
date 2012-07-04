<div class="pull-right">
	<p>
		<a href="<?php echo site_url('admin/package/new'); ?>" class="btn btn-small"><i class="icon-plus"></i> New Package</a>
	</p>
</div>

<table class="table">
	<thead>
		<tr>
			<th>Package</th>
			<th>Description</th>
			<th>Check Count</th>
			<th>Users</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($packages as $package): ?>
		<tr>
			<td><?php echo __($package->name); ?></td>
			<td><?php echo __($package->description); ?></td>
			<td><?php echo number_format($package->check_limit); ?></td>
			<td><?php echo number_format($package->user_count); ?></td>
			<td class="action-cell">
				<div class="btn-group">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('admin/package/'.$package->id); ?>" class=""><i class="icon-pencil"></i> Edit</a></li>
						<li><a href="<?php echo site_url('admin/package_delete/'.$package->id); ?>"><i class="icon-trash"></i> Delete</a></li>
					</ul>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<p>
	<a href="<?php echo site_url('admin'); ?>">&laquo; Back to Site Administration</a>
</p>