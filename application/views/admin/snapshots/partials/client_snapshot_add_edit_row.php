<?php $row_id = (property_exists($row, 'id')) ? 'client-snapshot-' . $row->id : 'new-client-snapshot'; ?>
<?php if ($action == 'add'): ?>
    <?php echo form_open("admin/snapshots/add", array('id' => $row_id, 'class' => 'add-row-form')); ?>
<?php elseif ($action == 'edit'): ?>
    <?php echo form_open("admin/snapshots/edit/{$row->id}", array('id' => $row_id, 'class' => 'edit-row-form')); ?>
<?php endif; ?>

<?php if ($action_title): ?>
    <h3><?php echo $action_title; ?></h3>
<?php endif; ?>

    <div class="section">

        <?php if ($action == 'edit'): ?>
            <input type="hidden" name="unique_id" value="<?php echo $row->id; ?>" />
        <?php endif; ?>

        <input type="hidden" name="client_id" value="<?php echo $row->client_id; ?>" />

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Snapshot name</label>
            <input
                id="<?php echo $row_id; ?>-name"
                class="client-snapshot-name-input"
                type="text"
                name="name"
                value="<?php echo set_value('name', $row->name); ?>"
                maxlength="60"
                />
            <?php echo form_error('name'); ?>
        </div>

        <div class="clear"></div>
        <?php
        date_default_timezone_set($this->user->time_zone);
        ?>
        <div class="control-group">
            <label>Commencement date</label>
            <input
                id="<?php echo $row_id; ?>-commence"
                class="mobile-date-picker time"
                type="text"
                name="commence"
                value="<?php echo set_value('commence', gmdate("Y-m-d\TH:i:s\Z", $row->commence)); ?>"
                data-value ="<?php echo gmdate("Y-m-d\TH:i:s\Z", $row->commence); ?>"
                data-min-date=""
                data-max-date="#<?php echo $row_id; ?>-expire"
                />
            <?php echo form_error('commence'); ?>
        </div>
        <div class="control-group">
            <label>Expiry date</label>
            <input
                id="<?php echo $row_id; ?>-expire"
                class="mobile-date-picker time"
                type="text"
                name="expire"
                value="<?php echo set_value('expire', gmdate("Y-m-d\TH:i:s\Z", $row->expire)); ?>"
                data-value ="<?php echo gmdate("Y-m-d\TH:i:s\Z", $row->expire); ?>"
                data-min-date="#<?php echo $row_id; ?>-commence"
                data-max-date=""
                />
            <?php echo form_error('expire'); ?>
        </div>
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