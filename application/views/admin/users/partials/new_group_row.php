<?php echo form_open("admin/groups/add_new_group", array('id' => $row_id, 'class' => 'add_new_groups', 'action' => 'post')); ?>
<input type="hidden" name="client_id" value="<?php echo $this->client->id; ?>" />
<div class="control-group">
    <label for="<?php echo $row_id; ?>-new_groups">Add new groups</label>
    <input type="text" name="name" id="new_groups" value=""/>
    <button id="add_new_groups" data-url="admin/groups/add">Add new groups</button>
    <?php echo form_error('new_groups'); ?>
</div>
<?php echo form_close(); ?>