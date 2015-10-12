<?php $row_id = (property_exists($row, 'id')) ? 'client-application-' . $row->id : 'new-client-application'; ?>
<?php if ($action == 'add'): ?>
    <?php echo form_open("admin/snapshots/applications/{$snapshot_id}/add", array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
    <?php echo form_open("admin/snapshots/applications/{$snapshot_id}/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>

<?php if ($action_title): ?>
    <h3><?php echo $action_title; ?></h3>
<?php endif; ?>

    <div class="section">

        <?php if ($action == 'edit'): ?>
            <input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
        <?php endif; ?>

        <input type="hidden" name="client_id" value="<?php echo $row->client_id; ?>" />

        <input type="hidden" name="snapshot_id" value="<?php echo $row->snapshot_id; ?>" />

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Application name</label>
            <input
                id="<?php echo $row_id; ?>-name"
                class="client-application-name-input"
                type="text"
                name="name"
                value="<?php echo set_value('name', $row->name); ?>"
                maxlength="60"
                />
            <?php echo form_error('name'); ?>
        </div>


        <?php if ($action == 'add'): ?>
            <div class="control-group">
                <label for="<?php echo $row_id; ?>-application">Application</label>
                <?php echo form_dropdown('application', $applications, $row->application, array('class' => 'client-application-input')); ?>
                <?php echo form_error('application'); ?>
            </div>
        <?php elseif ($action == 'edit'): ?>
            <div class="control-group">
                <label>Application</label>
                <input
                    type="text"
                    name="original_name"
                    value="<?php echo $row->original_name; ?>"
                    disabled="disabled"
                    />
                <input type="hidden" name="application" value="<?php echo $row->application; ?>" />
            </div>
        <?php endif; ?>

        <input type="hidden" name="accent_color" value="888888"/>

        <div class="control-group">
            <label>Active?</label>
            <input type="hidden" name="active" value="0" />
            <input
                id="<?php echo $row_id; ?>-active"
                type="checkbox"
                name="active"
                value="1"
                <?php if ($row->active == 1): ?>checked="checked"<?php endif; ?>
                />
            <label for="<?php echo $row_id; ?>-active" class="checkbox-label<?php if ($row->active == 1): ?> checked<?php endif; ?>">Active</label>
            <?php echo form_error('active'); ?>
        </div>

        <div class="clear"></div>


    </div>

    <div class="form-btns">
        <?php if ($action == 'add'): ?>
            <button type="submit" name="put" value="TRUE">Add</button>
            <a class="nav-btn cancel-add-btn">Cancel</a>
        <?php elseif ($action == 'edit'): ?>
            <button type="submit" name="update" value="TRUE">Update</button>
            <a class="nav-btn cancel-edit-btn">Cancel</a>
        <?php endif; ?>
        <div class="clear"></div>
    </div>

<?php echo form_close(); ?>

