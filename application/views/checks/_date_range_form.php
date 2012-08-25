<h3>Date Range</h3>
<form>
<fieldset class="form form-stacked">
	<div class="control-group">
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-calendar"></i></span><input type="text" value="<?php echo date('n/j/Y', strtotime($date_range['start_date'])); ?>" name="start_date" class="span2 datepicker" />
			</div>
		</div>
	</div>
	<div class="control-group">	
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-calendar"></i></span><input type="text" value="<?php echo date('n/j/Y', strtotime($date_range['end_date'])); ?>" name="end_date" class="span2 datepicker" />
			</div>
		</div>
		</div>
	<div>
		<input type="submit" class="btn" value="Change"/>
	</div>
</fieldset>
</form>