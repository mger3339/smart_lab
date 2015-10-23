<div class="admin-content settings">
    <div class="admin_menu_list">
        <a href="<?php echo "email-hostname"; ?>"><div class="menu_list">Valid Email Hostnames</div></a>
        <a href="<?php echo "settings"; ?>"><div class="menu_list">Access Setings</div></a>
        <a href="<?php echo "expertise"; ?>"><div class="menu_list">Expertise</div></a>
        <a href="<?php echo "groups"; ?>"><div class="menu_list">Groups</div></a>
        <a href="<?php echo "users"; ?>"><div class="menu_list">Users list</div></a>
    </div>
	
	<h2><?php echo lang('admin_settings_title'); ?></h2>
	
	<?php echo $this->load->view('admin/settings/auto_register/index', $auto_register_content, TRUE); ?>
	
</div>