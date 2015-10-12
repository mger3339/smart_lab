<?php $row_id = 'user-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">

	<div class="section">

		<ul class="user_row_ul">
            <div class="section row-btns ">
                <?php if ($this->user->id != $row->id): ?>
                    <?php echo form_open("admin/users/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this user?\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this user?\n")); ?>
                    <button class="delete-row-btn edit_delete_btn" type="submit" name="delete" value="TRUE"><?php echo lang('admin_button_label_delete'); ?></button>
                    <?php echo form_close(); ?>
                <?php endif; ?>
                <button class="edit-row-btn edit_delete_btn" data-url="<?php echo "admin/users/edit/{$row->id}"; ?>"><?php echo lang('admin_button_label_edit'); ?></button>
                <div class="clear"></div>
            </div>

            <li class="user_checkbox">
                <div>
                    <input name="<?php echo $row_id?>" id="<?php echo $row->id?>" type="checkbox" class="check_users">
                </div>
            </li>
            <li>
                <div>
                    <label>Username</label>
                    <span><?php echo $row->username; ?></span>
                </div>
            </li>
            <li>
                <div>
                    <label>Firstname</label>
                    <span><?php echo $row->firstname; ?></span>
                </div>
            </li>
            <li>
                <div>
                    <label>Lastname</label>
                    <span><?php echo $row->lastname; ?></span>
                </div>
            </li>
            <li>
                <div>
                    <label>Email</label>
                    <span><?php echo $row->email; ?></span>
                </div>
            </li>
            <li>
                <div>
                    <label>Role</label>
                    <span><?php echo $row->user_role; ?></span>
                </div>
            </li>
        </ul>


	</div>

</div>