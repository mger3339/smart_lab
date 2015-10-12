<div class="admin-content client-applications">

    <h2><span>Snapshot </span> applications</h2>

    <div class="add-row">
        <button class="add-row-btn" data-url="<?php echo "admin/snapshots/applications/{$snapshot->id}/add"; ?>">Add a new application</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>

    <?php if ($applications && ! empty($applications)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/snapshots/applications/sort"); ?>">
            <?php foreach ($applications as $row): ?>
                <li class="data-row" data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/snapshots/applications/partials/snapshot_application_row', array(
                        'row'			=> $row

                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <p class="no-data">No applications have yet been assigned </p>

    <?php endif; ?>

</div>