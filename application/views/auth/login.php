<div class="login">
	
	<h1><?php echo $client_name; ?><span><br /><?php echo lang('login_title'); ?></span></h1>
	
	<?php echo form_open(); ?>
		
		<?php if ($user_redirect): ?>
		<input type="hidden" name="user_redirect" value="<?php echo $user_redirect; ?>" />
		<?php endif; ?>
		
		<div class="control-group">
			<label for="username"><?php echo lang('login_username_label'); ?></label>
			<input id="username" type="text" name="username" value="" maxlength="255" autocomplete="off" />
			<?php echo form_error('username'); ?>
		</div>
		
		<div class="control-group">
			<label for="password"><?php echo lang('login_password_label'); ?></label>
			<input id="password" type="password" name="password" value="" autocomplete="off" />
			<?php echo form_error('password'); ?>
		</div>
		
		<div class="form-btns">
			<a href="<?php echo site_url('reset-password'); ?>" title="<?php echo lang('login_reset_password'); ?>"><?php echo lang('login_reset_password'); ?></a>
			<button type="submit" name="login" value="TRUE"><?php echo lang('login_submit'); ?></button>
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
		
	<?php echo form_close(); ?>
	
</div>