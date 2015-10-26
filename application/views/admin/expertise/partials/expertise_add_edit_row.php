<?php $row_id = (property_exists($row, 'id')) ? 'client-group-' . $row->id : 'new-client-group'; ?>

<?php if ($action == 'add'): ?>
    <?php echo form_open("admin/expertise/add", array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
    <?php echo form_open("admin/expertise/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>

<?php if ($action_title): ?>
    <h3><?php echo $action_title; ?></h3>
<?php endif; ?>

    <div class="section">

<?php if ($action == 'edit'): ?>
<?php endif; ?>

    <div class="control-group control-group-large">
        <label for="<?php echo $row_id; ?>-name">expertise name</label>
        <input
            id="<?php echo $row_id; ?>-name"
            class="client-group-name-input"
            type="text"
            name="name"
            value="<?php echo set_value('name', $row->expertise); ?>"
            maxlength="60"
            />
        <?php echo form_error('name'); ?>
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