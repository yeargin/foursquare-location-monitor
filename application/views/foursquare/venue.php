<div class="hero-unit">
	
	<h2><?php echo $venue->name; ?></h2>
	
	<div class="row">
		<div class="span7">
		<div id="map" class="spinner" style="width:100%; height:200px; margin-bottom:1em;">
			<p class="alert alert-info">Loading ...</p>
		</div>
		</div>
	</div>

	<div class="row">
	
		<div class="span3">
				
			<address>
				<?php echo isset($venue->location->address) ? $venue->location->address . '<br />' : ''; ?> 
				<?php echo isset($venue->location->city) ? $venue->location->city . ', ': ''; ?> 	<?php echo isset($venue->location->state) ? $venue->location->state : ''; ?>
				<?php echo isset($venue->location->postalCode) ? $venue->location->postalCode: ''; ?> <br />
				<?php echo isset($venue->location->country) ? $venue->location->country : ''; ?>
			</address>
	
			<h3>Stats</h3>
			<ul>
				<li><?php echo number_format($venue->stats->checkinsCount); ?> total checkins</li>
				<li><?php echo number_format($venue->stats->usersCount); ?> unique visitors</li>
				<li><?php echo number_format($venue->stats->tipCount); ?> tips left</li>
				<li><?php echo isset($venue->stats->photoCount) ? number_format($venue->stats->photoCount) : 0; ?> photos posted</li>
			</ul>
	
		</div>
		<div class="span4">

			<?php if (count($venue->categories) > 0): ?>
			<h3>Categories</h3>
			<div class="row">
				<?php foreach ($venue->categories as $category): ?>
					<div class="span2"><img src="<?php echo ($category->icon->prefix) . '32.png'; ?>" alt="<?php echo ($category->name); ?>" style="vertical-align:middle;margin-right:0.25em; margin-bottom:0.25em;"/> <?php echo ($category->name); ?></div> 
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<p style="margin-top:1em;">
				<a href="<?php echo ($venue->canonicalUrl); ?>" class="btn">View on Foursquare</a>
			</p>

		</div>

	</div>

</div>

<?php if (isset($stats->stats)): ?>
<h3>Venue Manager Stats</h3>
<pre>
<?php print_R($stats); ?>
</pre>

<hr />

<?php endif; ?>

<?php if (isset($check->id) && $check->id > 0): ?>
<?php if ($check->active != '1'): ?>
<p class="alert alert-error">
	<i class="icon-exclamation-sign"></i> <strong>This check is not active!</strong> Data is not being collected for this venue. <a href="<?php echo site_url('checks/check_edit') .'/'. $check->id; ?>">Edit Check</a>
	<span class="close" onclick="$('.alert.warning').hide();">&times</span>
</p>
<?php endif; ?>

<h3>Live Metrics</h3>

<?php if (count($live_data) > 2): ?>
<div id="chart_live" class="spinner" style="width:100%; height:275px; margin-bottom:1em;">
	<p class="alert alert-info">Loading ...</p>
</div>
<?php else: ?>
<p class="alert alert-info">
	<i class="icon-time"></i> Live metrics can be viewed in about 15 minutes.
	<span class="close" onclick="$('.alert').hide();">&times</span>
</p>
<?php endif; ?>

<h3>Daily Metrics</h3>

<?php if (count($daily_data_delta) > 2): ?>
<div id="chart_daily" class="spinner" style="width:100%; height:275px; margin-bottom:1em;">
	<p class="alert alert-info">Loading ...</p>
</div>
<?php else: ?>
<p class="alert alert-info">
	<i class="icon-time"></i> Daily metrics can be viewed in about 48 hours.
	<span class="close" onclick="$('.alert').hide();">&times</span>
</p>
<?php endif; ?>

<p>
	<a href="<?php echo site_url('checks/check') .'/'. $check->id; ?>" class="btn small">Check Log</a>
	<a href="<?php echo site_url('checks/check_edit') .'/'. $check->id; ?>" class="btn small">Edit Check</a>
</p>

<hr />

<?php else: ?>

