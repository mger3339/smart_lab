I<div class="admin-content client-snapshot">
    <h2><span>Snapshots (<?php echo count($this->client->snapshots)?>)</h2>
    <div class="add-row">
        <button class="add-row-btn" data-url="<?php echo "admin/snapshots/add"; ?>">Add a new snapshot</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($this->client->snapshots && ! empty($this->client->snapshots)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/snapshots/sort"); ?>">
            <?php foreach ($this->client->snapshots as $row): ?>
                <li class="data-row" data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/snapshots/partials/client_snapshot_row', array(
                        'row'			=> $row,
                        'snapshots'	=> $this->client->snapshots,
                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <p class="no-data">No snapshots have yet been assigned for <?php echo $this->client->name; ?></p>

    <?php endif; ?>

</div>