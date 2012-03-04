<h3>Venue Search</h3>

<form method="get" class="form-inline">
<fieldset>
	<p>
		<label>Venue Name</label>
		<input type="search" name="q" value="<?php echo __($this->input->get('q')); ?>" />
		<label>Near</label>
		<input type="text" name="near" value="<?php echo __($this->input->get('near')); ?>" class="required" />
		<input type="submit" class="btn" value="Search" />
	</p>

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
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>