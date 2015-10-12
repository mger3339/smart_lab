<?php $row_id = (property_exists($row, 'id')) ? 'user-' . $row->id : 'new-user'; ?>

<?php if ($action == 'add'): ?>
<?php echo form_open('super-admin/users/add', array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
<?php echo form_open("super-admin/users/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>
	
	<?php if ($action_title): ?>
	<h3><?php echo $action_title; ?></h3>
	<?php endif; ?>
	
	<?php if ($action == 'edit'): ?>
	<input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
	<?php endif; ?>
	
	<input type="hidden" name="client_id" value="0" />
	
	<div class="section form-section">
		
		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-name">First name</label>
			<input
				id="<?php echo $row_id; ?>-firstname"
				type="text"
				name="firstname"
				value="<?php echo set_value('firstname', $row->firstname); ?>"
				maxlength="60"
			/>
			<?php echo form_error('firstname'); ?>
		</div>
		
		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-name">Last name</label>
			<input
				id="<?php echo $row_id; ?>-lastname"
				type="text"
				name="lastname"
				value="<?php echo set_value('lastname', $row->lastname); ?>"
				maxlength="60"
			/>
			<?php echo form_error('lastname'); ?>
		</div>
		
		<?php if ($row->id != $this->user->id): ?>
		<div class="control-group">
			<label>Super-admin admin?</label>
			<input type="hidden" name="super_admin_admin" value="0" />
			<input
				id="<?php echo $row_id; ?>-super_admin_admin"
				type="checkbox"
				name="super_admin_admin"
				value="1"
				<?php if ($row->super_admin_admin == 1): ?>
				checked="checked"
				<?php endif; ?>
			/>
			<label for="<?php echo $row_id; ?>-super_admin_admin" class="checkbox-label<?php if ($row->super_admin_admin == 1): ?> checked<?php endif; ?>">Admin</label>
			<?php echo form_error('super_admin_admin'); ?>
		</div>
		<?php endif; ?>
		
		<?php if ($row->id != $this->user->id): ?>
		<div class="control-group">
			<label>Active?</label>
			<input type="hidden" name="active" value="0" />
			<input
				id="<?php echo $row_id; ?>-active"
				type="checkbox"
				name="active"
				value="1"
				<?php if ($row->active == 1): ?>
				checked="checked"
				<?php endif; ?>
			/>
			<label for="<?php echo $row_id; ?>-active" class="checkbox-label<?php if ($row->active == 1): ?> checked<?php endif; ?>">Active</label>
			<?php echo form_error('active'); ?>
		</div>
		<?php endif; ?>
		
		<div class="clear"></div>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-email">Email</label>
			<input
				id="<?php echo $row_id; ?>-email"
				type="text"
				name="email"
				value="<?php echo set_value('email', $row->email); ?>"
				maxlength="255"
			/>
			<?php echo form_error('email'); ?>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<input type="hidden" name="country_iso" value="<?php echo $row->country_iso; ?>" />
	<input type="hidden" name="time_zone" value="<?php echo $row->time_zone; ?>" />
	<input type="hidden" name="currency" value="<?php echo $row->currency; ?>" />
	
	<div class="form-btns">
		<?php if ($action == 'add'): ?>
		<button type="submit" name="put" value="TRUE">Add</button>
		<a class="nav-btn cancel-add-btn">Cancel</a>
		<?php elseif ($action == 'edit'): ?>
		<button type="submit" name="update" value="TRUE">Update</button>
		<a class="nav-btn cancel-edit-btn">Cancel</a>
		<?php endif; ?>
		<div class="clear"></div>
	</div>

<?php echo form_close(); ?>