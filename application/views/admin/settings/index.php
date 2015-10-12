<div class="admin-content settings">
	
	<h2><?php echo lang('admin_settings_title'); ?></h2>
	
	<?php echo $this->load->view('admin/settings/auto_register/index', $auto_register_content, TRUE); ?>
	
	<?php echo $this->load->view('admin/settings/email_hostnames/index', $email_hostnames_content, TRUE); ?>
	
</div>