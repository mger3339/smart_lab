<?php $row_id = 'application-module-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">
	
	<div class="section row-btns">
		<div class="btn sort-row-btn"><span>&equiv;</span></div>
		<button class="edit-row-btn" data-url="<?php echo "super-admin/client-application-modules/{$client_id}/{$row->client_application_id}/edit/{$row->id}"; ?>">Edit</button>
		<div class="clear"></div>
	</div>
	
	<div class="section">
		
		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-name">Module name</label>
			<p><?php echo $row->name; ?></p>
		</div>
		
		<div class="control-group">
			<label>Module</label>
			<p><?php echo $row->original_name; ?></p>
		</div>
		
		<div class="control-group">
			<label>Active</label>
			<p>Active: <strong>&nbsp;<?php if ($row->active == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
</div>