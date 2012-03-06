<h3>Venue Search</h3>

<form method="get" class="form form-stacked">
<fieldset>
	<div>
		<label>Venue Name</label>
		<div class="input"><input type="search" name="q" placeholder="Tom's Reastaurant" value="<?php echo __($this->input->get('q')); ?>" /></div>
	</div>
	<div>
		<label>Near</label>
		<div class="input"><input type="text" name="near" placeholder="Anytown, USA" value="<?php echo __($this->input->get('near')); ?>" class="required" /></div>
	</div>
	<div class="actions">
		<input type="submit" class="btn" value="Search" />
	</div>

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
		<td>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $row->id; ?>" class="btn secondary">View Venue</a>
			<?php if (isset($checks_by_venue[$row->id])): ?>
			<a href="<?php echo site_url('checks/check') .'/'. $checks_by_venue[$row->id]->id; ?>" class="btn secondary disabled">Check Log</a>
			<?php else: ?>
			<a href="<?php echo site_url('checks/check_add') .'/'. $row->id; ?>" class="btn secondary">Add Check</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

<p>
	<a href="<?php echo site_url('dashboard'); ?>">&laquo; Back to Dashboard</a>
</p>