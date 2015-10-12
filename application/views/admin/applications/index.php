<div class="admin-content client-applications">
    <h2><span>Applications (<?php echo count($this->client->applications)?>)</h2>
    <div class="add-row">
        <button class="add-row-btn" data-url="<?php echo "admin/applications/add"; ?>">Add a new application</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($this->client->applications && ! empty($this->client->applications)): ?>

        <ul class="data-rows-list sortable-list" data-sort-update="<?php echo site_url("admin/applications/sort"); ?>">
            <?php foreach ($this->client->applications as $row): ?>
                <li class="data-row" data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/applications/partials/application_row', array(
                        'row'			=> $row,
                        'applications'	=> $applications,
                    ), TRUE); ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php else: ?>

        <p class="no-data">No applications have been set up</p>

    <?php endif; ?>

</div>