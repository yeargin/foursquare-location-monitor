<form id="search" method="get" class="well form form-inline">
<fieldset>
	<label class="hide">Venue Name</label>
	<input type="search" name="q" placeholder="Tom's Restaurant" value="<?php echo __($this->input->get('q')); ?>" />

	<label class="hide">Near</label>
	<input type="text" name="near" placeholder="Anytown, USA" value="<?php echo __($this->input->get('near')); ?>" class="required" />

	<a href="javascript:$('#search').submit();" class="btn btn-primary"><i class="icon-search icon-white"></i> Search</a>
	<input type="submit" class="hide" />
</fieldset>
</form>


<?php if (isset($search_results) && count($search_results) > 0): ?>
<h3>Search Results</h3>
<table class="table">
<tbody>
	<?php foreach ($search_results as $row): ?>
	<tr>
		<td><a href="<?php echo site_url('foursquare/venue') .'/'. $row->id; ?>"><?php echo $row->name; ?></a></td>
		<td>
			<address>
				<?php echo isset($row->location->address) ? $row->location->address . '<br />' : ''; ?> 
				<?php echo isset($row->location->city) ? $row->location->city . ', ': ''; ?> 	<?php echo isset($row->location->state) ? $row->location->state : ''; ?>
				<?php echo isset($row->location->postalCode) ? $row->location->postalCode: ''; ?> <br />
				<?php echo isset($row->location->country) ? $row->location->country : ''; ?>
			</address>
		</td>
		<td class="action-cell">
			<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-wrench"></i><span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('foursquare/venue') .'/'. $row->id; ?>"><i class="icon-map-marker"></i> View Location</a></li>
					<?php if (isset($checks_by_venue[$row->id])): ?>
					<li><a href="<?php echo site_url('checks/check') .'/'. $checks_by_venue[$row->id]->id; ?>"><i class="icon-book"></i> Monitoring Log</a></li>
					<?php else: ?>
					<li><a href="<?php echo site_url('checks/check_add') .'/'. $row->id; ?>"><i class="icon-plus-sign"></i> Add Monitoring</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

<p>
	<a href="<?php echo site_url('dashboard'); ?>">&laquo; Back to Dashboard</a>
</p>