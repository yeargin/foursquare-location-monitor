<div class="modal hide fade in" id="tagModal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Edit Tags</h3>
	</div>
	<div class="modal-body">
		<form id="tag-form" class="form form-horizontal">
		
			<div class="control-group">
				<label class="control-label">Existing Tags</label>
				<div class="controls">
					<div id="existing_tags">(None)</div>
				</div>
			</div>
		
			<div class="control-group">
				<label class="control-label">New Tags</label>
				<div class="controls">
					<input name="tags" value="" id="tags" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label">Previous Tags</label>
				<div class="controls">
					<?php echo listTags($tag_list, ' <span class="label label-info" onclick="selectTag(this);" style="cursor:pointer;">%s</span>'); ?>
				</div>
			</div>

			<input type="hidden" name="check_id" id="check_id" value="" />
		</form>
	</div>
	<div class="modal-footer">
		<a href="javascript:void(0);" onclick="saveTags();" class="btn btn-primary">Save changes</a>
	</div>
</div>