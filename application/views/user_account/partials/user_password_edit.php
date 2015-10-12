<?php echo form_open('user_account/update_password', array('id' => 'user-password-update', 'class' => 'user-account-form')); ?>
	
	<div class="section">
		
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
		
		<div class="clear"></div>
		
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
		<button type="submit" name="update_password" value="TRUE">Change your password</button>
		<div class="clear"></div>
	</div>
	
<?php echo form_close(); ?>