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

        <div class="control-group control-group-large expertise_div">
            <div class="control-group">
                <input type="hidden" class="expertise_hidden" data-ids="" name="expertise_name" value="0" />
                <input id="expertise_checkbox" type="checkbox" name="expertise_name" />
                <label for="expertise_checkbox" data-id="<?php echo $row->id; ?>" class="expertise_chekbox"><?php echo $row->expertise; ?></label>
            </div>
        </div>

        <div class="clear"></div>

    </div>

</div>