<!--<pre>-->
<!--    --><?php //print_r($expertise); die; ?>
<div class="admin-content client-applications">
    <h2><span>Expertise (<?php echo count($expertise)?>)</h2>
    <div class="add-row">
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