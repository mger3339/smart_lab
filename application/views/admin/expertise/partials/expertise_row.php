<?php $row_id = 'group-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">

    <div class="section row-btns">
        <?php echo form_open("admin/expertise/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client group? All data relative to this client group will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client group?\n")); ?>
        <button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
        <?php echo form_close(); ?>
        <button class="edit-row-btn" data-url="<?php echo "admin/expertise/edit/{$row->id}"; ?>">Edit</button>
        <div class="clear"></div>
    </div>

    <div class="section">

        <div class="control-group control-group-large zxc">
            <p><?php echo $row->expertise; ?></p>
        </div>

        <div class="clear"></div>

    </div>

</div>