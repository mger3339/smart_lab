<?php echo form_open('admin/users/edit_user_role', array('class' => 'user-role-row-form')); ?>
    <input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />

    <div class="section form-section">
            <div class="control-group">
                <label for="user_role">User role</label>
                <?php echo form_dropdown('user_role', $all_roles); ?>
                <?php echo form_error('user_role'); ?>
            </div>

        <input type="hidden" name="user_ids" value="<?php echo $user_ids?>"/>

        <div class="form-btns">
                <button type="submit" name="update" value="TRUE"><?php echo lang('admin_button_label_update'); ?></button>
                <a class="nav-btn cancel-user-role-btn"><?php echo lang('admin_button_label_cancel'); ?></a>
            <div class="clear"></div>
        </div>
    </div>
<?php echo form_close(); ?>

