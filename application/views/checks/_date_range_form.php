<form>
<fieldset>
	<legend>Date Range</legend>
	<div class="input-prepend">
		<span class="add-on"><i class="icon-calendar"></i></span><input type="text" value="<?php echo date('n/j/Y', strtotime($date_range['start_date'])); ?>" name="start_date" class="span2 datepicker" />
	</div>
	<div class="input-prepend">
		<span class="add-on"><i class="icon-calendar"></i></span><input type="text" value="<?php echo date('n/j/Y', strtotime($date_range['end_date'])); ?>" name="end_date" class="span2 datepicker" />
	</div>
	<div>
		<input type="submit" class="btn btn-small" value="Change"/>
	</div>
</fieldset>
</form>