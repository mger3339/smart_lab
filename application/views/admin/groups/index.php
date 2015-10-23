<div class="admin-content client-applications">
    <div class="admin_menu_list">
        <a href="<?php echo "email-hostname"; ?>"><div class="menu_list">Valid Email Hostnames</div></a>
        <a href="<?php echo "settings"; ?>"><div class="menu_list">Access Setings</div></a>
        <a href="<?php echo "expertise"; ?>"><div class="menu_list">Expertise</div></a>
        <a href="<?php echo "groups"; ?>"><div class="menu_list">Groups</div></a>
        <a href="<?php echo "users"; ?>"><div class="menu_list">Users list</div></a>
    </div>
    <h2><span>Groups (<?php echo count($groups)?>)</h2>
    <div class="add-row">
        <button class="add-row-btn" data-url="<?php echo "admin/groups/add"; ?>">Add a new group</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($groups && ! empty($groups)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/groups/sort"); ?>">
            <?php foreach ($groups as $row): ?>
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