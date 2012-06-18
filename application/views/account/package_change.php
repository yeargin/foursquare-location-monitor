<div class="hero-unit">
	<p>You are changing from the <strong><?php echo $usage['package']->name; ?></strong> package with <strong><?php echo $usage['package']->check_limit; ?> monitored locations</strong> to the <strong><?php echo $package->name; ?></strong> package with <strong><?php echo $package->check_limit; ?> monitored locations</strong>.</p>
</div>

<div class="row">
	<div class="span6">
		<form method="post" action="<?php echo site_url('packages/change_post'); ?>" class="form form-horizontal" id="change_form">
		<?php if ($overage): ?>
			<p class="alert alert-warning">
				Your account would be over its allowed quota by <strong><?php echo $overage; ?> monitored location</strong>. You must select which monitored locations to keep active. 
			</p>
			<script>
			$(document).ready(function() {
				$('input.check').live('change', function() {
					var count = $('input.check:checked').length;
					$('.active-checks').html(count);
				});
				console.log('test');
				$('#change_form').submit(function() {
					event.stopPropagation();
					if ($('.active-checks').html() > <?php echo $package->check_limit; ?>) {
						alert('You must select less than <?php echo $package->check_limit; ?> locations');
						return false;
					} else {
						$(this).submit();
					}
				});

			});
			</script>
			<div class="control-group">
				<label for="optionsCheckboxList" class="control-label">Monitored Locations</label>
				<div class="controls">
					<?php foreach ($checks as $check): ?>
					<label class="checkbox">
				    	<input name="checks[]" class="check" type="checkbox" value="<?php echo $check->id; ?>" <?php echo ($check->active == 1) ? 'checked="checked"' : ''; ?>/> <?php echo __($check->check_title); ?>
					</label>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" value="Confirm Change" class="btn btn-primary">
			</div>
		<?php else: ?>
			<input type="submit" value="Confirm Change" class="btn btn-primary btn-large">
		<?php endif; ?>
			<input type="hidden" name="package_id" value="<?php echo $package->id; ?>" />
		</form>
	</div>
	<div class="span3">
		<h2><span class="active-checks"><?php echo $usage['checks']->active_checks; ?></span> <small>monitored venues.</h2>
	</div>
</div>