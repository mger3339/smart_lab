<h1><?php echo lang('applications_nav_title'); ?></h1>

<?php if ($client->applications): ?>
<?php $client_applications = array_reverse($client->applications, TRUE); ?>
<?php foreach ($client_applications as $application): ?>
<?php if ($application->active == 1 || $this->user->admin == 1): ?>
    <?php if($application->valid_application): ?>
        <a class="system-nav-item-btn <?php echo $application->application; ?>" href="<?php echo site_url("launch/{$application->id}"); ?>" title="<?php echo $application->name; ?>">
    <?php else: ?>
        <a class="system-nav-item-btn <?php echo $application->application; ?>" title="<?php echo $application->name; ?>">
    <?php endif; ?>
        <span class="icon"></span>
        <span class="title"><?php echo $application->name; ?></span>
    </a>
<?php endif; ?>
<?php endforeach ?>
<?php endif; ?>

<a class="system-nav-item-btn home" href="<?php echo site_url('home'); ?>" title="<?php echo lang('applications_nav_home'); ?>">
	<span class="icon"></span>
	<span class="title"><?php echo lang('applications_nav_home'); ?></span>
</a>

<?php if ($user->admin == 1): ?>
<a class="system-nav-item-btn admin" href="<?php echo site_url('admin'); ?>" title="<?php echo lang('user_nav_admin'); ?>">
	<span class="icon"></span>
	<span class="title"><?php echo lang('user_nav_admin'); ?></span>
</a>
<?php endif; ?>

<div class="clear"></div>