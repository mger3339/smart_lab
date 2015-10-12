<?php $row_id = 'snapshot-' . $row->id; ?>
<div id="<?php echo $row_id; ?>" class="row">

    <div class="section row-btns">
        <div class="handle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <?php echo form_open("admin/snapshots/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client snapshot? All data relative to this client snapshot will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client snapshot?\n")); ?>
        <button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
        <?php echo form_close(); ?>
        <button class="edit-row-btn" data-url="<?php echo "admin/snapshots/edit/{$row->id}"; ?>">Edit</button>
        <div class="clear"></div>
    </div>

    <div class="section">

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Snapshot name</label>
            <p><?php echo $row->name; ?></p>
        </div>

        <div class="clear"></div>

        <div class="control-group">
            <label>Commencement date</label>
            <p><?php echo gmstrftime("%b %d %Y %H:%M", $row->commence); ?></p>
        </div>

        <div class="control-group">
            <label>Expiry date</label>
            <p><?php echo gmstrftime("%b %d %Y %H:%M", $row->expire); ?></p>
        </div>

        <div class="clear"></div>

        <div class="control-group">
            <label>Created</label>
            <p><?php echo strftime("%a %e %b %Y, %T", $row->created); ?></p>
        </div>

        <?php if ($row->modified > $row->created): ?>
            <div class="control-group">
                <label>Last Modified</label>
                <p><?php echo strftime("%a %e %b %Y, %T", $row->modified); ?></p>
            </div>
        <?php endif; ?>

        <div class="clear"></div>
        <div class="section toggle-section">

            <h4><span class="toggle-btn">Applications </span><a href="<?php echo site_url("admin/snapshots/applications/{$row->id}"); ?>">[edit]</a></h4>

            <div class="toggle-content">

                <?php if ($row->applications && ! empty($row->applications)): ?>

                    <?php $c = 1; ?>
                    <?php foreach ($row->applications as $client_application): ?>
                        <div class="control-group">
                            <label><span><?php echo $c; ?></span> <?php echo $client_application->original_name; ?></label>
                            <p><?php echo $client_application->name; ?></p>
                        </div>
                        <?php $c++; ?>
                    <?php endforeach; ?>

                    <div class="clear"></div>

                <?php else: ?>

                    <h5>No applications assigned</h5>

                <?php endif; ?>

            </div>

        </div>

        <div class="section toggle-section">

            <h4><span class="toggle-btn">Sessions </span><a href="<?php echo site_url("admin/snapshots/sessions/{$row->id}"); ?>">[edit]</a></h4>

            <div class="toggle-content">

                <?php if ($row->sessions && ! empty($row->sessions)): ?>

                    <?php $c = 1; ?>
                    <?php foreach ($row->sessions as $snapshot_session): ?>
                        <div class="control-group">
                            <label><span><?php echo $c; ?></span> Session</label>
                            <p><?php echo $snapshot_session->name; ?></p>
                        </div>
                        <?php $c++; ?>
                    <?php endforeach; ?>

                    <div class="clear"></div>

                <?php else: ?>

                    <h5>No sessions assigned</h5>

                <?php endif; ?>

            </div>

        </div>
    </div>

</div>