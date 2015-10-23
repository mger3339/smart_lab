<div class="admin-content client-applications">
    <div class="admin_menu_list">
        <a href="<?php echo "email-hostname"; ?>"><div class="menu_list">Valid Email Hostnames</div></a>
        <a href="<?php echo "settings"; ?>"><div class="menu_list">Access Setings</div></a>
        <a href="<?php echo "expertise"; ?>"><div class="menu_list">Expertise</div></a>
        <a href="<?php echo "groups"; ?>"><div class="menu_list">Groups</div></a>
        <a href="<?php echo "users"; ?>"><div class="menu_list">Users list</div></a>
    </div>
    <h2><span>Expertise (<?php echo count($expertise)?>)</h2>
    <div class="add-row">
        <button class="merge-row-btn" data-url="<?php echo "admin/expertise/add_merge"; ?>">Merge expertise</button>
        <button class="add-row-btn" data-url="<?php echo "admin/expertise/add"; ?>">Add a new expertise</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($expertise && ! empty($expertise)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/groups/sort"); ?>">
            <?php foreach ($expertise as $row): ?>
                <li class="data-row" data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/expertise/partials/expertise_row', array(
                        'row'			=> $row,
                        'expertise'	=> $expertise,
                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <p class="no-data">No groups have been set up</p>

    <?php endif; ?>

</div>