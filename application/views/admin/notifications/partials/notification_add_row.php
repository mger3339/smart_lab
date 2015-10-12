<?php echo form_open('admin/notifications/add', array('class' => 'add-row-form')); ?>
		
	<a class="nav-btn cancel-add-btn right" title="<?php echo lang('admin_button_label_cancel'); ?>">X</a>

	<?php if ($action_title): ?>
	<h3><?php echo $action_title; ?></h3>
	<?php endif; ?>

	<input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />

	<div class="section form-section">

        <div class="control-group">
            <label>To</label>
            <input type="hidden"  name="user_ids" id="user_ids" value="">
            <input type="hidden"  name="group_ids" id="group_ids" value="">
            <div class="recipients_tags_div tags_div">
                <input id="recipients_search" type="text" placeholder="<?php echo lang('admin_add_notification_recipients_search_text'); ?>" value="" />
            </div>
            <div class="searched_recipients searched_div"></div>
			<?php echo form_error('recipients'); ?>
         </div>

		<div class="clear"></div>

		<div class="control-group">
			<label for="subject">Subject</label>
			<input
				id="subject"
				type="text"
				name="subject"
			/>
			<?php echo form_error('subject'); ?>
		</div>

		<div class="clear"></div>

		<div class="control-group">
			<textarea
				id="message"
				name="message"
				rows="10"
			></textarea>
			<?php echo form_error('message'); ?>
		</div>


	<div class="clear"></div>

	<div class="control-group">
		<button id="send-notification-btn" class="right" type="submit" name="put" value="TRUE"><?php echo lang('admin_add_notification_action_send'); ?></button>
		<a class="nav-btn">Attach files</a>
		<a class="nav-btn">Link</a>
	</div>

	<div class="form-btns">
		<div class="clear"></div>
	</div>

	</div>

<?php echo form_close(); ?>