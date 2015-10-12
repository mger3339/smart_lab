<td><?php echo $row->name;?></td>
<td>
    <?php if(!empty($row->facilitator)):?>
        <select>
            <?php foreach ($row->facilitator as $value): ?>
                <option><?php echo $value->fullname; ?></option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>

    <?php endif; ?>
</td>
<td>
    <?php if(!empty($row->groups)): ?>
        <select>
            <?php foreach ($row->groups as $value): ?>
                <option><?php echo $value->name; ?></option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>

    <?php endif; ?>
</td>
<td><?php echo isset($row->commence) ? gmdate("Y-m-d H:i", $row->commence): '';?></td>
<td><?php echo isset($row->expire) ? gmdate("Y-m-d H:i", $row->expire): '';?></td>
<td>
    <button class="edit-row-btn edit_btn" data-url="<?php echo "admin/snapshots/sessions/{$snapshot->id}/edit/{$row->id}"; ?>">Edit</button>
    <?php echo form_open("admin/snapshots/sessions/delete/{$row->id}/{$snapshot->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client application? All data relative to this client application will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client application?\n")); ?>
    <button class="delete-row-btn delete_btn" type="submit" name="delete" value="TRUE">Delete</button>
    <?php echo form_close(); ?>
</td>

<script>
</script>