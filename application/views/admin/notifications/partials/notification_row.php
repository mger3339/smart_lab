<?php
$notification_users = object_array_to_dropdown_array($row->notification_user, 'user.id', 'user.fullname');
$notification_groups = object_array_to_dropdown_array($row->notification_group, 'client_group.id', 'client_group.name');
?>
<div class="row">
	
	<div class="section row-btns">
			<p class="right"><?php echo date('F d Y H:i', $row->created); ?></p>
			<button class="edit-row-btn" data-url="<?php echo "admin/users/edit"; ?>"><?php echo lang('admin_notifications_button_label_resend'); ?></button>
	</div>
	
	<div class="section">
		<p><strong><?php echo $row->subject; ?></strong></p>

		<p>
			<strong>To:</strong>
			<?php echo implode(', ', $notification_users) . ( ! empty($notification_users) ? ', ' : '') . implode(', ', $notification_groups); ?>
		</p>
		<p><?php echo $row->message; ?></p>
	</div>

</div>