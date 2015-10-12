<?php echo form_open('admin/users/edit_groups', array('class' => 'user-group-row-form')); ?>

    <div class="section form-section">
        <div class="control-group">
            <label for="multi_select">Groups</label>
            <?php echo form_multiselect('group_id[]', $user_groups_options,[],"id='multi_select' class='multi_select'"); ?>
            <?php echo form_error('user_groups'); ?>
        </div>

        <input type="hidden" name="user_ids" value="<?php echo $user_ids?>"/>
        <div class="form-btns">
            <button type="submit" name="remove" value="TRUE"><?php echo lang('admin_button_label_remove'); ?></button>
            <button type="submit" name="overwrite" value="TRUE"><?php echo lang('admin_button_label_overwrite'); ?></button>
            <button type="submit" name="add" value="TRUE"><?php echo lang('admin_button_label_add'); ?></button>
            <a class="nav-btn cancel-user-group-btn"><?php echo lang('admin_button_label_cancel'); ?></a>
            <div class="clear"></div>
        </div>
    </div>

<?php echo form_close();?>