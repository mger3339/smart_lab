<div class="reset-password">
	
	<h1><?php echo lang('reset_password_title'); ?></h1>
	
	<p><?php echo lang('reset_password_instructions'); ?></p>
	
	<?php echo form_open(); ?>
		
		<div class="control-group">
			<label for="email"><?php echo lang('reset_password_email_label'); ?></label>
			<input id="email" type="text" name="email" value="" maxlength="255" autocomplete="off" />
			<?php echo form_error('email'); ?>
		</div>
		
		<div class="form-btns">
			<a href="<?php echo site_url('login'); ?>" title="<?php echo lang('reset_password_login'); ?>"><?php echo lang('reset_password_login'); ?></a>
			<button type="submit" name="reset_password" value="TRUE"><?php echo lang('reset_password_request_submit'); ?></button>
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
		
	<?php echo form_close(); ?>
	
</div>