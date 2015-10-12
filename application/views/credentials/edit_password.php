<div class="reset-password">
	
	<h1><?php echo lang('edit_password_title'); ?></h1>
	
	<p><?php echo lang('edit_password_instructions'); ?></p>
	
	<?php echo form_open(); ?>
		
		<div class="control-group">
			<label for="password"><?php echo lang('edit_password_new_password_label'); ?></label>
			<input id="password" type="password" name="password" value="" maxlength="60" autocomplete="off" />
			<?php echo form_error('password'); ?>
		</div>
		
		<div class="control-group">
			<label for="password_confirm"><?php echo lang('edit_password_confirm_password_label'); ?></label>
			<input id="password_confirm" type="password" name="password_confirm" value="" maxlength="60" autocomplete="off" />
			<?php echo form_error('password_confirm'); ?>
		</div>
		
		<div class="form-btns">
			<button type="submit" name="update_password" value="TRUE"><?php echo lang('edit_password_submit'); ?></button>
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
		
	<?php echo form_close(); ?>
	
</div>