<p class="alert alert-information">
	This venue is not being monitored. <a href="<?php echo site_url('checks/check_add') .'/'. $venue->id; ?>">Activate monitoring check</a>?
	<span class="close" onclick="$('.alert').hide();">&times</span>
</p>

<?php endif; ?>

<?php if (isset($photos->groups) && $photos->count > 0): ?>
<h3>Photos</h3>
<ul class="thumbnails">
<?php foreach ($photos->groups as $group): ?>
<?php foreach ($group->items as $item): ?>
	<li class="span2"><a href="<?php echo $item->url; ?>" class="fancybox" rel="photos" title="Photo: <?php echo $item->user->firstName; ?> <?php echo isset($item->user->lastName) ? $item->user->lastName : ''; ?>, <?php echo date('F j, Y', $item->createdAt); ?>"><img src="<?php echo $item->sizes->items[1]->url; ?>" alt="Photo" /></a></li>
<?php endforeach; ?>
<?php endforeach; ?>
</ul>
<hr />
<?php endif; ?>

<?php if (isset($tips->items) && $tips->count > 0): ?>
<h3>Tips</h3>
<ul class="thumbnails">
<?php foreach ($tips->items as $item): ?>
	<li class="span2">
		<blockquote style="height:175px; overflow-y:auto; width: 100%;">
		<?php echo nl2br($item->text); ?>
		<small><?php echo $item->user->firstName; ?> <?php echo isset($item->user->lastName) ? $item->user->lastName : ''; ?>, <?php echo date('F j, Y', $item->createdAt); ?></small>
		</blockquote>
	</li>
<?php endforeach; ?>
</ul>

<hr />
<?php endif; ?>


<h3>The Mayor</h3>
<?php if (isset($venue->mayor->user)): ?>
<table class="table">
<tbody>
<tr>
	<td><img src="<?php echo ($venue->mayor->user->photo); ?>" alt="Profile Picture" /></td>
	<td>
		<a href="<?php echo site_url('foursquare/profile') .'/'. $venue->mayor->user->id; ?>"><?php echo ($venue->mayor->user->firstName); ?> <?php echo isset($venue->mayor->user->lastName) ? $venue->mayor->user->lastName : ''; ?></a><br />
	</td>
	<td><?php echo ($venue->mayor->user->homeCity); ?></td>
	<td><a href="<?php echo site_url('foursquare/profile') .'/'. $venue->mayor->user->id; ?>" class="btn secondary">View Profile</a></td>
</tr>
</tbody>
</table>
<?php else: ?>
<p>
	<em>This place does not have a mayor.</em>
</p>
<?php endif; ?>

<hr />

<?php if (isset($venue->hereNow->groups)): ?>
<h3>Here Now <small>(<?php echo (int) $venue->hereNow->count; ?>)</small></h3>
<?php if ($venue->hereNow->count > 0): ?>
<table class="table">
<tbody>
<?php foreach ($venue->hereNow->groups as $group): ?>
	<tr>
		<th colspan="4">
			<h3><?php echo $group->name; ?> <small>(<?php echo $group->count; ?>)</small></h4>
		</th>
	</tr>
<?php foreach ($group->items as $friend): ?>
	<tr>
		<td><img src="<?php echo ($friend->user->photo); ?>" alt="Profile Picture" /></td>
		<td>
			<a href="<?php echo site_url('foursquare/profile') .'/'. $friend->user->id; ?>"><?php echo ($friend->user->firstName); ?> <?php echo isset($friend->user->lastName) ? $friend->user->lastName : ''; ?></a><br />
			<?php echo time_ago(date('c', $friend->createdAt)); ?>
		</td>
		<td>
			<?php echo ($friend->user->homeCity); ?>
		</td>
		<td>
			<a href="<?php echo site_url('foursquare/profile') .'/'. $friend->user->id; ?>" class="btn secondary">View Profile</a>
		</td>
	</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<p>
	<em>Nobody is here right now.</em>
</p>
<?php endif; ?>

<hr />

<?php endif; ?>

<p>
	<a href="<?php echo site_url('checks'); ?>">&laquo; Back to Checks</a>
</p>

<?php include_once('_map_js.php'); ?>