<div class="admin-content client-settings">
	
	<h2><span><?php echo $client->name; ?></span> settings</h2>
	
	<?php echo form_open("super-admin/client-settings/edit/{$client->id}", array('class' => 'edit-row-form')); ?>
		
		<ul class="data-rows-list">
			<?php foreach ($setting_groups as $set => $title): ?>
			<li class="data-row">
				<h3><?php echo $title; ?></h3>
				
				<?php foreach ($client->settings as $setting => $props): ?>
				<?php if ($props['group'] === $set): ?>
				<div class="control-group">
					<label for="<?php echo $setting; ?>"><?php echo $props['label']; ?></label>
					
					<?php switch ($props['type']): case 'date': ?>
					
					<div class="date-picker-wrap">
						<input
							id="<?php echo $setting; ?>"
							class="date-picker"
							type="text"
							name="<?php echo $setting; ?>"
							value="<?php echo set_value($setting, $props['display_value']); ?>"
							data-date="<?php echo $props['value']; ?>"
						/>
					</div>
					
					<?php break; case 'select': ?>
					
					<?php echo form_dropdown($setting, $props['options'], $props['value']); ?>
					
					<?php break; default: ?>
					
					<input
						id="<?php echo $setting; ?>"
						type="text"
						name="<?php echo $setting; ?>"
						value="<?php echo set_value($setting, $props['value']); ?>"
					/>
					
					<?php endswitch; ?>
					
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				
				<div class="clear"></div>
			</li>
			<?php endforeach; ?>
		</ul>
		
		<div class="clear"></div>
		
		<div class="form-btns">
			<button type="submit" name="update" value="TRUE">Update</button>
			<a class="nav-btn cancel-edit-btn" href="<?php echo site_url('super-admin/clients'); ?>">Cancel</a>
			<div class="clear"></div>
		</div>
		
	<?php echo form_close(); ?>
	
</div>