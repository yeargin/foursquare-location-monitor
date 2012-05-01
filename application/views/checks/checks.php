<?php if (is_array($tag_list) && count($tag_list) > 0): ?>
<form class="form form-inline">
	<?php echo form_dropdown('tag', array(''=>'All Tags')+$tag_list, $this->input->get('tag')); ?>
	<?php echo form_submit('', 'Filter', 'class="btn"'); ?>
</form>
<?php endif; ?>

<div class="alert alert-block alert-information">
	<p><i class="icon-info-sign"></i> You are using <strong><?php echo number_format($counts['checks']->active_checks); ?></strong> checks of the <strong><?php echo number_format($counts['package']->check_limit); ?></strong> avaialble in the '<?php echo __($counts['package']->name); ?>' package.</p>
</div>

<table class="table table-bordered table-rounded table-striped">
<thead>
	<tr>
		<th>Title</th>
		<th>Status</th>
		<th>Last "Live" Check</th>
		<th>Last Daily Check</th>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php if (is_array($checks) && count($checks) > 0): ?>
	<?php foreach ($checks as $check): ?>
	<tr id="check_<?php echo __($check->id); ?>">
		<td><a href="<?php echo site_url('foursquare/venue') .'/'. ($check->venue_id); ?>"><?php echo ($check->check_title); ?></a> <span class="taglist" data-check_id="<?php echo __($check->id); ?>" style="display:block;"><?php echo (isset($tags[$check->id])) ? listTags($tags[$check->id]) : ''; ?></span></td>
		<td><?php echo ($check->active == 1) ? 'Active' : 'Inactive'; ?></td>
		<td><?php echo (!empty_date($check->last_live_check_ts)) ? date('F j, Y, g:i a', strtotime($check->last_live_check_ts)) : 'Not yet.'; ?></td>
		<td><?php echo (!empty_date($check->last_daily_check_ts)) ? date('F j, Y, g:i a', strtotime($check->last_daily_check_ts)) : 'Not yet.'; ?></td>
		<td class="action-cell">
			<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Action<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('checks/check') .'/'. ($check->id); ?>"><i class="icon-book"></i> Check Log</a></li>
					<li><a href="<?php echo site_url('checks/check_edit') .'/'. ($check->id); ?>"><i class="icon-pencil"></i> Edit Check</a></li>
					<li><a data-toggle="modal" href="#tagModal" onclick="openTagModal(this)" data-check_title="<?php echo __($check->check_title); ?>" data-check_id="<?php echo __($check->id); ?>" data-tags="<?php echo (isset($tags[$check->id])) ? join(', ', $tags[$check->id]) : ''; ?>"><i class="icon-tag"></i> Edit Tags</a>
					<?php if ($check->active == 1): ?>
					<li><a href="<?php echo site_url('checks/check_deactivate') .'/'. ($check->id); ?>"><i class="icon-pencil"></i> Deactivate Check</a></li>
					<?php else: ?>
					<li><a href="<?php echo site_url('checks/check_activate') .'/'. ($check->id); ?>"><i class="icon-pencil"></i> Activate Check</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
	<?php endif; ?>
</tbody>
</table>

<hr />

<p>
	<a href="<?php echo site_url('checks'); ?>">&laquo; Back to Venue Checks</a>
</p>