<?php $row_id = (property_exists($row, 'id')) ? 'client-' . $row->id : 'new-client'; ?>

<?php if ($action == 'add'): ?>
<?php echo form_open('super-admin/clients/add', array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
<?php echo form_open("super-admin/clients/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>
	
	<?php if ($action_title): ?>
	<h3><?php echo $action_title; ?></h3>
	<?php endif; ?>
	
	<?php if ($action == 'edit'): ?>
	<input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
	<?php endif; ?>
	
	<input type="hidden" name="client_id" value="<?php echo $row->id; ?>" />
	
	<div class="section form-section">
		
		<div class="control-group control-group-large control-group-wide">
			<label for="<?php echo $row_id; ?>-name">Name</label>
			<input
				id="<?php echo $row_id; ?>-name"
				class="client-name-input"
				type="text"
				name="name"
				value="<?php echo set_value('name', $row->name); ?>"
				maxlength="60"
			/>
			<?php echo form_error('name'); ?>
		</div>
		
		<div class="control-group">
			<label for="<?php echo $row_id; ?>-slug">Slug</label>
			<input
				id="<?php echo $row_id; ?>-slug"
				class="client-slug-input"
				type="text"
				name="slug"
				value="<?php echo set_value('slug', $row->slug); ?>"
				maxlength="60"
			/>
			<?php echo form_error('slug'); ?>
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
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-hostname">Domain name</label>
			<input
				id="<?php echo $row_id; ?>-hostname"
				class="client-hostname-input"
				type="text"
				name="hostname"
				value="<?php echo set_value('hostname', $row->hostname); ?>"
				maxlength="255"
			/>
			<?php echo form_error('hostname'); ?>
		</div>
		
		<div class="control-group">
			<label>SSL enabled?</label>
			<input type="hidden" name="enable_ssl" value="0" />
			<input
				id="<?php echo $row_id; ?>-enable_ssl"
				type="checkbox"
				name="enable_ssl"
				value="1"
				<?php if ($row->enable_ssl == 1): ?>checked="checked"<?php endif; ?>
			/>
			<label for="<?php echo $row_id; ?>-enable_ssl" class="checkbox-label<?php if ($row->enable_ssl == 1): ?> checked<?php endif; ?>">Enable SSL</label>
			<?php echo form_error('enable_ssl'); ?>
		</div>
		
		<div class="clear"></div>
		
		<?php
			date_default_timezone_set($row->time_zone);
		?>
		
		<div class="control-group">
			<label>Commencement date</label>
			<input
				id="<?php echo $row_id; ?>-commence"
				class="mobile-date-picker"
				type="text"
				name="commence"
				value="<?php echo set_value('commence', date('Y-m-d', $row->commence)); ?>"
				data-min-date=""
				data-max-date="#<?php echo $row_id; ?>-expire"
			/>
			<?php echo form_error('commence'); ?>
		</div>
		
		<div class="control-group">
			<label>Expiry date</label>
			<input
				id="<?php echo $row_id; ?>-expire"
				class="mobile-date-picker"
				type="text"
				name="expire"
				value="<?php echo set_value('expire', date('Y-m-d', $row->expire)); ?>"
				data-min-date="#<?php echo $row_id; ?>-commence"
				data-max-date=""
			/>
			<?php echo form_error('expire'); ?>
		</div>
		
		<?php
			date_default_timezone_set(DEFAULT_TIMEZONE);
		?>
		
		<div class="clear"></div>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-language">Language folder</label>
			<?php echo form_dropdown('language', $language_options, $row->language); ?>
			<?php echo form_error('language'); ?>
		</div>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-country_iso">Country</label>
			<?php echo form_dropdown('country_iso', $country_options, $row->country_iso); ?>
			<?php echo form_error('country_iso'); ?>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-time_zone">Time zone</label>
			<?php echo form_dropdown('time_zone', $time_zone_options, $row->time_zone); ?>
			<?php echo form_error('time_zone'); ?>
		</div>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-currency">Currency</label>
			<?php echo form_dropdown('currency', $currency_options, $row->currency); ?>
			<?php echo form_error('currency'); ?>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group">
			<label for="<?php echo $row_id; ?>-session_timeout">User inactivity session timeout (seconds)</label>
			<input
				id="<?php echo $row_id; ?>-session_timeout"
				class="client-session_timeout-input"
				type="text"
				name="session_timeout"
				value="<?php echo set_value('session_timeout', $row->session_timeout); ?>"
				maxlength="10"
			/>
			<?php echo form_error('session_timeout'); ?>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="section form-section">
		
		<h3>Account user<?php if ($action == 'edit'): ?> for <span><?php echo $row->name; ?></span><?php endif; ?></h3>
		
		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-admin_user">Account user set up</label>
			<select class="form-toggle-select" id="<?php echo $row_id; ?>-admin_user" name="admin_user">
				<option
					value="new_admin_user"
					data-section-id="#new_admin_user"
					<?php if (($action == 'add' && ! $form_admin_user_choice) || $form_admin_user_choice === 'new_admin_user'): ?>
					selected="selected"
					<?php endif; ?>
				>Create new admin user for this client</option>
				<option
					value="existing_admin_user"
					data-section-id="#existing_admin_user"
					<?php if (($action == 'edit' && ! $form_admin_user_choice) ||  $form_admin_user_choice === 'existing_admin_user'): ?>
					selected="selected"
					<?php endif; ?>
				>Use existing <?php if ($action == 'add'): ?>super <?php endif; ?>admin user</option>
				<option
					value="this_admin_user"
					data-section-id="#this_admin_user"
				>You (<?php echo $this->user->fullname; ?>)</option>
			</select>
		</div>
		
		<div class="clear"></div>
		
		<div id="new_admin_user" class="form-toggle-section <?php echo $row_id; ?>-admin_user">
			
			<div class="control-group">
				<label for="<?php echo $row_id; ?>-new_admin_user_firstname">First name</label>
				<input
					id="<?php echo $row_id; ?>-new_admin_user_firstname"
					type="text"
					name="firstname"
					value="<?php echo set_value('firstname'); ?>"
					maxlength="60"
				/>
				<?php echo form_error('firstname'); ?>
			</div>
			
			<div class="control-group">
				<label for="<?php echo $row_id; ?>-new_admin_user_lastname">Last name</label>
				<input
					id="<?php echo $row_id; ?>-new_admin_user_lastname"
					type="text"
					name="lastname"
					value="<?php echo set_value('lastname'); ?>"
					maxlength="60"
				/>
				<?php echo form_error('lastname'); ?>
			</div>
			
			<div class="control-group control-group-wide">
				<label for="<?php echo $row_id; ?>-new_admin_user_email">Email</label>
				<input
					id="<?php echo $row_id; ?>-new_admin_user_email"
					type="text"
					name="email"
					value="<?php echo set_value('email'); ?>"
				/>
				<?php echo form_error('email'); ?>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<div id="existing_admin_user" class="form-toggle-section <?php echo $row_id; ?>-admin_user">
			
			<?php if ($action == 'edit'): ?>
			<div class="control-group control-group-wide">
				<label for="<?php echo $row_id; ?>-existing_admin_user_fullname">Full name</label>
				<input
					id="<?php echo $row_id; ?>-existing_admin_user_fullname"
					type="text"
					name="existing_admin_user_fullname"
					value="<?php echo $row->admin_user->fullname; ?>"
					maxlength="120"
					disabled="disabled"
				/>
			</div>
			<?php endif; ?>
			
			<div class="control-group control-group-wide">
				<label for="<?php echo $row_id; ?>-existing_admin_user_email">Email</label>
				<input
					id="<?php echo $row_id; ?>-existing_admin_user_email"
					type="text"
					name="existing_admin_user_email"
					<?php if ($action == 'add'): ?>
					value="<?php echo set_value('existing_admin_user_email'); ?>"
					<?php endif; ?>
					<?php if ($action == 'edit'): ?>
					value="<?php echo set_value('existing_admin_user_email', $row->admin_user->email); ?>"
					<?php endif; ?>
				/>
				<?php if (isset($form_errors['existing_admin_user_email'])): ?>
				<span class="error"><?php echo $form_errors['existing_admin_user_email']; ?></span>
				<?php endif; ?>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<div id="this_admin_user" class="form-toggle-section <?php echo $row_id; ?>-admin_user">
			
			<div class="control-group control-group-wide">
				<label for="<?php echo $row_id; ?>-this_admin_user_fullname">Full name</label>
				<input
					id="<?php echo $row_id; ?>-this_admin_user_fullname"
					type="text"
					name="this_admin_user_fullname"
					value="<?php echo $this->user->fullname; ?>"
					maxlength="120"
					disabled="disabled"
				/>
			</div>
			
			<div class="control-group control-group-wide">
				<label for="<?php echo $row_id; ?>-this_admin_user_email">Email</label>
				<input
					id="<?php echo $row_id; ?>-this_admin_user_email"
					type="text"
					name="this_admin_user_email"
					value="<?php echo $this->user->email; ?>"
					disabled="disabled"
				/>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
	</div>
	
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