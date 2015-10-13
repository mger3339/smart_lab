<?php $row_id = (property_exists($row, 'id')) ? 'user-' . $row->id : 'new-user'; ?>
    <?php echo form_open_multipart('admin/users/import', array('id' => $row_id, 'class' => 'import-row-form')); ?>
<?php if ($action_title): ?>
    <h3><?php echo $action_title; ?></h3>
<?php endif; ?>

<?php if ($action == 'edit'): ?>
    <input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
<?php endif; ?>

    <input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />

    <div class="section form-section">

        <input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />
        <input type="hidden" name="currency" value="<?php echo $row->currency; ?>" />

        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-user_groups">Groups</label>
                <?php echo form_multiselect('group_id[]', $user_groups_options,$row->group_id,"id='multi_select' class='multi_select'"); ?>
                <?php echo form_error('user_groups'); ?>
            </div>
        <?php endif; ?>


        <?php if ($row->id != $this->user->id): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-user_file">Import file</label>
                <input type="file" name="userfile" id="userfile"/>
                <?php echo form_error('userfile'); ?>
            </div>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
<img class="img_loading" src="<?php echo base_url('_/img/admin/loading.gif'); ?>" />
    <div class="form-btns import-row">
        <button type="submit" name="put" value="TRUE"><?php echo lang('admin_button_label_import'); ?></button>
        <a class="nav-btn cancel-import-btn"><?php echo lang('admin_button_label_cancel'); ?></a>
        <div class="clear"></div>
    </div>

<?php echo form_close(); ?>

<?php if ($row->id != $this->user->id): ?>
    <div class="new_groups_div">
        <?php echo $this->load->view('admin/users/partials/new_group_row', array(
            'row_id'					=> $row->id,
        ), TRUE); ?>
    </div>
<?php endif; ?>
