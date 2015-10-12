<div class="section auto-register ajax-form-wrapper">
	
	<h3><?php echo lang('admin_auto_register_title'); ?></h3>
	
	<?php echo lang('admin_auto_register_intro'); ?>
	
	<?php echo form_open('admin/settings/auto-register/update', array('class' => 'ajax-form')); ?>
		
		<div class="control-group">
			<label><?php echo lang('admin_auto_register_label'); ?></label>
			<input type="hidden" name="user_auto_register" value="0" />
			<input
				id="user_auto_register"
				type="checkbox"
				name="user_auto_register"
				value="1"
				<?php if ($user_auto_register == 1 || $this->input->post('user_auto_register') == 1): ?>
				checked="checked"
				<?php endif; ?>
s			/>
			<label for="user_auto_register" class="checkbox-label<?php if ($user_auto_register == 1|| $this->input->post('user_auto_register') == 1): ?> checked<?php endif; ?>"><?php echo lang('admin_auto_register_checkbox_label'); ?></label>
			<?php echo form_error('user_auto_register'); ?>
		</div>
		
		<div class="control-group">
			<label for="auto_register_password"><?php echo lang('admin_auto_register_password_label'); ?></label>
			<input
				id="auto_register_password"
				type="password"
				name="auto_register_password"
				value=""
				maxlength="60"
				autocomplete="off"
			/>
			<?php echo form_error('auto_register_password'); ?>
		</div>
		
		<div class="control-group">
			<label for="auto_register_password_confirm"><?php echo lang('admin_auto_register_password_confirm_label'); ?></label>
			<input
				id="auto_register_password_confirm"
				type="password"
				name="auto_register_password_confirm"
				value=""
				maxlength="60"
				autocomplete="off"
			/>
			<?php echo form_error('auto_register_password_confirm'); ?>
		</div>
		
		<div class="control-group button-control-group">
			<button class="add-submit" type="submit" name="update" value="TRUE"><?php echo lang('admin_button_label_update'); ?></button>
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
		
	<?php echo form_close(); ?>
	
</div>