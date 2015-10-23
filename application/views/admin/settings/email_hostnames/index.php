<div class="section email-hostnames ajax-form-wrapper">

	<h3><?php echo lang('admin_email_hostnames_title'); ?></h3>

	<?php echo lang('admin_email_hostnames_intro'); ?>

	<?php foreach ($email_hostnames as $row): ?>
	<div class="data-list-row">

		<?php echo form_open('admin/settings/email-hostnames/update/' . $row->id, array('class' => 'ajax-form')); ?>

			<input
				type="text"
				name="hostname"
				value="<?php echo $row->hostname; ?>"
				maxlength="255"
			/>

			<input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />

			<button class="update-submit" type="submit" name="update" value="<?php echo $row->id; ?>"><?php echo lang('admin_button_label_update'); ?></button>

		<?php echo form_close(); ?>

		<?php echo form_open('admin/settings/email-hostnames/delete/' . $row->id, array('class' => 'ajax-form', 'data-confirm' => "Are you sure you would like to remove this email hostname?\n")); ?>

			<button class="delete-submit" type="submit" name="delete" value="<?php echo $row->id; ?>"><?php echo lang('admin_button_label_delete'); ?></button>

		<?php echo form_close(); ?>

		<div class="clear"></div>

		<?php if ($error_id == $row->id): ?>
		<?php echo form_error('hostname'); ?>
		<?php endif; ?>

	</div>
	<?php endforeach; ?>

	<div class="clear"></div>

	<div class="data-list-row">

		<?php echo form_open('admin/settings/email-hostnames/add', array('class' => 'ajax-form')); ?>

			<label for="add_email_hostname"><?php echo lang('admin_add_email_hostname_action_title'); ?></label>

			<input
				id="add_email_hostname"
				type="text"
				name="hostname"
				maxlength="255"
			/>

			<button class="add-submit" type="submit" name="add" value="TRUE"><?php echo lang('admin_button_label_add'); ?></button>

		<?php echo form_close(); ?>

		<div class="clear"></div>

		<?php if ($error_id == NULL): ?>
		<?php echo form_error('hostname'); ?>
		<?php endif; ?>

	</div>

	<div class="clear"></div>

</div>