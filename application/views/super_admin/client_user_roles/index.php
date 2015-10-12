<div class="admin-content client-user-roles">
	
	<h2><span><?php echo $client->name; ?></span> user roles</h2>
	
	<?php echo form_open("super-admin/client-user-roles/edit/{$client->id}", array('class' => 'edit-row-form')); ?>
		
		<?php foreach ($user_roles as $role => $attr): ?>
		<div class="control-group">
			<label>&nbsp;</label>
			<input
				id="<?php echo $role; ?>-role"
				type="checkbox"
				name="user_roles[]"
				value="<?php echo $role; ?>"
				<?php if (in_array($role, $client->user_roles)): ?>
				checked="checked"
				<?php endif; ?>
				<?php if ($attr['default'] === TRUE): ?>
				disabled="disabled"
				<?php endif; ?>
			/>
			<label
				for="<?php echo $role; ?>-role"
				class="checkbox-label<?php if (in_array($role, $client->user_roles)): ?> checked<?php endif; ?>"
			><?php echo $attr['name']; ?><?php if ($attr['default'] === TRUE): ?> *<?php endif; ?><?php if ($attr['admin'] === TRUE): ?> **<?php endif; ?><?php if ($attr['guest'] === TRUE): ?> ***<?php endif; ?></label>
		</div>
		<?php endforeach; ?>
		
		<div class="clear"></div>
		
		<div class="form-btns">
			<button type="submit" name="update" value="TRUE">Update</button>
			<a class="nav-btn cancel-edit-btn" href="<?php echo site_url('super-admin/clients'); ?>">Cancel</a>
			<div class="clear"></div>
		</div>
		
	<?php echo form_close(); ?>
	
	<p>* Default user role &nbsp;&nbsp; ** Admin user role &nbsp;&nbsp; *** Guest user role</p>
	
</div>