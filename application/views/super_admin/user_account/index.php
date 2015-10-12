<div class="admin-content user-account">
	
	<h2>Your account</h2>
	
	<?php echo form_open('super-admin/my-account/update', array('class' => 'edit-row-form')); ?>
		
		<input type="hidden" name="unique_id" value="<?php echo $user->id; ?>" />
	
		<input type="hidden" name="client_id" value="0" />
		
		<div class="section form-section">
			
			<h3>Your details</h3>
			
			<div class="control-group control-group-large">
				<label for="firstname">First name</label>
				<input
					id="firstname"
					type="text"
					name="firstname"
					value="<?php echo set_value('firstname', $user->firstname); ?>"
					maxlength="60"
				/>
				<?php echo form_error('firstname'); ?>
			</div>
			
			<div class="control-group control-group-large">
				<label for="lastname">Last name</label>
				<input
					id="lastname"
					type="text"
					name="lastname"
					value="<?php echo set_value('lastname', $user->lastname); ?>"
					maxlength="60"
				/>
				<?php echo form_error('lastname'); ?>
			</div>
			
			<div class="control-group control-group-wide">
				<label for="email">Email</label>
				<input
					id="email"
					type="text"
					name="email"
					value="<?php echo set_value('email', $user->email); ?>"
					maxlength="255"
				/>
				<?php echo form_error('email'); ?>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<input type="hidden" name="time_zone" value="<?php echo $user->time_zone; ?>" />
		<input type="hidden" name="country_iso" value="<?php echo $user->country_iso; ?>" />
		<input type="hidden" name="currency" value="<?php echo $user->currency; ?>" />
		
		<div class="form-btns">
			<button type="submit" name="update_account" value="TRUE">Update your details</button>
			<div class="clear"></div>
		</div>
		
	<?php echo form_close(); ?>
	
	<?php echo form_open('super-admin/my-account/change-password', array('class' => 'edit-row-form')); ?>
		
		<div class="section form-section">
			
			<h3>Change your password</h3>
			
			<div class="control-group">
				<label for="password">Your current password</label>
				<input
					id="password"
					type="password"
					name="password"
					value=""
					maxlength="60"
					autocomplete="off"
				/>
				<?php echo form_error('password'); ?>
			</div>
			
			<div class="control-group">
				<label for="new_password">Your new password</label>
				<input
					id="new_password"
					type="password"
					name="new_password"
					value=""
					maxlength="60"
					autocomplete="off"
				/>
				<?php echo form_error('new_password'); ?>
			</div>
			
			<div class="control-group">
				<label for="new_password_confirm">Re-type your new password</label>
				<input
					id="new_password_confirm"
					type="password"
					name="new_password_confirm"
					value=""
					maxlength="60"
					autocomplete="off"
				/>
				<?php echo form_error('new_password_confirm'); ?>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<div class="form-btns">
			<button type="submit" name="update_password" value="TRUE">Update your password</button>
			<div class="clear"></div>
		</div>
		
	<?php echo form_close(); ?>
	
</div>