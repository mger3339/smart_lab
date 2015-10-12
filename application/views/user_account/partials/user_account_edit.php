<?php echo form_open('user_account/update_account', array('id' => 'user-account-update', 'class' => 'user-account-form')); ?>
	
	<input type="hidden" name="unique_id" value="<?php echo $user->id; ?>" />
	
	<input type="hidden" name="client_id" value="<?php echo $user->client_id; ?>" />
	
	<div class="section">
		
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
		
		<div class="clear"></div>
		
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
	
	<div class="section">
		
		<h3>Locale settings</h3>
		
		<div class="control-group control-group-wide">
			<label for="time_zone">Timezone</label>
			<?php echo form_dropdown('time_zone', $time_zone_options, $user->time_zone); ?>
			<?php echo form_error('time_zone'); ?>
		</div>
		
		<div class="clear"></div>
		
		<div class="control-group">
			<label for="country_iso">Country</label>
			<?php echo form_dropdown('country_iso', $country_options, $user->country_iso); ?>
			<?php echo form_error('country_iso'); ?>
		</div>
		
		<div class="control-group">
			<label for="currency">Currency</label>
			<?php echo form_dropdown('currency', $currency_options, $user->currency); ?>
			<?php echo form_error('currency'); ?>
		</div>
		
		<div class="clear"></div>
		
	</div>
	
	<div class="form-btns">
		<button type="submit" name="update_account" value="TRUE">Update your account</button>
		<div class="clear"></div>
	</div>
	
<?php echo form_close(); ?>