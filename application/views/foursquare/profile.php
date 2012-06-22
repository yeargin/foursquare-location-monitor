<div class="hero-unit">

	<div class="pull-right">
		<img src="<?php echo ($member->photo); ?>" alt="Profile Picture" />
	</div>
	<h2><?php echo ($member->firstName); ?> <?php echo isset($member->lastName) ? $member->lastName : ''; ?></h2>
	<p><?php echo ($member->homeCity); ?></p>
	
	<ul>
		<?php foreach ($member->contact as $type => $value): ?>
		<?php
			switch($type):
				case 'facebook':
					$value = 'http://facebook.com/'.$value;
					break;
				case 'twitter':
					$value = 'http://twitter.com/'.$value;
					break;
			endswitch;
		?>
		<?php printf('<li><strong>%s:</strong> %s', ucwords($type), auto_link($value, 'both', true)); ?>
		<?php endforeach; ?>
		
	</ul>
	<p>
		<a href="http://foursquare.com/user/<?php echo ($member->id); ?>" class="btn secondary"><i class="icon-user"></i> View on Foursquare</a>
	</p>

</div>

<?php if (isset($member->checkins->items) && count($member->checkins->items) > 0): ?>

<h3>Last Checkin</h3>

<table class="table">
<tbody>
	<?php foreach ($member->checkins->items as $row): ?>
	<tr>
		<td>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $row->venue->id; ?>"><?php echo $row->venue->name; ?></a><br />
			<?php echo time_ago(date('c', $row->createdAt)); ?>
		</td>
		<td>
			<address>
				<?php echo isset($row->venue->location->address) ? $row->venue->location->address . '<br />' : ''; ?> 
				<?php echo isset($row->venue->location->city) ? $row->venue->location->city . ', ': ''; ?> 	<?php echo isset($row->venue->location->state) ? $row->venue->location->state : ''; ?>
				<?php echo isset($row->venue->location->postalCode) ? $row->venue->location->postalCode: ''; ?> <br />
				<?php echo isset($row->venue->location->country) ? $row->venue->location->country : ''; ?>
			</address>
		</td>
		<td>
			<a href="<?php echo site_url('foursquare/venue') .'/'. $row->venue->id; ?>" class="btn secondary">View Venue</a>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

<?php if (isset($member->friends->count) && $member->friends->count > 0): ?>

<h3>Friends <small>(<?php echo (int) $member->friends->count; ?>)</small></h3>

<table class="table">
<tbody>
<?php foreach ($member->friends->groups as $group): ?>
	<tr>
		<th colspan="4">
			<h3><?php echo $group->name; ?> <small>(<?php echo $group->count; ?>)</small></h4>
		</th>
	</tr>
<?php foreach ($group->items as $friend): ?>
	<tr>
		<td><img src="<?php echo ($friend->photo); ?>" alt="Profile Picture" /></td>
		<td>
			<a href="<?php echo site_url('foursquare/profile') .'/'. $friend->id; ?>"><?php echo ($friend->firstName); ?> <?php echo isset($friend->lastName) ? $friend->lastName : ''; ?></a><br />
		</td>
		<td>
			<?php echo ($friend->homeCity); ?>
		</td>
		<td>
			<a href="<?php echo site_url('foursquare/profile') .'/'. $friend->id; ?>" class="btn secondary">View Profile</a>
		</td>
	</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>

<?php endif; ?>

<?php if (isset($member->mayorships->count) && $member->mayorships->count > 0): ?>
<h3>Mayorships <small>(<?php echo (int) $member->mayorships->count; ?>)</small></h3>
<table class="table">
<tbody>
	<?php foreach ($member->mayorships->items as $row): ?>
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

<hr />

<p>
	<a href="<?php echo site_url('dashboard'); ?>">&laquo; Back to Dashboard</a>
</p>