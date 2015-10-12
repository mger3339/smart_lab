<?php $row_id = 'application-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">

    <div class="section row-btns">
        <div class="handle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <?php echo form_open("admin/snapshots/applications/delete/{$row->id}/{$snapshot->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client application? All data relative to this client application will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client application?\n")); ?>
        <button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
        <?php echo form_close(); ?>
        <button class="edit-row-btn" data-url="<?php echo "admin/snapshots/applications/{$snapshot->id}/edit/{$row->id}"; ?>">Edit</button>
        <div class="clear"></div>
    </div>

    <div class="section">

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Application name</label>
            <p><?php echo $row->name; ?></p>
        </div>

        <div class="control-group">
            <label>Application</label>
            <p><?php echo $row->original_name; ?></p>
        </div>

        <div class="control-group">
            <label>Active</label>
            <p>Active: <strong>&nbsp;<?php if ($row->active == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
        </div>

        <div class="clear"></div>

    </div>


</div>