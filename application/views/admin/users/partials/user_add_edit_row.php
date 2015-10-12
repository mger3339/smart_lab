<?php $row_id = (property_exists($row, 'id')) ? 'user-' . $row->id : 'new-user'; ?>

<?php if ($action == 'add'): ?>
<?php echo form_open('admin/users/add', array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
<?php echo form_open("admin/users/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>

	<?php if ($action_title): ?>
	<h3><?php echo $action_title; ?></h3>
	<?php endif; ?>

	<?php if ($action == 'edit'): ?>
	<input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
	<?php endif; ?>

	<input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />

	<div class="section form-section">

		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-firstname">First name</label>
			<input
				id="<?php echo $row_id; ?>-firstname"
				type="text"
				name="firstname"
				value="<?php echo set_value('firstname', $row->firstname); ?>"
				maxlength="60"
			/>
			<?php echo form_error('firstname'); ?>
		</div>

		<div class="control-group control-group-large">
			<label for="<?php echo $row_id; ?>-lastname">Last name</label>
			<input
				id="<?php echo $row_id; ?>-lastname"
				type="text"
				name="lastname"
				value="<?php echo set_value('lastname', $row->lastname); ?>"
				maxlength="60"
			/>
			<?php echo form_error('lastname'); ?>
		</div>

		<?php if ($row->id != $this->user->id): ?>
		<div class="control-group">
			<label for="<?php echo $row_id; ?>-user_role">User role</label>
			<?php echo form_dropdown('user_role', $user_role_options, $row->user_role); ?>
            <?php echo form_error('user_role'); ?>
		</div>
		<?php endif; ?>
        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-user_groups">Groups</label>
                <?php echo form_multiselect('group_id[]', $user_groups_options,$row->group_id,"id='multi_select' class='multi_select'"); ?>
                <?php echo form_error('user_groups'); ?>
            </div>
        <?php endif; ?>

		<div class="clear"></div>

		<div class="control-group control-group-wide">
			<label for="<?php echo $row_id; ?>-email">Email</label>
			<input
				id="<?php echo $row_id; ?>-email"
				type="text"
				name="email"
				value="<?php echo set_value('email', $row->email); ?>"
				maxlength="255"
			/>
			<?php echo form_error('email'); ?>
		</div>

        <div class="control-group control-group-wide">
            <label for="<?php echo $row_id; ?>-username">Username</label>
            <input
                id="<?php echo $row_id; ?>-username"
                type="text"
                name="username"
                value="<?php echo set_value('username', $row->username); ?>"
                maxlength="60"
                />
            <?php echo form_error('username'); ?>

        </div>

        <div class="clear"></div>

        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label>Active?</label>
                <input type="hidden" name="active" value="0" />
                <input
                    id="<?php echo $row_id; ?>-active"
                    type="checkbox"
                    name="active"
                    value="1"
                    <?php if ($row->active == 1): ?>
                        checked="checked"
                    <?php endif; ?>
                    />
                <label for="<?php echo $row_id; ?>-active" class="checkbox-label<?php if ($row->active == 1): ?> checked<?php endif; ?>">Active</label>
                <?php echo form_error('active'); ?>
            </div>
        <?php endif; ?>

        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label>Locked?</label>
                <input type="hidden" name="locked" value="0" />
                <input
                    id="<?php echo $row_id; ?>-locked"
                    type="checkbox"
                    name="locked"
                    value="1"
                    <?php if ($row->locked == 1): ?>
                        checked="checked"
                    <?php endif; ?>
                    />
                <label for="<?php echo $row_id; ?>-locked" class="checkbox-label<?php if ($row->locked == 1): ?> checked<?php endif; ?>">Locked</label>
                <?php echo form_error('active'); ?>
            </div>
        <?php endif; ?>

        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-city">City</label>
                <input
                    id="<?php echo $row_id; ?>-city"
                    type="text"
                    name="city"
                    value="<?php echo set_value('city', $row->city); ?>"
                    maxlength="60"
                    />
                <?php echo form_error('city'); ?>

            </div>
        <?php endif; ?>
        <div class="clear"></div>
        <?php if ($action == 'edit'): ?>
            <div class="control-group control-group-large">
                <label for="<?php echo $row_id; ?>-password">Your current password</label>
                <input
                    id="<?php echo $row_id; ?>-password"
                    type="password"
                    name="password"
                    value=""
                    maxlength="60"
                    />
                <?php echo form_error('password'); ?>

            </div>
        <?php endif; ?>
        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-password">Your new password</label>
            <input
                id="<?php echo $row_id; ?>-password"
                type="password"
                name="new_password"
                value=""
                maxlength="60"
                />
            <?php echo form_error('password'); ?>

        </div>

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-password">Re-type your new password</label>
            <input
                id="<?php echo $row_id; ?>-password"
                type="password"
                name="new_password_confirm"
                value=""
                maxlength="60"
                />
            <?php echo form_error('password'); ?>
        </div>

		<div class="clear"></div>

	</div>

	<input type="hidden" name="country_iso" value="<?php echo $row->country_iso; ?>" />
	<input type="hidden" name="time_zone" value="<?php echo $row->time_zone; ?>" />
	<input type="hidden" name="currency" value="<?php echo $row->currency; ?>" />

	<div class="form-btns">
		<?php if ($action == 'add'): ?>
		<button type="submit" name="put" value="TRUE"><?php echo lang('admin_button_label_add'); ?></button>
		<a class="nav-btn cancel-add-btn"><?php echo lang('admin_button_label_cancel'); ?></a>
		<?php elseif ($action == 'edit'): ?>
		<button type="submit" name="update" value="TRUE"><?php echo lang('admin_button_label_update'); ?></button>
		<a class="nav-btn cancel-edit-btn"><?php echo lang('admin_button_label_cancel'); ?></a>
		<?php endif; ?>
		<div class="clear"></div>
	</div>

<?php echo form_close(); ?>