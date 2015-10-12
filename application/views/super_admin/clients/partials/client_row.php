<?php $row_id = 'client-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">
	
	<div class="section row-btns">
		<?php echo form_open("super-admin/clients/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client? All data relative to this client will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client?\n")); ?>
			<button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
		<?php echo form_close(); ?>
		<button class="edit-row-btn" data-url="<?php echo "super-admin/clients/edit/{$row->id}"; ?>">Edit</button>
		<div class="clear"></div>
	</div>
	
	<div class="section">
		
		<div class="control-group control-group-large control-group-wide">
			<label for="<?php echo $row_id; ?>-name">Name</label>
			<p><?php echo $row->name; ?></p>
		</div>
		
		<div class="control-group">
			<label>Active</label>
			<p>Active: <strong>&nbsp;<?php if ($row->active == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group control-group-wide">
			<label>URL</label>
			<p><a href="<?php echo $row->url; ?>" title="<?php echo $row->url; ?>" target="_blank"><?php echo $row->url; ?></a></p>
		</div>
		
		<div class="control-group">
			<label>Created</label>
			<p><?php echo strftime("%a %e %b %Y, %T", $row->created); ?></p>
		</div>
		
		<?php if ($row->modified > $row->created): ?>
		<div class="control-group">
			<label>Last Modified</label>
			<p><?php echo strftime("%a %e %b %Y, %T", $row->modified); ?></p>
		</div>
		<?php endif; ?>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="section toggle-section">
		
		<h4><span class="toggle-btn">Details</span></h4>
		
		<div class="toggle-content">
			
			<?php
				date_default_timezone_set($row->time_zone);
			?>
			
			<div class="control-group">
				<label>Commencement date &amp; time</label>
				<p><?php echo date('D j M Y, H:i', $row->commence); ?></p>
			</div>
			
			<div class="control-group">
				<label>Expiry date &amp; time</label>
				<p><?php echo date('D j M Y, H:i', $row->expire); ?></p>
			</div>
			
			<?php
				date_default_timezone_set(DEFAULT_TIMEZONE);
			?>
			
			<div class="clear"></div>
			
			<div class="control-group control-group-wide">
				<label>Account user full name</label>
				<p><?php echo $row->admin_user->fullname; ?></p>
			</div>
			
			<div class="control-group control-group-wide">
				<label>Account user email</label>
				<p><a href="mailto:<?php echo $row->admin_user->email; ?>"><?php echo $row->admin_user->email; ?></a></p>
			</div>
			
			<div class="clear"></div>
			
			<div class="control-group control-group-wide">
				<label>Language folder</label>
				<p>language/<?php echo $row->language; ?></p>
			</div>
			
			<div class="control-group control-group-wide">
				<label>Country</label>
				<p><?php echo $country_options[$row->country_iso]; ?></p>
			</div>
			
			<div class="control-group control-group-wide">
				<label>Time zone</label>
				<p><?php echo $time_zone_options[$row->time_zone]; ?></p>
			</div>
			
			<div class="control-group control-group-wide">
				<label>Currency</label>
				<p><?php echo $currency_options[$row->currency]; ?></p>
			</div>
			
			<div class="clear"></div>
			
			<div class="control-group">
				<label>User inactivity session timeout (h:m:s)</label>
				<p><?php echo gmdate("H:i:s", $row->session_timeout); ?></p>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
	</div>
	
	<div class="section toggle-section">
		
		<h4><span class="toggle-btn">Settings </span><a href="<?php echo site_url('super-admin/client-settings/' . $row->id); ?>">[edit]</a></h4>
		
		<div class="toggle-content">
			
			<?php foreach ($row->settings as $option => $props): ?>
			<div class="control-group">
				<label><?php echo $props['label']; ?></label>
				<p><?php echo $props['display_value']; ?>&nbsp;</p>
			</div>
			<?php endforeach; ?>
			
			<div class="clear"></div>
			
		</div>
		
	</div>
	
	<div class="section toggle-section">
		
		<h4><span class="toggle-btn">Applications </span><a href="<?php echo site_url("super-admin/client-applications/{$row->id}"); ?>">[edit]</a></h4>
		
		<div class="toggle-content">
			
			<?php if ($row->applications && ! empty($row->applications)): ?>
			
			<?php $c = 1; ?>
			<?php foreach ($row->applications as $client_application): ?>
			<div class="control-group">
				<label><span><?php echo $c; ?></span> <?php echo $client_application->original_name; ?></label>
				<p><?php echo $client_application->name; ?></p>
			</div>
			<?php $c++; ?>
			<?php endforeach; ?>
			
			<div class="clear"></div>
			
			<?php else: ?>
			
			<h5>No applications assigned</h5>
			
			<?php endif; ?>
						
		</div>
		
	</div>
	
	<div class="section toggle-section">
		
		<h4><span class="toggle-btn">User roles </span><a href="<?php echo site_url('super-admin/client-user-roles/' . $row->id); ?>">[edit]</a></h4>
		
		<div class="toggle-content">
			
			<?php foreach ($user_roles as $role => $attr): ?>
			<div class="control-group">
				<label>&nbsp;</label>
				<label class="checkbox-label dummy-checkbox-label<?php if (in_array($role, $row->user_roles)): ?> checked<?php endif; ?>"><?php echo $attr['name']; ?></label>
			</div>
			<?php endforeach; ?>
			
			<div class="clear"></div>
			
		</div>
		
	</div>
	
</div>