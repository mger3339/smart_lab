<h1><?php echo $user->fullname; ?></h1>

<a class="system-nav-item-btn logout" href="<?php echo site_url('logout'); ?>" title="<?php echo lang('user_nav_logout'); ?>">
	<span class="icon"></span>
	<span class="title"><?php echo lang('user_nav_logout'); ?></span>
</a>

<a class="system-nav-item-btn account" href="<?php echo site_url('my-account'); ?>" title="<?php echo lang('user_nav_account'); ?>">
	<span class="icon"></span>
	<span class="title"><?php echo lang('user_nav_account'); ?></span>
</a>

<div class="clear"></div>