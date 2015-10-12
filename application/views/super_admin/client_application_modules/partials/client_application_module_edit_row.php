<?php $row_id = 'application-module-' . $row->id; ?>

<?php echo form_open("super-admin/client-application-modules/{$client_id}/{$row->client_application_id}/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
	
	<h3>Edit <span><?php echo $row->name; ?></span></h3>
	
	<div class="section">
		
		<input type="hidden" name="client_application_id" value="<?php echo $row->client_application_id; ?>" />
		<input type="hidden" name="application" value="<?php echo $row->application; ?>" />
		
		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-name">Module name</label>
			<input
				id="<?php echo $row_id; ?>-name"
				class="client-application-name-input"
				type="text"
				name="name"
				value="<?php echo set_value('name', $row->name); ?>"
				maxlength="60"
			/>
			<?php echo form_error('name'); ?>
		</div>
		
		<div class="control-group">
			<label>Module</label>
			<input
				type="text"
				name="original_name"
				value="<?php echo $row->original_name; ?>"
				disabled="disabled"
			/>
			<input type="hidden" name="module" value="<?php echo $row->module; ?>" />
		</div>
		
		<div class="control-group">
			<label>Active?</label>
			<input type="hidden" name="active" value="0" />
			<input
				id="<?php echo $row_id; ?>-active"
				type="checkbox"
				name="active"
				value="1"
				<?php if ($row->active == 1): ?>checked="checked"<?php endif; ?>
			/>
			<label for="<?php echo $row_id; ?>-active" class="checkbox-label<?php if ($row->active == 1): ?> checked<?php endif; ?>">Active</label>
			<?php echo form_error('active'); ?>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="form-btns">
		<button type="submit" name="update" value="TRUE">Update</button>
		<a class="nav-btn cancel-edit-btn">Cancel</a>
		<div class="clear"></div>
	</div>

<?php echo form_close(); ?>