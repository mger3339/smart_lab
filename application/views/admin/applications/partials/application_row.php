<?php $row_id = 'application-' . $row->id; ?>

<div id="<?php echo $row_id; ?>" class="row">

    <div class="section row-btns">
        <div class="handle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <?php echo form_open("admin/applications/delete/{$row->id}", array('class' => 'delete-row-form', 'data-confirm' => "Are you sure you would like to remove this client application? All data relative to this client application will be deleted permanently.\n", 'data-re-confirm' => "Are you absolutely sure you would like to remove this client application?\n")); ?>
        <button class="delete-row-btn" type="submit" name="delete" value="TRUE">Delete</button>
        <?php echo form_close(); ?>
        <button class="edit-row-btn" data-url="<?php echo "admin/applications/edit/{$row->id}"; ?>">Edit</button>
        <div class="clear"></div>
    </div>

    <div class="section">

        <div class="control-group control-group-large">
            <label for="<?php echo $row_id; ?>-name">Application name</label>
            <p><?php echo $row->name; ?></p>
        </div>

        <div class="control-group">
            <label>Application</label>
            <p><?php echo $row->original_name; ?></p>
        </div>

        <div class="control-group">
            <label>Application Color</label>
            <p><?php echo $applications_colors[$row->accent_color]; ?></p>
        </div>

        <div class="control-group">
            <label>Active</label>
            <p>Active: <strong>&nbsp;<?php if ($row->active == 1): ?>Yes<?php else: ?>No<?php endif; ?></strong></p>
        </div>

        <div class="clear"></div>

        <div class="control-group">
            <label>Commencement date &amp; time</label>
            <p><?php echo gmstrftime("%b %d %Y %H:%M", $row->commence); ?></p>
        </div>

        <div class="control-group">
            <label>Expiry date &amp; time</label>
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

    </div>
    <?php if (is_array($row->modules) && count($row->modules) > 1): ?>
        <div class="section toggle-section">

            <h4><span class="toggle-btn"><?php echo $row->original_name; ?> modules </span><a href="<?php echo site_url("admin/application-modules/{$row->client_id}/{$row->id}"); ?>">[edit]</a></h4>

            <div class="toggle-content">

                <?php $c = 1; ?>
                <?php foreach ($row->modules as $module): ?>
                    <div class="control-group control-group-wide">
                        <label><span><?php echo $c; ?></span> <?php echo $applications[$row->application]['modules'][$module->module]; ?> module</label>
                        <label class="checkbox-label dummy-checkbox-label<?php if ($module->active == 1): ?> checked<?php endif; ?>"><?php echo $module->name; ?></label>
                    </div>
                    <?php $c++; ?>
                <?php endforeach; ?>

                <div class="clear"></div>

            </div>

        </div>
    <?php endif; ?>

</div>