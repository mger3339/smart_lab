<div class="admin-content client-applications">

    <h2><span>Snapshot </span> sessions</h2>

    <div class="add-row">

        <button class="add-row-btn" data-url="<?php echo "admin/snapshots/sessions/{$snapshot->id}/add"; ?>">Add a new session</button>
        <div class="clear"></div>
        <ul class="data-rows-list">
            <li class="data-row"></li>
        </ul>
    </div>
    <?php if ($sessions && ! empty($sessions)): ?>

        <table class="session_table data-rows-list">
            <thead>
            <tr>
                <th>Session</th>
                <th>Facilitator</th>
                <th>Group</th>
                <th>Start time</th>
                <th class="not_sort">End time</th>
                <th class="not_sort"></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($sessions as $row): ?>
                <tr data-id="<?php echo $row->id; ?>">
                    <?php echo $this->load->view('admin/snapshots/sessions/partials/snapshot_session_row', array(
                        'row'			=> $row

                    ), TRUE); ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>

        <p class="no-data">No sessions have yet been assigned </p>

    <?php endif; ?>

</div>