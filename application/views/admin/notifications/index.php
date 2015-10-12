<div class="admin-content notifications">
	
	<h2><?php echo lang('admin_notifications_title'); ?></h2>
	<p>Send notifications to user groups or individuals.</p>
	
	<div class="add-row">
		<button class="add-row-btn" data-url="<?php echo "admin/notifications/add"; ?>"><?php echo lang('admin_notifications_button_label_new'); ?></button>
		<div class="clear"></div>
		<ul class="data-rows-list">
			<li class="data-row"></li>
		</ul>
	</div>

	<?php if (empty($admin_notifications)): ?>
		<h4><?php echo lang('admin_notifications_no_sent_notifications'); ?></h4>
	<?php else: ?>
	
	<ul class="data-rows-list">
		<?php foreach ($admin_notifications as $key => $notification): ?>
		<li class="data-row">
			<?php $this->load->view('admin/notifications/partials/notification_row', array('row' => $notification,)); ?>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php endif; ?>
	
</div>