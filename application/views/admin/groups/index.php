<div class="admin-content client-applications">
    <h2><span>Groups (<?php echo count($this->client->groups)?>)</h2>
    <div class="add-row">
        <button class="add-row-btn" data-url="<?php echo "admin/groups/add"; ?>">Add a new group</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($this->client->groups && ! empty($this->client->groups)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/groups/sort"); ?>">
            <?php foreach ($this->client->groups as $row): ?>
                <li class="data-row" data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/groups/partials/groups_row', array(
                        'row'			=> $row,
                        'groups'	=> $groups,
                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <p class="no-data">No groups have been set up</p>

    <?php endif; ?>

</div>